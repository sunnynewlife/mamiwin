<?php
LunaLoader::import("luna_lib.log.ILunaLoggerPersist");
LunaLoader::import("luna_lib.log.LunaLogger");
LunaLoader::import("luna_lib.util.LunaWebUtil");

class TxtLogerPersist implements ILunaLoggerPersist 
{
	function logMessage($message)
	{
		$this->_logRecords[]=$message;		
		$this->serializeLog();
	}
	function init($configs)
	{
		$this->_logDir=$configs["dir"];
	}
	private $_logDir;
		
	private $_logRecords=array();

	public function serializeLog()
	{
		$logMessages="";
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
		$filename = $dirMonth . DIRECTORY_SEPARATOR . date('Y.m.d').'.log';
	
		$msgEncoding=mb_detect_encoding($logMessages);
		$convertMsg=@mb_convert_encoding($logMessages,"UTF-8",$msgEncoding);
		if($convertMsg===false || empty($convertMsg))
		{
			$convertMsg=$logMessages;
		}
		file_put_contents ( $filename, $convertMsg, FILE_APPEND|FILE_TEXT|LOCK_EX);
		$this->_logRecords=array();
	}
	
}

?>