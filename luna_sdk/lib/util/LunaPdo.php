<?php

LunaLoader::import("luna_core.LunaConfigMagt");
LunaLoader::import("luna_lib.log.LunaLogger");
LunaLoader::import("luna_lib.util.LunaWebUtil");

class LunaPdo extends PDO 
{
	private static  $_globalInstance;
	private static  $_instance=array();
	
	public static function GetInstance($cfgNodeName='')
	{
		if(empty($cfgNodeName)){
			if(self::$_globalInstance==null){
				self::$_globalInstance=new LunaPdo();
			}
			return self::$_globalInstance;
		}else{
			if(isset(self::$_instance[$cfgNodeName])==false){
				self::$_instance[$cfgNodeName]=new LunaPdo($cfgNodeName);
			}
			return self::$_instance[$cfgNodeName];
		}
	}
	
	public static function ResetInstance($cfgNodeName='')
	{
		if(empty($cfgNodeName)){
			unset(self::$_globalInstance);
			self::$_globalInstance=null;
		}else{
			unset(self::$_instance[$cfgNodeName]);
		}
	}
	
	public static function ResetAllInstance()
	{
		if(isset(self::$_globalInstance) &&  self::$_globalInstance!=null){
			self::$_globalInstance=null;
		}
		foreach (self::$_instance as $cfgName => $pdoInstance){
			if(isset($pdoInstance) &&  $pdoInstance!=null){
				$pdoInstance=null;
			}
		}
		self::$_instance=array();
	}
	
	private $_configs;
	
	private $_dns;
	private $_user;
	private $_pass;
	private $_charset='utf8';
	private $_persistent=false;
	
	public function __construct($cfgNodeName='')
	{
		$this->_configs=LunaConfigMagt::getInstance()->getLunaSDKConfigSection("Pdo");
		$globalCfg=$this->_configs["global"];
		$this->_dns=$globalCfg["dsn"];
		$this->_user=$globalCfg["user"];
		$this->_pass=$globalCfg["pass"];
		if(isset($globalCfg["charset"]) && empty($globalCfg["charset"])==false){
			$this->_charset=$globalCfg["charset"];
		}
		if(isset($globalCfg["persistent"]) && empty($globalCfg["persistent"])==false){
			$this->_persistent=($globalCfg["persistent"]=="true");
		}
		
		if(empty($cfgNodeName)==false && isset($this->_configs["special"])){
			foreach ($this->_configs["special"] as $nodeName => $nodeCfg){
				if($nodeName==$cfgNodeName){
					if(isset($nodeCfg["dsn"])){
						$this->_dns=$nodeCfg["dsn"];
					}
					if(isset($nodeCfg["user"])){
						$this->_user=$nodeCfg["user"];
					}
					if(isset($nodeCfg["pass"])){
						$this->_pass=$nodeCfg["pass"];
					}
					if(isset($nodeCfg["charset"])){
						$this->_charset=$nodeCfg["charset"];
					}
					if(isset($nodeCfg["persistent"])){
						$this->_persistent=($nodeCfg["persistent"]=="true");
					}						
					break;
				}
			}
		}
		ini_set('mysql.connect_timeout', 3);
		$dbOptions=array(PDO::ATTR_TIMEOUT=>2,PDO::MYSQL_ATTR_INIT_COMMAND =>sprintf("SET NAMES '%s'",$this->_charset),);
		if($this->_persistent){
			$dbOptions[PDO::ATTR_PERSISTENT]=true;
		}
		@parent::__construct( $this->_dns,$this->_user,$this->_pass,$dbOptions);
	}
	
	public function __destruct()
	{

	}

	private $_errorInfo="";
	public function errorInfo()
	{
		if(isset($this->_errorInfo) && empty($this->_errorInfo)==false){
			return $this->_errorInfo;
		}
		return parent::errorInfo();
	}	

	private function _logErrorMsg($sql,$time_start,$stmtErrorInfo=array(),$params=array())
	{
		$errorMsg=$this->_errorInfo;
		if(empty($errorMsg)){
			$errorMsg="(Pdo Errors:".json_encode($stmtErrorInfo).")";
		}
		$remote_ip = LunaWebUtil::getClientIp();
		$logMsg=sprintf("<LunaPdo>\t<%s>\t<%s>\t<%s>\t<%s>\t<%s>",microtime(true)-$time_start,$errorMsg,str_replace("\t", " ", $sql),$remote_ip,json_encode($params));
		LunaLogger::getInstance()->error($logMsg);
	}
	private function _logInfoMsg($sql,$time_start,$params=array())
	{
		$remote_ip = LunaWebUtil::getClientIp();
		$logMsg=sprintf("<LunaPdo>\t<%s>\t<%s>\t<%s>\t<%s>",microtime(true)-$time_start,str_replace("\t", " ", $sql),$remote_ip,json_encode($params));
		LunaLogger::getInstance()->info($logMsg);		
	}
	
	public function gen_uuid()
	{
		$sql="SELECT REPLACE(UUID(), '-', '')";
		$data=$this->query_with_prepare($sql,array(),PDO::FETCH_NUM);
		if($data && is_array($data) && count($data)>0 && is_array($data[0]) && count($data[0])>0){
			return $data[0][0];			
		}
		return false;
	}
	
	public function exec_with_prepare($sql, $params=array(),$initAtrributes=array())
	{   
		$this->_errorInfo="";
		$_time_start = microtime(true);
		
		if( !is_array($params) ){
			$this->_errorInfo = 'params is not a array.';
			return false;
		}

		$stmt;
		if(isset($initAtrributes) && is_array($initAtrributes) && count($initAtrributes)>0){
			foreach ($initAtrributes as $initSql){
				$stmt=$this->prepare($initSql);
				$stmt->execute();
			}
		}
		$stmt=$this->prepare($sql);
		
		if(!$stmt){
			$this->_logErrorMsg($sql, $_time_start,parent::errorInfo(),$params);
			return false;
		}
			
		if( !$stmt->execute($params)){
			$this->_logErrorMsg($sql, $_time_start,$stmt->errorInfo(),$params);
			return false;
		}
		$this->_logInfoMsg($sql, $_time_start,$params);
		return $stmt->rowCount();
	}
	
	public function query_with_prepare($sql, $params=array(), $fetch_style = PDO::FETCH_ASSOC,$initAtrributes=array())
	{
		$this->_errorInfo="";
		$_time_start = microtime(true);
		
		if( !is_array($params)){
			$this->_errorInfo = 'params is not a array.';
			return false;
		}
		
		$stmt;
		if(isset($initAtrributes) && is_array($initAtrributes) && count($initAtrributes)>0){
			foreach ($initAtrributes as $initSql){
				$stmt=$this->prepare($initSql);
				$stmt->execute();
			}
		}
		
		$stmt=$this->prepare($sql);
		
		if(!$stmt){
			$this->_logErrorMsg($sql, $_time_start,parent::errorInfo(),$params);
			return false;
		}
			
		if( !$stmt->execute($params)){
			$this->_logErrorMsg($sql, $_time_start,$stmt->errorInfo(),$params);
			return false;
		}
		$ret = $stmt->fetchAll($fetch_style);
		$this->_logInfoMsg($sql, $_time_start,$params);
		
		return $ret;
	}	
}

