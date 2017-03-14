<?php

LunaLoader::import("luna_lib.log.LunaLogger");
LunaLoader::import("luna_lib.util.LunaUtil");

class Curl 
{
	private $_error_no		=0;
	private $_error_msg		="";
	private $_curl_session;
	
	private function initCurl($opt=array(),$curlOptions=array())
	{
		$this->_curl_session=curl_init();
		$this->_initOpt($opt);
		$this->_initOpt($curlOptions);
	}
	private function _initOpt($opt=array())
	{
		foreach ($opt as $key => $val){
			switch($key){
				case "header":
				case "useragent":
				case "referer":
				case "cookie":
					if(!empty($val)){
						curl_setopt($this->_curl_session,$this->getOptIdByHeadName($key),$val);
					}
					break;
				case "cookiejar":
					if(file_exists($val)){
						curl_setopt($this->_curl_session, CURLOPT_COOKIEJAR, $val);
						curl_setopt($this->_curl_session, CURLOPT_COOKIEFILE, $val);
					}
					break;
				case "proxy":
					if(!empty($val)){
						curl_setopt($this->_curl_session, CURLOPT_HTTPPROXYTUNNEL, true);
						curl_setopt($this->_curl_session, CURLOPT_PROXY, $val);
						curl_setopt($this->_curl_session, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
					}
					break;
				default:
					curl_setopt($this->_curl_session, $key, $val);
					break;
			}
		}		
	}
	private function getOptIdByHeadName($headName)
	{
		$optIds=array("header"=>CURLOPT_HTTPHEADER,"useragent"=>CURLOPT_USERAGENT, "referer"=>CURLOPT_REFERER,"cookie" =>CURLOPT_REFERER,);
		if(isset($optIds[$headName])){
			return $optIds[$headName];
		}
	}
	private function buildQueryString($url,$data_fields = array(),$isBatchModel=false)
	{
		if($isBatchModel){
			$join_char = strpos($url, '?') === false ? '?' : '&';
			//处理同名参数
			$tmpUrl = array();
			$i = 0;
			foreach ($data_fields as $key => $value) {
				if (true === is_array($value)) {
					sort($value, SORT_STRING);
					foreach ($value as $v) {
						$tmpUrl[$i] .= '&' . $key . '=' . $v ;
					}
					$tmpUrl[$i] = substr($tmpUrl[$i], 1);
				} else {
					$tmpUrl[$i] = $key . '=' . $value;
				}
				$i++;
			}
			$url .= $join_char . join('&', $tmpUrl);
			return $url;	
		}else{
			$join_char = strpos($url, '?') === false ? '?' : '&';
			$url .= $join_char . http_build_query($data_fields);
			return 	$url;			
		}
	}
	
	private function _array2Xml($data)
	{
		$xml = "<xml>";
		foreach ($data as $key=>$val){
			if (is_numeric($val)){
				$xml.="<".$key.">".$val."</".$key.">";
			} else{
				$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
			}
		}
		$xml.="</xml>";
		return $xml;
	}
	
	private function _http($url,$method = 'GET', $data_fields = array(), $option = array(),$opt=array(),$isBatchModel=false,$postBodyAsJson=false,$postAsQueryString=true,$jsonEncodeEx=false,$postBodyAsXml=false)
	{
		$this->initCurl($opt,$option);
		if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')){
			curl_setopt($this->_curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		}
		switch ($method) {
			case 'POST':
				curl_setopt($this->_curl_session, CURLOPT_POST, true);
				if($postBodyAsJson){
					if($jsonEncodeEx){
						$bodyStr=LunaUtil::json_encodeEx($data_fields);
					}else{
						$bodyStr=json_encode($data_fields);
					}
					curl_setopt($this->_curl_session,CURLOPT_HTTPHEADER,array("Content-Type: application/json; charset=utf-8",'Content-Length: ' . strlen($bodyStr)));
					curl_setopt($this->_curl_session, CURLOPT_POSTFIELDS, $bodyStr);
				}else if($postBodyAsXml){
					if (!empty($data_fields)) {
						curl_setopt($this->_curl_session, CURLOPT_POSTFIELDS,$this->_array2Xml($data_fields));
					}					
				}else{
					if (!empty($data_fields)) {
						if($postAsQueryString){
							curl_setopt($this->_curl_session, CURLOPT_POSTFIELDS,http_build_query($data_fields));
						}else{
							curl_setopt($this->_curl_session, CURLOPT_POSTFIELDS, $data_fields);
						}
					}
				}
				break;
			case 'GET':
			default:
				$url=$this->buildQueryString($url,$data_fields,$isBatchModel);
				break;
		}
		curl_setopt($this->_curl_session, CURLOPT_URL, $url);
		if (parse_url($url, PHP_URL_SCHEME) == 'https') {
			curl_setopt($this->_curl_session, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($this->_curl_session, CURLOPT_SSL_VERIFYHOST, 1);
		}
		$_time_start = microtime(true);
		$response = curl_exec($this->_curl_session);
		$http_info = curl_getinfo($this->_curl_session);
		$this->_error_no=curl_errno($this->_curl_session);
		if($this->_error_no){
			$this->_error_msg=curl_error($this->_curl_session);
			if ($method == 'POST') {
				$action = "POST $url with " . json_encode($data_fields);
			} else {
				$action = "GET $url";
			}
			$log_attrs = array('total_time', 'namelookup_time', 'connect_time', 'pretransfer_time', 'starttransfer_time');
			$log_info = array();
			foreach ($log_attrs as $key) {
				$log_info[$key] = $http_info[$key];
			}
			$logMsg=sprintf("%s (error_no=%s error_msg=%s)\t%s\thttpinfo(%s)",$action,$this->_error_no,$this->_error_msg,microtime(true)-$_time_start,json_encode($log_info));
			LunaLogger::getInstance()->error($logMsg);
			unset($this->_curl_session);
			return false;
		}
		$http_code = $http_info['http_code'];
		if ($http_code != 200) {
			$logMsg=sprintf("%s error:http_code=%s\t%s",$url,$http_code,microtime(true)-$_time_start,json_encode($data_fields));
			LunaLogger::getInstance()->error($logMsg);
		}
		curl_close($this->_curl_session);
		unset($this->_curl_session);
		return $response;
	}

	public function http($url,$method = 'GET', $data_fields = array(), $option = array(),$postBodyAsJson=false,$postAsQueryString=true,$jsonEncodeEx=false,$postBodyAsXml=false)
	{
		$opt=array( CURLOPT_HTTP_VERSION =>CURL_HTTP_VERSION_1_1,CURLOPT_ENCODING=>'gzip, deflate',
				CURLOPT_RETURNTRANSFER=>true,CURLOPT_CONNECTTIMEOUT=>5,CURLOPT_TIMEOUT=>5,CURLOPT_FOLLOWLOCATION=>true,
				CURLOPT_MAXREDIRS=>5,CURLOPT_DNS_USE_GLOBAL_CACHE=>false,CURLOPT_DNS_CACHE_TIMEOUT=>2,);
		return $this->_http($url, $method, $data_fields,$option,$opt,false,$postBodyAsJson,$postAsQueryString,$jsonEncodeEx,$postBodyAsXml);
	}
	
	public function httpBatch($url, $method = 'GET', $data_fields = array(), $option = array(),$jsonEncodeEx=false,$postBodyAsXml=false) 
	{
		$opt=array( CURLOPT_HTTP_VERSION =>CURL_HTTP_VERSION_1_1,CURLOPT_ENCODING=>'gzip, deflate',
				CURLOPT_RETURNTRANSFER=>true,CURLOPT_CONNECTTIMEOUT=>5,CURLOPT_TIMEOUT=>5,CURLOPT_FOLLOWLOCATION=>true,
				CURLOPT_MAXREDIRS=>5,);		
		return $this->_http($url, $method, $data_fields,$option,$opt,true);
	}
}

