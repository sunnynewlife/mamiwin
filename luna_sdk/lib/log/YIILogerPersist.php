<?php
LunaLoader::import("luna_lib.log.ILunaLoggerPersist");
LunaLoader::import("luna_lib.log.LunaLogger");
LunaLoader::import("luna_lib.util.LunaWebUtil");

class YIILogerPersist implements ILunaLoggerPersist 
{
	function logMessage($message)
	{
		$this->_logRecords[]=$message;		
	}
	function init($configs)
	{
		$this->_logDir=$configs["dir"];
		if(isset($configs["sessionCookieName"])){
			$this->_sessionCookieName=$configs["sessionCookieName"];
		}else {
			$this->_sessionCookieName="PHPSESSID";
		}
	}
	private $_logDir;
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
	
	private function serializeLog()
	{
		$logTimestamp=date('Y-m-d H:i:s',$this->_logStartTime);
		$serviceTime=$this->_endTime-$this->_startTime;
		$logMessages="\n$logTimestamp\t$serviceTime\t".$this->_sessionId."\t".$this->_clientIp."\t".$this->_requestUri."\t".$this->_requestType."\t".$this->_requestQueryString."\t".$this->_localServerIp;
		if(isset($this->_logRecords))
		{
			$logDetailsCount=count($this->_logRecords);
			for($index=0;$index<$logDetailsCount;$index++){
				$logMessages=sprintf("%s\n\t%s",$logMessages,$this->_logRecords[$index]);
			}
		}
		if((!file_exists($this->_logDir)) || (!is_dir($this->_logDir))){
			@mkdir($this->_logDir, 0775);
		}
		$dirMonth = $this->_logDir . DIRECTORY_SEPARATOR . date("Y.m");
		if((!file_exists($dirMonth)) || (!is_dir($dirMonth))){
			@mkdir($dirMonth, 0775);
		}
		$filename = $dirMonth . DIRECTORY_SEPARATOR . date('Y.m.d').'.access.log';
	
		$msgEncoding=mb_detect_encoding($logMessages);
		$convertMsg=@mb_convert_encoding($logMessages,"UTF-8",$msgEncoding);
		if($convertMsg===false || empty($convertMsg))
		{
			$convertMsg=$logMessages;
		}
		file_put_contents ( $filename, $convertMsg, FILE_APPEND|FILE_TEXT|LOCK_EX);
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
		if(get_class($logger)=="YIILogerPersist"){			
			$logger->handleRequestBegin($httpRequestBeginEvent);
		}	
	}
	public static function HttpRequestEnd($httpRequestEndEvent)
	{
		$logger=LunaLogger::getInstance()->getLoggerPersist();
		if(get_class($logger)=="YIILogerPersist"){			
			$logger->handleRequestEnd($httpRequestEndEvent);
		}	
	}
}

?>