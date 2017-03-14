<?php
LunaLoader::import("luna_core.LunaConfigMagt");
LunaLoader::import("luna_lib.util.Curl");
LunaLoader::import("luna_lib.log.LunaLogger");

class HttpInterface 
{
	public $_config;
	
	private $_external_system_name;
	private $_interface_name;

	public  $_domain;
	private $_needMd5Sign;
	private $_signPwd;
	private $_signPosition;
	private $_url;
	private $_responseType;
	private $_method="GET";
	private $_signParaName;
	private $_signParamJoinChar="";
	private $_signSplitChar="";
	private $_signAlgorithm="md5";
	
	protected $_excludeParaName=false;
	
	protected $_logBeforeMd5String=false;
	
	protected $_fixParams=array();
	
	public function __construct($ExtralSystemName,$InterfaceName)
	{
		$this->_external_system_name=$ExtralSystemName;
		$this->_interface_name=$InterfaceName;
		$this->_config=LunaConfigMagt::getInstance()->getLunaSDKConfigSection("HttpInterface");

		if(isset($this->_config) && is_array($this->_config)){
			
			foreach ($this->_config as $SystemName => $SystemConfig){
				if($SystemName==$ExtralSystemName){
					$this->_domain=$SystemConfig["domain"];
					$this->_needMd5Sign=(strtoupper($SystemConfig["needMd5Sign"])=="TRUE");
					$this->_signPwd=$SystemConfig["signPwd"];
					$this->_signPosition=strtoupper($SystemConfig["signPosition"]);
					$this->_responseType=$SystemConfig["responseType"];
					$this->_method=strtoupper($SystemConfig["method"]);
					$this->_signParaName=$SystemConfig["signParaName"];
					$this->_signParamJoinChar=$SystemConfig["signParamJoinChar"];
					if(isset($SystemConfig["signSplitChar"])){
						$this->_signSplitChar=$SystemConfig["signSplitChar"];
					}
					if(isset($SystemConfig["excludeParaName"])){
						$this->_excludeParaName=("true"==$SystemConfig["excludeParaName"]);
					}
					if(isset($SystemConfig["logBeforeMd5String"])){
						$this->_logBeforeMd5String=("true"==$SystemConfig["logBeforeMd5String"]);
					}
					if(isset($SystemConfig["signAlgorithm"])){
						$this->_signAlgorithm=$SystemConfig["signAlgorithm"];
					}
					
					foreach ($SystemConfig["interface"] as $interfaceId => $interfaceConfig){
						if($interfaceId==$InterfaceName){
							$this->_url=$interfaceConfig["url"];
							if(isset($interfaceConfig["domain"])){
								$this->_domain=$interfaceConfig["domain"];
							}
							if(isset($interfaceConfig["needMd5Sign"])){
								$this->_needMd5Sign=(strtoupper($interfaceConfig["needMd5Sign"])=="TRUE");
							}
							if(isset($interfaceConfig["signPwd"])){
								$this->_signPwd=$interfaceConfig["signPwd"];
							}
							if(isset($interfaceConfig["signPosition"])){
								$this->_signPosition=strtoupper($interfaceConfig["signPosition"]);
							}
							if(isset($interfaceConfig["responseType"])){
								$this->_responseType=$interfaceConfig["responseType"];
							}		
							if(isset($interfaceConfig["method"])){
								$this->_method=strtoupper($interfaceConfig["method"]);
							}			
							if(isset($interfaceConfig["signParaName"])){
								$this->_signParaName=$interfaceConfig["signParaName"];
							}			
							if(isset($interfaceConfig["signParamJoinChar"])){
								$this->_signParamJoinChar=$interfaceConfig["signParamJoinChar"];
							}			
							if(isset($interfaceConfig["signSplitChar"])){
								$this->_signSplitChar=$interfaceConfig["signSplitChar"];
							}			
							if(isset($interfaceConfig["excludeParaName"])){
								$this->_excludeParaName=("true"==$interfaceConfig["excludeParaName"]);
							}
							if(isset($interfaceConfig["parameter"]) && is_array($interfaceConfig["parameter"])){
								foreach ($interfaceConfig["parameter"] as $parameterKey =>$parameterValue){
									$this->_fixParams[$parameterKey]=$parameterValue;
								}
							}
							if(isset($interfaceConfig["signAlgorithm"])){
								$this->_signAlgorithm=$interfaceConfig["signAlgorithm"];
							}
							break;
						}
					}
					break;
				}				
			}			
		}
	}
	
	//$postBodyAsJson  $postBodyAsXml 互斥 
	public function submit($params=array(),$postBodyAsJson=false,$options=array(),$postAsQueryString=true,$appendUrl="",$jsonEncodeEx=false,$postBodyAsXml=false)
	{
		$params = array_merge($this->_fixParams,$params);
		if($this->_needMd5Sign){
			$sign_code = $this->calculateSignature($params);
			$params[$this->_signParaName] = $sign_code;
		}
		
		$requestUrl = ($this->_domain."/".$this->_url);
		
		//post 模式有些query 自定作为地址一部分
		if(empty($appendUrl)==false){
			$requestUrl.=$appendUrl;
		}
		
		$_time_start = microtime(true);
		$curl=new Curl();
		$resp =$curl->http($requestUrl, $this->_method, $params,$options,$postBodyAsJson,$postAsQueryString,$jsonEncodeEx,$postBodyAsXml);

		if (!$resp) {
			return false;
		}
		
		$join_char = strpos($requestUrl, '?') === false ? '?' : '&';
		$log_url = $requestUrl . $join_char . http_build_query($params);
		$log_resp=$resp;
		$remote_ip = LunaWebUtil::getClientIp();
		$logMsg=sprintf("<HttpInterface>\t<%s>\t<%s>\t%s\t%s\t%s\t%s",$this->_external_system_name,$this->_interface_name,$log_url,microtime(true) - $_time_start,$remote_ip,$log_resp);
		LunaLogger::getInstance()->info($logMsg);
		if($this->_responseType=="json"){
			return json_decode($resp,true);
		}
		return $resp;		
	}
	
	//增加 upload 方式支持
	public function upload($fileName,$fileAsAttachment=false,$params=array(),$options=array())
	{
		$params = array_merge($this->_fixParams,$params);
		if($this->_needMd5Sign){
			$sign_code = $this->calculateSignature($params);
			$params[$this->_signParaName] = $sign_code;
		}
		
		$requestUrl = ($this->_domain."/".$this->_url);
		$appendQueryStr = http_build_query($params);
		if(empty($appendQueryStr)==false){
			$requestUrl.="?".$appendQueryStr;
		}

		if($fileAsAttachment){
			$dataFields =array("f" => "@".$fileName);
		}else{
			$dataFields = file_get_contents($fileName);
		}
		
		$_time_start = microtime(true);
		$curl=new Curl();
		$resp =$curl->http($requestUrl, $this->_method,$dataFields,$options,false,false,false,false);
		if (!$resp) {
			return false;
		}
		
		$log_url = $requestUrl ;
		$log_resp=$resp;
		$remote_ip = LunaWebUtil::getClientIp();
		$logMsg=sprintf("<HttpInterface>\t<%s>\t<%s>\t%s\t%s\t%s\t%s",$this->_external_system_name,$this->_interface_name,$log_url,microtime(true) - $_time_start,$remote_ip,$log_resp);
		LunaLogger::getInstance()->info($logMsg);
		if($this->_responseType=="json"){
			return json_decode($resp,true);
		}
		return $resp;		
	}
	
	
	private function calculateSignature($params)
	{
		if($this->_signAlgorithm=="hmac-sha1"){
			return $this->calculateSignatureHmacSha1($params);	
		}
		return $this->calculateSignatureMd5($params);
	}
	
	private function calculateSignatureHmacSha1($params)
	{
		ksort($params);
		$arr_sign = array();
		foreach ($params as $key=>$val) {
			$arr_sign[] = sprintf("%s%s%s",($this->_excludeParaName?"":$key),$this->_signParamJoinChar,$val);
		}
		$str_sign = implode($this->_signSplitChar, $arr_sign);
		if($this->_logBeforeMd5String){
			LunaLogger::getInstance()->info("Before HMAC-SHA1 String:".$str_sign);
		}
		return bin2hex(hash_hmac('sha1', $str_sign, $this->_signPwd, TRUE));		
	}
	
	private function calculateSignatureMd5($params)
	{
		ksort($params);
		$arr_sign = array();
		foreach ($params as $key=>$val) {
			$arr_sign[] = sprintf("%s%s%s",($this->_excludeParaName?"":$key),$this->_signParamJoinChar,$val);
		}
		$str_sign = implode($this->_signSplitChar, $arr_sign);
		if($this->_signPosition=="HEAD"){
			$str_sign =$this->_signPwd.$str_sign;
		}else if($this->_signPosition=="BOTTOM"){
			$str_sign .= $this->_signPwd;
		}
		if($this->_logBeforeMd5String){
			LunaLogger::getInstance()->info("Before Md5 String:".$str_sign);
		}
		return md5($str_sign);		
	}

}

?>