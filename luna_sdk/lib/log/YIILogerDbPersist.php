<?php
LunaLoader::import("luna_lib.log.ILunaLoggerPersist");
LunaLoader::import("luna_lib.log.LunaLogger");
LunaLoader::import("luna_lib.util.LunaWebUtil");
LunaLoader::import("luna_lib.util.ThreadId");

class YIILogerDbPersist implements ILunaLoggerPersist 
{
	function logMessage($message)
	{
		$this->_logRecords[]=$message;		
	}
	function init($configs)
	{
		if(isset($configs["sessionCookieName"])){
			$this->_sessionCookieName=$configs["sessionCookieName"];
		}else {
			$this->_sessionCookieName="PHPSESSID";
		}
		
		if(isset($configs["dir"])){
			$this->_logDir=$configs["dir"];
		}
		if(isset($configs["serverIp"])){
			$this->_serverIp=$configs["serverIp"];
		}
		if(isset($configs["serverPort"])){
			$this->_serverPort=$configs["serverPort"];
		}

		if(isset($configs["db_dir"])){
			$this->_dbDir=$configs["db_dir"];
		}
		if(isset($configs["db_serverIp"])){
			$this->_dbServerIp=$configs["db_serverIp"];
		}
		if(isset($configs["db_serverPort"])){
			$this->_dbServerPort=$configs["db_serverPort"];
		}

		if(isset($configs["db_name"])){
			$this->_dbName=$configs["db_name"];
		}
		if(isset($configs["persist_type"])){
			$this->_persistType=$configs["persist_type"];
		}
		if(isset($configs["non_db"])){
			$this->_nonDb=$configs["non_db"];
		}
	}
	
	private $_siteName;
	
	private $_dbName;
	private $_persistType;
	private $_nonDb;
	
	private $_serverIp;
	private $_serverPort;
	private $_logDir;
	
	private $_dbDir;			
	private $_dbServerIp;
	private $_dbServerPort;
	
	
	private $_sessionCookieName;
	
	private $_startTime;
	private $_sessionId;
	private $_endTime;
	private $_logStartTime;
	
	private $_requestUri;
	private $_requestType;
	private $_requestUrlReferrer;
	private $_requestAgent;
	private $_requestQueryString;
	private $_clientIp;
		
	private $_logRecords=array();
	
	private $_localServerIp;
	private $_httpRequest;
	
	private function getNonDbSerializeMsg()
	{
		$logTimestamp=date('Y-m-d H:i:s',$this->_logStartTime);
		$serviceTime=$this->_endTime-$this->_startTime;
		$logMessages="\n$logTimestamp\t$serviceTime\t".$this->_sessionId."\t".$this->_clientIp."\t".$this->_requestUri."\t".$this->_requestType."\t".$this->_requestQueryString."\t".$this->_localServerIp;
		if(isset($this->_logRecords)){
			$logDetailsCount=count($this->_logRecords);
			for($index=0;$index<$logDetailsCount;$index++){
				$logMessages=sprintf("%s\n\t%s",$logMessages,$this->_logRecords[$index]);
			}
		}
		$msgEncoding=mb_detect_encoding($logMessages);
		$convertMsg=@mb_convert_encoding($logMessages,"UTF-8",$msgEncoding);
		if($convertMsg===false || empty($convertMsg)){
			$convertMsg=$logMessages;
		}
		return $convertMsg;
	}
	
	private function nonDbPersist()
	{
		$strNonDb=$this->getNonDbSerializeMsg();
		if($this->_nonDb=="file"){
			if((!file_exists($this->_logDir)) || (!is_dir($this->_logDir))){
				@mkdir($this->_logDir, 0775);
			}
			$dirMonth = $this->_logDir . DIRECTORY_SEPARATOR . date("Y.m");
			if((!file_exists($dirMonth)) || (!is_dir($dirMonth))){
				@mkdir($dirMonth, 0775);
			}
			$filename = $dirMonth . DIRECTORY_SEPARATOR . date('Y.m.d').'.access.log';
			file_put_contents ( $filename, $strNonDb, FILE_APPEND|FILE_TEXT|LOCK_EX);
		}else{
			$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
			if (!$socket) {
				return false;
			}
			socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 0, "usec" => 100000));
			$result = socket_connect($socket,$this->_serverIp,$this->_serverPort);
			if ($result && is_resource($socket)) {
				socket_write($socket, $strNonDb, strlen($strNonDb));
			}
			socket_close($socket);
		}
	}
	
	private function formatStr4Db($msg,$maxLen)
	{
		if(strlen($msg)>=$maxLen){
			$msg=substr($msg, 0,$maxLen);
		}
		return str_replace("'", "\\'", $msg);
	}
	private function formatUriStr4Db($UriStr,$maxLen)
	{
		if(strlen($UriStr)>=$maxLen){
			$UriStr=substr($UriStr, 0,$maxLen);
		}
		list($logUri)=explode("?",$UriStr);
		return $logUri;
	}
	private function getMarjorLogSql($accessGUID)
	{
		return sprintf("insert into %s.LunaAccessLogHistory (SiteName,SessionId,ExecuteDuration,RequestTime,RequestUri,RequestMethod,RequestParameter,UserIp,ServerIp,RecGUID,CreateDt) values ('%s','%s',%s,'%s','%s','%s','%s','%s','%s','%s',NOW());\n",
					$this->_dbName,
					$this->_siteName,
					$this->_sessionId,
					($this->_endTime-$this->_startTime),
					date('Y-m-d H:i:s',$this->_logStartTime),
					$this->formatUriStr4Db($this->_requestUri, 100),
					$this->_requestType,
					$this->formatStr4Db($this->_requestQueryString, 2000),
					$this->_clientIp,
					$this->_localServerIp,
					$accessGUID);
	}
	
	private function getDetailLogSql($accessGUID,$msg)
	{
		$logMsgArr=explode("\t",$msg);
		if(is_array($logMsgArr) && count($logMsgArr)>0){
			if(count($logMsgArr)>=8 && $logMsgArr[3]=="<LunaPdo>"){
				return $this->getDetailLogSql4Pdo($logMsgArr,$accessGUID);
			}
			if(count($logMsgArr)==10 && $logMsgArr[3]=="<HttpInterface>"){
				return $this->getDetailLogSql4HttpInterface($logMsgArr, $accessGUID);
			}
			if(count($logMsgArr)>=9 && $logMsgArr[3]=="<hps>"){
				return $this->getDetailLogSql4Hps($logMsgArr, $accessGUID);		
			}
			if(count($logMsgArr)>=3){
				return $this->getDetailLogSql4Other($logMsgArr, $accessGUID);
			}
		}
		return "";		
	}
	
	private function getDetailLogSql4Other($logMsgArr,$accessGUID)
	{
		$LogModelType	="other";
		$LogLevelType	=substr($logMsgArr[2],1,-1);
		$ModelDuration	=0.00;
		$msg_1			= (count($logMsgArr)>=4? $this->formatStr4Db($logMsgArr[3], 2000):""); 	
		$msg_2			= (count($logMsgArr)>=5? $this->formatStr4Db($logMsgArr[4], 2000):"");
		$msg_3			= (count($logMsgArr)>=6? $this->formatStr4Db($logMsgArr[5], 2000):"");
		$msg_4			= (count($logMsgArr)>=7? $this->formatStr4Db($logMsgArr[6], 2000):"");
		
		return sprintf("insert into %s.LunaAccessDetailLogHistory (SiteName,RecGUID,LevelType,LogModelType,ModelDuration,msg_1,msg_2,msg_3,msg_4,CreateDt) values ('%s','%s','%s','%s',%s,'%s','%s','%s','%s',NOW());\n",
				$this->_dbName,
				$this->_siteName,
				$accessGUID,
				$LogLevelType,
				$LogModelType,
				$ModelDuration,
				$msg_1,$msg_2,$msg_3,$msg_4);		
	}
	
	private function getDetailLogSql4Hps($logMsgArr,$accessGUID)
	{
		$LogModelType	="hps";
		$LogLevelType	=substr($logMsgArr[2],1,-1);
		$ModelDuration	=substr($logMsgArr[4],1,-1);
		list($msg_1,$msg_2)=explode("?",substr($logMsgArr[6],1,-1));
		$msg_3			=substr($logMsgArr[8],1,-1);
		$msg_4			=substr($logMsgArr[5],1,-1);
		
		$msg_1=$this->formatStr4Db($msg_1, 2000);
		$msg_2=$this->formatStr4Db($msg_2, 2000);
		$msg_3=$this->formatStr4Db($msg_3, 2000);
		$msg_4=$this->formatStr4Db($msg_4, 2000);
		
		return sprintf("insert into %s.LunaAccessDetailLogHistory (SiteName,RecGUID,LevelType,LogModelType,ModelDuration,msg_1,msg_2,msg_3,msg_4,CreateDt) values ('%s','%s','%s','%s',%s,'%s','%s','%s','%s',NOW());\n",
				$this->_dbName,
				$this->_siteName,
				$accessGUID,
				$LogLevelType,
				$LogModelType,
				$ModelDuration,
				$msg_1,$msg_2,$msg_3,$msg_4);
	}
	
	private function getDetailLogSql4HttpInterface($logMsgArr,$accessGUID)
	{
		$LogModelType	="HttpInterface";
		$LogLevelType	=substr($logMsgArr[2],1,-1);
		$ModelDuration	=$logMsgArr[7];
		list($msg_1,$msg_2)=explode("?",$logMsgArr[6]);
		$msg_3			=$logMsgArr[9];
		$msg_4			=sprintf("%s.%s",substr($logMsgArr[4],1,-1),substr($logMsgArr[5],1,-1));
		
		$msg_1=$this->formatStr4Db($msg_1, 2000);
		$msg_2=$this->formatStr4Db($msg_2, 2000);
		$msg_3=$this->formatStr4Db($msg_3, 2000);
		$msg_4=$this->formatStr4Db($msg_4, 2000);
		
		return sprintf("insert into %s.LunaAccessDetailLogHistory (SiteName,RecGUID,LevelType,LogModelType,ModelDuration,msg_1,msg_2,msg_3,msg_4,CreateDt) values ('%s','%s','%s','%s',%s,'%s','%s','%s','%s',NOW());\n",
				$this->_dbName,
				$this->_siteName,
				$accessGUID,
				$LogLevelType,
				$LogModelType,
				$ModelDuration,
				$msg_1,$msg_2,$msg_3,$msg_4);
		
	}
	
	private function getDetailLogSql4Pdo($logMsgArr,$accessGUID)
	{
		$LogModelType	="LunaPdo";
		$LogLevelType	=substr($logMsgArr[2],1,-1);
		$ModelDuration	=substr($logMsgArr[4],1,-1);
		$msg_1			=($LogLevelType=="ERROR"?substr($logMsgArr[6],1,-1):substr($logMsgArr[5],1,-1));
		$msg_2			=($LogLevelType=="ERROR"?substr($logMsgArr[8],2,-2):substr($logMsgArr[7],2,-2));
		$msg_3			=($LogLevelType=="ERROR"?substr($logMsgArr[5],1,-1):"");
		
		$msg_1=$this->formatStr4Db($msg_1, 2000);
		$msg_2=$this->formatStr4Db($msg_2, 2000);
		$msg_3=$this->formatStr4Db($msg_3, 2000);
		
		return sprintf("insert into %s.LunaAccessDetailLogHistory (SiteName,RecGUID,LevelType,LogModelType,ModelDuration,msg_1,msg_2,msg_3,CreateDt) values ('%s','%s','%s','%s',%s,'%s','%s','%s',NOW());\n",
				$this->_dbName,
				$this->_siteName,
				$accessGUID,
				$LogLevelType,
				$LogModelType,
				$ModelDuration,
				$msg_1,$msg_2,$msg_3);
	}
	
	private function serializeLog()
	{
		$this->_siteName=LunaLogger::getInstance()->getSiteId();
		
		$accessGuid=ThreadId::guid();
		$logSql=array();
		$logSql[]=$this->getMarjorLogSql($accessGuid);
		if(isset($this->_logRecords)){
			$logDetailsCount=count($this->_logRecords);
			for($index=0;$index<$logDetailsCount;$index++){
				
				$msgEncoding=mb_detect_encoding($this->_logRecords[$index]);
				$convertMsg=@mb_convert_encoding($this->_logRecords[$index],"UTF-8",$msgEncoding);
				if($convertMsg===false || empty($convertMsg)){
					$convertMsg=$this->_logRecords[$index];
				}
				$logSql[]=$this->getDetailLogSql($accessGuid, $convertMsg);	
			}
		}
		
		$dbLogUdpReady=false;
		if($this->_persistType=="file"){
			if((!file_exists($this->_dbDir)) || (!is_dir($this->_dbDir))){
				@mkdir($this->_dbDir, 0775);
			}
			$dirMonth = $this->_dbDir . DIRECTORY_SEPARATOR . date("Y.m");
			if((!file_exists($dirMonth)) || (!is_dir($dirMonth))){
				@mkdir($dirMonth, 0775);
			}
			$dbLogFileName = $dirMonth . DIRECTORY_SEPARATOR . date('Y.m.d').'.access.sql';
		}else if($this->_persistType=="udp"){
			$dbLogSocket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
			if ($dbLogSocket) {
				socket_set_option($dbLogSocket, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 0, "usec" => 100000));
				$dbResult = socket_connect($dbLogSocket,$this->_dbServerIp,$this->_dbServerPort);
				$dbLogUdpReady= ($dbResult && is_resource($dbLogSocket));
			}
		}
				
		foreach ($logSql as $logSqlItem){
			if(empty($logSqlItem)==false){
				if($this->_persistType=="file"){
					file_put_contents ( $dbLogFileName, $logSqlItem, FILE_APPEND|FILE_TEXT|LOCK_EX);
				} else if($this->_persistType=="udp" &&  $dbLogUdpReady ){
					socket_write($dbLogSocket, $logSqlItem, strlen($logSqlItem));
				}
			}			
		}
		if($this->_persistType=="udp" && isset($dbLogSocket) && $dbLogSocket){
			socket_close($dbLogSocket);
		}
				
		if($this->_nonDb=="file" || $this->_nonDb=="udp"){
			$this->nonDbPersist();
		}
	}
	private function handleRequestBegin($httpRequestBeginEvent)
	{
		$this->_httpRequest=$httpRequestBeginEvent->sender->request;
		if(isset($_COOKIE[$this->_sessionCookieName]) && empty($_COOKIE[$this->_sessionCookieName])==false){
			$this->_sessionId=$_COOKIE[$this->_sessionCookieName];
		}
		$this->_requestUri=$this->_httpRequest->requestUri;
		$this->_requestType=$this->_httpRequest->requestType;
		
		$this->_requestUrlReferrer		=$this->emptyIfNotsetVariable($this->_httpRequest->urlReferrer);
		$this->_requestAgent			=$this->emptyIfNotsetVariable($this->_httpRequest->userAgent);
		$this->_requestQueryString		=$this->emptyIfNotsetVariable($this->_httpRequest->queryString);
		$this->_localServerIp			=$this->emptyIfNotsetVariable((isset($_SERVER["LOCAL_ADDR"])?$_SERVER["LOCAL_ADDR"]:isset($_SERVER["SERVER_ADDR"])?$_SERVER["SERVER_ADDR"]:""));
		$this->_clientIp				=LunaWebUtil::getClientIp();
		$this->_startTime				=$this->getLogTime();
		$this->_logStartTime=time();		
	}
	private function handleRequestEnd($httpRequestEndEvent)
	{
		$this->_endTime=$this->getLogTime();
		$session=$httpRequestEndEvent->sender->session;
		if(isset($session) && isset($session->sessionID) && empty($session->sessionID)==false){
			if($session->sessionID!=$this->_sessionId){
				$this->_sessionId=$session->sessionID;
			}
		}
		$this->serializeLog();
	}
	private function getLogTime(){
		list($usec, $sec) = explode(" ", microtime());
		return number_format(((float)$usec + (float)$sec)*1000,0,'.','');
	}
	private function emptyIfNotsetVariable($var)
	{
		if(isset($var)==false){
			return "";
		}
		return $var;
	}
		
	public static function HttpRequestBegin($httpRequestBeginEvent)
	{
		$logger=LunaLogger::getInstance()->getLoggerPersist();
		if(get_class($logger)=="YIILogerDbPersist"){			
			$logger->handleRequestBegin($httpRequestBeginEvent);
		}	
	}
	public static function HttpRequestEnd($httpRequestEndEvent)
	{
		$logger=LunaLogger::getInstance()->getLoggerPersist();
		if(get_class($logger)=="YIILogerDbPersist"){			
			$logger->handleRequestEnd($httpRequestEndEvent);
		}	
	}
}

?>