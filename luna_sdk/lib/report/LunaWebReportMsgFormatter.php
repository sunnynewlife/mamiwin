<?php
LunaLoader::import("luna_lib.util.LunaWebUtil");
LunaLoader::import("luna_lib.report.IReportMsgFormat");

class LunaWebReportMsgFormatter implements IReportMsgFormat 
{
	private $_config;
	private $_type;
	private $_splitChar;
	private $_auoFillRequestInfo;
	
	public function init($config)
	{
		$this->_config=$config;
		$this->_type=strtoupper($this->_config["type"]);
		$this->_splitChar=$this->_config["splitChar"];
		$this->_auoFillRequestInfo=(strtoupper($this->_config["auoFillRequestInfo"])=="TRUE");		
	}
	public  function format($params)
	{
		$logMsgArray=$params;
		if($this->_auoFillRequestInfo){
			$requestInfo=$this->getRequestInfo();
			$logMsgArray=array_merge($requestInfo,$params);
		}
		$logMsg="";
		switch($this->_type){
			case "HTTP_QUERY":
				$logMsg=http_build_query($logMsgArray);
				break;
			case "JSON":
				$logMsg=json_encode($logMsgArray);
				break;
			case "ASSEMBLE":
				foreach ($logMsgArray as $fieldName => $fieldValue){
					$logMsg.=sprintf("%s%s", (empty($logMsg)?"":$this->_splitChar),$fieldValue);
				}
				break;			
		}
		return $logMsg;
	}

	//需要定制继承此类实现定制
	protected  function getRequestInfo()
	{
		$requestInfo=array(
				"SessionId" => $this->getSessionId(),
				"UserIp" 	=> LunaWebUtil::getClientIp(),
				"ServerIp" 	=> LunaWebUtil::getServerIp(),
				"ThisUrl" 	=> $this->getRequestUri(),
				"FromUrl" 	=> $this->getReferUri(),
		);
		return $requestInfo;
	}
	protected function getSessionId()
	{
		$sessionNameInCookie="PHPSESSID";
		if(isset($_COOKIE[$sessionNameInCookie]) && empty($_COOKIE[$sessionNameInCookie])==false){
			return $_COOKIE[$sessionNameInCookie];
		}
		return "";
	}
	protected  function getRequestUri()
	{
		if(isset($_SERVER["REQUEST_URI"])){
			return $_SERVER["REQUEST_URI"];
		}
		return "";
	}
	protected  function getReferUri()
	{
		if(isset($_SERVER["HTTP_REFERER"])){
			return $_SERVER['HTTP_REFERER'];
		}
		return "";
	}	
}

?>