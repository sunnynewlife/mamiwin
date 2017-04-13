<?php
LunaLoader::import("luna_lib.http.HttpInterface");
LunaLoader::import("luna_lib.util.CGuidManager");
LunaLoader::import("luna_lib.util.LunaMemcache");

class WxHelper
{
	const WX_APP_ID="wxb3def19097c93c37";
	const WX_SECRET="9af53f46a404fa91b5678c4716248dca";
	
	public static function getOpenId($code)
	{
		if(empty($code)==false){
			$http=new HttpInterface("Tencent","PageAccessToken");
			$params=array(
					"code"			=>	$code,
					"appid"			=>	WxHelper::WX_APP_ID,
					"secret"		=>	WxHelper::WX_SECRET,
					"grant_type"	=>	"authorization_code",
			);
			$data=$http->submit($params);
			if($data && is_array($data) && isset($data["openid"]) && empty($data["openid"])==false){
				//保存访问用户的信息的 AccessToken
				$cacheCfgNodeName="TencentWx";
				$cacheKey=$data["openid"]."_AccessToken";
				$expire=$data["expires_in"]-60;
				LunaMemcache::GetInstance($cacheCfgNodeName)->write($cacheKey,$data["access_token"],$expire);
				
				//保存刷新用户信息的 AccessToken的 RefreshToken
				$cacheKey=$data["openid"]."_RefreshToken";
				$expire=$data["expires_in"]+72000;
				LunaMemcache::GetInstance($cacheCfgNodeName)->write($cacheKey,$data["refresh_token"],$expire);
				return $data;
			}
		}
		return false;
	}
	
	public static function getOpenIdUserInfo($openId)
	{
		if(empty($openId)==false){
			$http=new HttpInterface("Tencent","GetUserInfo");
			$params=array(
					"access_token"		=>	self::getOpenIdAccessToken($openId),
					"openid"			=>	$openId,
					"lang"				=>	"zh_CN",
			);
			$data=$http->submit($params);
			if($data && is_array($data) && isset($data["openid"]) && empty($data["openid"])==false){
				return $data;
			}
		}
		return false;
	}
	
	public static function getOpenIdAccessToken($openId)
	{
		$cacheCfgNodeName="TencentWx";
		$cacheKey=$openId."_AccessToken";
		$cachedValue =LunaMemcache::GetInstance($cacheCfgNodeName)->read($cacheKey);
		
		if(isset($cachedValue) &&  $cachedValue!=false && empty($cachedValue)==false ){
			return $cachedValue;
		}else{
			$cacheKey=$openId."_RefreshToken";
			$refreshToken =LunaMemcache::GetInstance($cacheCfgNodeName)->read($cacheKey);
			if(isset($refreshToken) &&  $refreshToken!=false && empty($refreshToken)==false ){
				$http=new HttpInterface("Tencent","PageRefreshAccessToken");
				$params=array(
						"grant_type"		=>	"refresh_token",
						"appid"				=>	WxHelper::WX_APP_ID,
						"refresh_token"		=>	$refreshToken,
				);
				$data=$http->submit($params);
				if(isset($data) && is_array($data) && isset($data["access_token"]) && empty($data["access_token"])==false){
					$cachedValue=$data["access_token"];
					
					//保存访问用户的信息的 AccessToken
					$cacheCfgNodeName="TencentWx";
					$cacheKey=$openId."_AccessToken";
					$expire=$data["expires_in"]-60;
					LunaMemcache::GetInstance($cacheCfgNodeName)->write($cacheKey,$data["access_token"],$expire);
					
					//保存刷新用户信息的 AccessToken的 RefreshToken
					$cacheKey=$openId."_RefreshToken";
					$expire=$data["expires_in"]+72000;
					LunaMemcache::GetInstance($cacheCfgNodeName)->write($cacheKey,$data["refresh_token"],$expire);
				}
				return $cachedValue;
			}
			return "access token out of date";
		}
	}
	
	public static function getJSSDKData($url)
	{
		//check 地址是否以 #结束
		$lastStr=substr($url,-1);
		if($lastStr=="#"){
			$url=substr($url,0,-1);
		}
		$data=array(
				"appId"			=>	WxHelper::WX_APP_ID,
				"timestamp"		=>	time(),
				"nonceStr"		=>	CGuidManager::GetGuid(),
		);
		$params=array(
				"jsapi_ticket"	=>	self::getJsApiTicket(),
				"noncestr"		=>	$data["nonceStr"],
				"timestamp"		=>	$data["timestamp"],
				"url"			=>	$url,
		);
		$buff="";
		foreach ($params as $k => $v){
			$buff .= $k . "=" . $v . "&";
		}
		$buff=substr($buff, 0, strlen($buff)-1);
		$data["signature"]=sha1($buff);
		return $data;
	}	
	
	public static function getJsApiTicket()
	{
		$cacheCfgNodeName="TencentWx";
		$cacheKey="WX.ACCESS.JSSDK_TICKET";
		$expire=7100;
	
		$cachedValue =LunaMemcache::GetInstance($cacheCfgNodeName)->read($cacheKey);
		if(isset($cachedValue) &&  $cachedValue!=false && empty($cachedValue)==false){
			return $cachedValue;
		}else{
			$http=new HttpInterface("Tencent","JSApiTicket");
			$params=array(
					"type"			=>	"jsapi",
					"access_token"	=>	self::getAccessToken(),
			);
			$data=$http->submit($params);
			if(isset($data) && is_array($data) && isset($data["errcode"]) && $data["errcode"]=="0" && isset($data["ticket"]) && empty($data["ticket"])==false){
				$cachedValue=$data["ticket"];
				LunaMemcache::GetInstance($cacheCfgNodeName)->write($cacheKey,$cachedValue,$expire);
			}else{
				//重新获取 access token
				if(isset($data) && is_array($data) && isset($data["errcode"]) && ($data["errcode"]=="40001" || $data["errcode"]=="40002" || $data["errcode"]=="42001")){
					$params[access_token] = self::getAccessToken(false);
					$data=$http->submit($params);
					if(isset($data) && is_array($data) && isset($data["errcode"]) && $data["errcode"]=="0" && isset($data["ticket"]) && empty($data["ticket"])==false){
						$cachedValue=$data["ticket"];
						LunaMemcache::GetInstance($cacheCfgNodeName)->write($cacheKey,$cachedValue,$expire);
					}
				}
			}
			return $cachedValue;
		}
	}	
	
	
	public static function getAccessToken($bUsingCache=true)
	{
		$cacheCfgNodeName="TencentWx";
		$cacheKey="WX.ACCESS.TOKEN";
		$expire=7100;
		
		$cachedValue =LunaMemcache::GetInstance($cacheCfgNodeName)->read($cacheKey);
		if(isset($cachedValue) &&  $cachedValue!=false && empty($cachedValue)==false && $bUsingCache){
			return $cachedValue;
		}else{
			$http=new HttpInterface("Tencent","AccessToken");
			$params=array(
					"grant_type"		=>	"client_credential",
					"appid"				=>	WxHelper::WX_APP_ID,
					"secret"			=>	WxHelper::WX_SECRET,
			);
			$data=$http->submit($params);
			if(isset($data) && is_array($data) && isset($data["access_token"]) && empty($data["access_token"])==false){
				$cachedValue=$data["access_token"];
				LunaMemcache::GetInstance($cacheCfgNodeName)->write($cacheKey,$cachedValue,$expire);
			}
			return $cachedValue;
		}
	} 
	
	public static function sendWxPlayerNotifyMsg($openId,$msgTemplateId,$msgParams,$topColor="#FFF000")
	{
		$http=new HttpInterface("Tencent","NotifyMsg");
		$appendUrl=sprintf("?access_token=%s",WxHelper::getAccessToken());
		$params=array(
				"touser"		=>	$openId,
				"template_id"	=>	$msgTemplateId,
				"topcolor"		=>	$topColor,
				"data"			=>	$msgParams,
		);
		LunaLogger::getInstance()->info(print_r($params,true));
		$data=$http->submit($params,true,array(),false,$appendUrl,true);
		if(isset($data) && is_array($data) && isset($data["errcode"]) && ($data["errcode"]=="40001" || $data["errcode"]=="40002" || $data["errcode"]=="42001")){
			$appendUrl=sprintf("?access_token=%s",WxHelper::getAccessToken(false));
			$data=$http->submit($params,true,array(),false,$appendUrl,true);
		}
	}
}
