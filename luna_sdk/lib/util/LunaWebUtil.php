<?php

class LunaWebUtil 
{
	public static function getClientIp()
	{
		$clientIp="unknown-ip";
		$xForwardHeadValue="";
		if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$xForwardHeadValue=$_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		if(isset($xForwardHeadValue) && empty($xForwardHeadValue)==false){
			$ips=explode(",",$xForwardHeadValue);
			if(count($ips)>0){
				$clientIp=$ips[0];
			}
		}
		if(empty($clientIp) || $clientIp=="unknown-ip"){
			if(isset($_SERVER['REMOTE_ADDR'])){
				$remoteAddr=$_SERVER['REMOTE_ADDR'];
				if(isset($remoteAddr) && empty($remoteAddr)==false){
					$clientIp=$remoteAddr;
				}
			}
		}
		return $clientIp;
	}	
	
	public static function getServerIp()
	{
		if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])){
			return $_SERVER['REMOTE_ADDR'];
		}
		if(isset($_SERVER['LOCAL_ADDR']) && !empty($_SERVER['LOCAL_ADDR'])){
			return $_SERVER['LOCAL_ADDR'];
		}
		if (isset($_ENV) && isset($_ENV["HOSTNAME"]) && !empty($_ENV["HOSTNAME"])){
			return gethostbyname($_ENV["HOSTNAME"]);
		}
		if (isset($_ENV) && isset($_ENV["COMPUTERNAME"]) && !empty($_ENV["COMPUTERNAME"])){
			return gethostbyname($_ENV["COMPUTERNAME"]);
		}
		return false;
	}	
	
	public static function getServerName()
	{ 
		if(isset($_SERVER['SERVER_NAME']) && !empty($_SERVER['SERVER_NAME'])){
			return $_SERVER['SERVER_NAME'];
		}		
		return false;
	}
	
}

