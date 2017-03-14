<?php
LunaLoader::import("luna_lib.http.HttpInterface");
LunaLoader::import("luna_lib.util.CGuidManager");
LunaLoader::import("luna_lib.util.LunaMemcache");

class WxHelper
{
	const WX_APP_ID="wx8a417e8a8315d161";
	const WX_SECRET="c458469a3a7ba712d2d8408dc488a45a";
	
	
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
