<?php
LunaLoader::import("luna_lib.http.HttpInterface");
LunaLoader::import("luna_lib.util.CGuidManager");
LunaLoader::import("luna_lib.util.LunaMemcache");
LunaLoader::import("luna_lib.log.LunaLogger");

class WbHelper
{
	const WB_APP_ID="1465627348";
	const WB_SECRET="bdd2e3f1786375282f71c96b1f24a158";
	
	public static function getOpenId($code)
	{
		if(empty($code)==false){
			$http=new HttpInterface("WeiBo","AccessToken");
			$params=array(
					"code"				=>	$code,
					"client_id"			=>	WbHelper::WB_APP_ID,
					"client_secret"		=>	WbHelper::WB_SECRET,
					"grant_type"		=>	"authorization_code",
					"redirect_uri"		=>	"http://api.fumuwin.com/site/wbLogin",
			);
			$data=$http->submit($params);
			if($data && is_array($data) && isset($data["uid"]) && empty($data["uid"])==false){
				//保存访问用户的信息的 AccessToken
				$cacheCfgNodeName="Weibo";
				$cacheKey=$data["uid"]."_Wb_AccessToken";
				$expire=$data["expires_in"]-60;
				LunaLogger::getInstance()->info($cacheKey);
				LunaLogger::getInstance()->info("expire=$expire");
				LunaLogger::getInstance()->info($data["access_token"]);
				
				LunaMemcache::GetInstance($cacheCfgNodeName)->write($cacheKey,$data["access_token"],86400);
				return $data;
			}
		}
		return false;
	}
	
	public static function getOpenIdUserInfo($uid)
	{
		$cacheCfgNodeName="Weibo";
		$cacheKey=$uid."_Wb_AccessToken";
		$accessToken =LunaMemcache::GetInstance($cacheCfgNodeName)->read($cacheKey);
		LunaLogger::getInstance()->info($cacheKey);
		LunaLogger::getInstance()->info($accessToken);
		
		if(isset($accessToken) &&  $accessToken!=false && empty($accessToken)==false ){
			$http=new HttpInterface("WeiBo","UserInfo");
			$params=array(
					"access_token"		=>	$accessToken,
					"uid"				=>	$uid,
			);
			$data=$http->submit($params);
			if(isset($data) && is_array($data)){
				return $data;
			}
		}
		return array();
	}
}
