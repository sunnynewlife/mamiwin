<?php

LunaLoader::import("luna_lib.util.IReportMsgFormat");
LunaLoader::import("luna_lib.util.IReportPersist");
LunaLoader::import("luna_core.LunaConfigMagt");

class LunaReport 
{
	const _REPORT_CONFIG_SECTION	="DataReport";
	
	private static $_instance=null;
	public static function GetInstance()
	{
		if(!isset(self::$_instance)){
			self::$_instance=new self();
		}
		return self::$_instance;
	}
	
	private function __construct()
	{
		$this->_config=LunaConfigMagt::getInstance()->getLunaSDKConfigSection(self::_REPORT_CONFIG_SECTION);
		$formatConfig=$this->_config["Formatter"];
		$msgFormatImplmenter=$formatConfig["implementer"];
		$formatClassName=LunaLoader::import($msgFormatImplmenter);
		$this->_reportMsgFormat=new $formatClassName;
		$this->_reportMsgFormat->init($formatConfig["param"]);
		
		$persistConfig=$this->_config["Persister"];
		$msgPersistImplmenter=$persistConfig["implementer"];
		$persistClassName=LunaLoader::import($msgPersistImplmenter);
		$this->_reportPersist=new $persistClassName;
		$this->_reportPersist->init($persistConfig["param"]);
	}
	
	private $_config;
	private $_reportMsgFormat;
	private $_reportPersist; 
	
	public function sendReportData($reportData=array())
	{
		$reportMsg=$this->_reportMsgFormat->format($reportData);
		$this->_reportPersist->save($reportMsg);
	}
	
}

?>