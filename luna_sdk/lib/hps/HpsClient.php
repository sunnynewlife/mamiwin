<?php
LunaLoader::import("luna_core.LunaConfigMagt");
LunaLoader::import("luna_lib.util.LunaWebUtil");
LunaLoader::import("luna_lib.util.ThreadId");
LunaLoader::import("luna_lib.util.Curl");
LunaLoader::import("luna_lib.util.LunaUtil");
LunaLoader::import("luna_lib.log.LunaLogger");

class HpsClient 
{
	private $_hpsDomain;
	private $_appid;
	private $_areaid=-1;
	private $_user;
	private $_signPwd;

	private $_modelClassName;
	private $_configs;

	private $_error_no;
	private $_error_msg;
	
	public function __construct($callerClassName="")
	{
		$this->_modelClassName=$callerClassName;
		$this->_configs=LunaConfigMagt::getInstance()->getLunaSDKConfigSection("HpsClient");
		
		$globalCfg=$this->_configs["global"];
		
		$this->_hpsDomain=$globalCfg["domain"];
		$this->_user=$globalCfg["user"];
		$this->_signPwd=$globalCfg["sign_pwd"];
		
		if(isset($globalCfg["appid"])){
			$this->_appid=$globalCfg["appid"];
		}
		if(isset($globalCfg["areaid"])){
			$this->_areaid=$globalCfg["areaid"];
		}
		if(isset($this->_configs["special"]) && empty($this->_modelClassName)==false){
			$specialCfg=$this->_configs["special"];
			foreach ($specialCfg as $clsName => $clsCfg){
				if($clsName==$this->_modelClassName){
					$this->_overrideCfg($clsCfg);
					break;
				}	
			}			
		}
		
	}
	private function _overrideCfg($cfg)
	{
		if(isset($cfg["domain"])){
			$this->_hpsDomain=$cfg["domain"];
		}
		if(isset($cfg["user"])){
			$this->_user=$cfg["user"];
		}
		if(isset($cfg["sign_pwd"])){
			$this->_signPwd=$cfg["sign_pwd"];
		}
		if(isset($cfg["appid"])){
			$this->_appid=$cfg["appid"];
		}
		if(isset($cfg["areaid"])){
			$this->_areaid=$cfg["areaid"];
		}		
	}
	
	private function getSafeLogUrl($log_url) 
	{
		$log_url_safe = $log_url;
		$log_url_safe = preg_replace('/([&|\?])pwd=(.{1}[^&])(.[^&]*)/', '$1pwd=$2****$4', $log_url_safe);
		$log_url_safe = preg_replace('/([&|\?])password=(.{1}[^&])(.[^&]*)/', '$1password=$2****$4', $log_url_safe);
		return $log_url_safe;
	}
	private function getSafeLogResp($resp) 
	{
		$resp_safe = $resp;
		$resp_safe = preg_replace('/(\")ServiceNo(\")(.*[:|\s])(\")(.{2}[^\"\'])(.*[^\"\'])(\")/', '"ServiceNo" : "$5****"', $resp_safe);
		return $resp_safe;
	}		
	private function calculateSignature($params) 
	{
		ksort($params);
		$arr_sign = array();
		foreach ($params as $key=>$val) {
			$arr_sign[] = "$key=$val";
		}
		$str_sign = implode('', $arr_sign);
		$str_sign .= $this->_signPwd;
	
		return md5($str_sign);
	}
	private function calculateSignatureBatch($params) 
	{
		ksort($params);
		$arr_sign = array();
		$i = 0;
		foreach ($params as $key=>$val) {
			if (true === is_array($val)) {
				sort($val, SORT_STRING);
				foreach ($val as $v) {
					$arr_sign[$i] .= "$key=$v";
				}
			} else {
				$arr_sign[$i] = "$key=$val";
			}
			$i++;
		}
		$str_sign = implode('', $arr_sign);
		$str_sign .= $this->_signPwd;
	
		return md5($str_sign);
	}
		
	public function getErrorNo()
	{
		return $this->_error_no;
	}
	public function getErrorMsg()
	{
		$msgMap=array("-1" => "hps parameter errors","-2" => "hps connect error");
		if(isset($msgMap[$this->_error_no])){
			return $msgMap[$this->_error_no];
		}
	}
	public function getData($path, $params, $maskSafe = 0, $charset ='utf-8')
	{
		return $this->_getData($path, $params,$maskSafe,$charset,false,"GET");
	}
	public function getDataBatch($path, $params) 
	{
		return $this->_getData($path, $params,0,"utf-8",true,"GET");
	}
	public function postData($path, $params, $maskSafe = 0,$charset ='utf-8') 
	{
		return $this->_getData($path, $params,$maskSafe,$charset,false,"POST");
	}
	
	private  function _getData($path, $params, $maskSafe = 0, $charset ='utf-8',$isMatchModel=false,$method='GET') 
	{
		if (!$path || substr($path, 0, 1) != '/' || !$params || !is_array($params)) {
			$this->_error_no = -1;
			return false;
		}
		
		$singParam = array(
				'appId' => $this->_appid,
				'merchant_name' => $this->_user,
				'signature_method' => 'MD5',
				'timestamp' => time(),
		);		
		$params = array_merge($singParam,$params);
		if($isMatchModel){
			$sign_code = $this->calculateSignatureBatch($params);
		}else{
			$sign_code = $this->calculateSignature($params);
		}
		$params['signature'] = $sign_code;
	
		$url = $this->_hpsDomain . $path;
	
		$_time_start = microtime(true);
		$curl=new Curl();
		if($isMatchModel){
			$resp =$curl->httpBatch($url, $method,$params);
		}
		else{
			$resp =$curl->http($url, $method, $params);
		}		
		if (!$resp) {
			$this->_error_no = -2;
			return false;
		}
		
		//增加的支持非utf-8字符的接口返回值
		if($charset != 'utf-8'){
			$resp = @mb_convert_encoding($resp, 'utf-8', $charset);
		}
		$join_char = strpos($url, '?') === false ? '?' : '&';
		$log_url = $url . $join_char . http_build_query($params);
		$log_resp=$resp;
		if($maskSafe == 1) {
			$log_url = $this->getSafeLogUrl($log_url); //获取安全的日志字符串
			$log_resp = $this->getSafeLogResp($log_resp); //获取安全的日志字符串
		}
		$remote_ip = LunaWebUtil::getClientIp();
		$consumer_time = microtime(true) - $_time_start; //接口耗时
		$ts_level = LunaUtil::getTsLevel($consumer_time); //接口耗时等级
		$logMsg=sprintf("<hps>\t<%s>\t<%s>\t<%s>\t<%s>\t<%s>",$consumer_time,$ts_level,$log_url,$remote_ip,$log_resp);
		LunaLogger::getInstance()->info($logMsg);			
		return $resp;
	}	
}

?>