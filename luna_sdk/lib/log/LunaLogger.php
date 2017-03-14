<?php

LunaLoader::import("luna_core.LunaConfigMagt");
LunaLoader::import("luna_lib.log.ILunaLoggerPersist");
LunaLoader::import("luna_lib.util.ThreadId");

class LunaLogger 
{
	const _LOG_LEVEL_INFO		=1;
	const _LOG_LEVEL_NOTICE		=2;
	const _LOG_LEVEL_WARNING	=4;
	const _LOG_LEVEL_DEBUG		=8;
	const _LOG_LEVEL_ERROR		=16;
	const _LOG_LEVEL_FATAL		=32;	
	const _LOG_LEVEL_ALL		=0x0;
	const _LOG_LEVEL_NONE		=0xffffffff;
	
	const _LOG_CONFIG_SECTION	="Logger";
	
	private $_lunaLoggerPersist=null;
	private $_logLevel=self::_LOG_LEVEL_ALL;
	private $_enableColorLog=false;
	
	private $_log_site_id=""; 
	
	public function info($message)
	{
		self::_log(self::_LOG_LEVEL_INFO, $message);
	}
	public function notice($message)
	{
		self::_log(self::_LOG_LEVEL_NOTICE, $message);
	}
	public function warn($message)
	{
		self::_log(self::_LOG_LEVEL_WARNING, $message);
	}
	public function error($message)
	{
		self::_log(self::_LOG_LEVEL_ERROR, $message);
	}
	public function fatal($message)
	{
		self::_log(self::_LOG_LEVEL_FATAL,$message);
	}
	public function debug($message)
	{
		self::_log(self::_LOG_LEVEL_DEBUG,$message);
	}
	
	private function _log($logLevel,$message)
	{
		if($this->_logLevel<=$logLevel){
			$logSiteIdStr="";
			if(isset($this->_log_site_id) && empty($this->_log_site_id)==false){
				$logSiteIdStr=sprintf("[%s]\t",$this->_log_site_id);
			}
			$this->_lunaLoggerPersist->logMessage($logSiteIdStr.$this->_toLogMessage($message,$logLevel));
		}		
	}
	private function _toLogMessage($message,$logLevel)
	{
		if (is_object($message)) {
			if (method_exists($message, 'getmessage')) {
				$message = $message->getMessage();
			} else if (method_exists($message, 'tostring')) {
				$message = $message->toString();
			} else if (method_exists($message, '__tostring')) {
				if (version_compare(PHP_VERSION, '5.0.0', 'ge')) {
					$message = (string)$message;
				} else {
					$message = $message->__toString();
				}
			} else {
				$message = var_export($message, true);
			}
		} else if (is_array($message)) {
			if (isset($message['message'])) {
				if (is_scalar($message['message'])) {
					$message = $message['message'];
				} else {
					$message = var_export($message['message'], true);
				}
			} else {
				$message = var_export($message, true);
			}
		} else if (is_bool($message) || $message === NULL) {
			$message = var_export($message, true);
		}
		if($this->_enableColorLog){
			return sprintf("\033[0;35;40m[%s]\033[0m\t%s\t%s",ThreadId::getThreadId(),$this->getLogLevelString($logLevel),$message);
		}	
		else{
			return sprintf("[%s]\t%s\t%s",ThreadId::getThreadId(),$this->getLogLevelString($logLevel),$message);
		}
	}
	private function getLogLevelString($logLevel)
	{
		switch($logLevel){
			case self::_LOG_LEVEL_NOTICE:
				return "[NOTICE]";
			case self::_LOG_LEVEL_WARNING:
				return "[WARNING]";
			case self::_LOG_LEVEL_ERROR:
				if($this->_enableColorLog){
					return "\033[0;31;40m[ERROR]\033[0m";
				}else{
					return "[ERROR]";
				}
			case self::_LOG_LEVEL_FATAL:
				if($this->_enableColorLog){
					return "\033[0;31;40m[FATAL]\033[0m";
				}
				else {
					return "[FATAL]";
				}
			case self::_LOG_LEVEL_DEBUG:
				return "[DEBUG]";
			default:
				return "[INFO]";
		}
	}
	
	public function getLoggerPersist()
	{
		return  $this->_lunaLoggerPersist;
	}
	
	private function __construct() 
	{
		$confSection=LunaConfigMagt::getInstance()->getLunaSDKConfigSection(self::_LOG_CONFIG_SECTION);
		$logLevel=strtoupper($confSection["level"]); 
		switch($logLevel){
			case "INFO":
				$this->_logLevel=self::_LOG_LEVEL_INFO;
				break;
			case "NOTICE":
				$this->_logLevel=self::_LOG_LEVEL_NOTICE;
				break;
			case "WARNING":
				$this->_logLevel=self::_LOG_LEVEL_WARNING;
				break;
			case "ERROR":
				$this->_logLevel=self::_LOG_LEVEL_ERROR;
				break;
			case "FATAL":
				$this->_logLevel=self::_LOG_LEVEL_FATAL;
				break;
			case "NONE":
				$this->_logLevel=self::_LOG_LEVEL_NONE;
				break;
			case "DEBUG":
				$this->_logLevel=self::_LOG_LEVEL_DEBUG;
				break;
			default:
				$this->_logLevel=self::_LOG_LEVEL_ALL;
				break;				
		}
		$this->_enableColorLog=(strtoupper($confSection["enableColorLog"])=="TRUE");
		if(isset($confSection["logSiteId"])){
			$this->_log_site_id=$confSection["logSiteId"];
		} 
		$logPersistImplmenter=$confSection["appender"]["persist"];
		$className=LunaLoader::import($logPersistImplmenter);
		$this->_lunaLoggerPersist=new $className;
		$this->_lunaLoggerPersist->init($confSection["appender"]["param"]);
	}

	public function getSiteId()
	{
		return $this->_log_site_id;
	}
	
	private static $_instance = null;	
	public static function getInstance()
	{
		if (!isset(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}	
}

?>