<?php
LunaLoader::import("luna_lib.util.LunaPdo");

class AppDownload
{
	private static $_database_cfg_node_name="FHDatabase";
	private static $_pdo=null;
	
	private static $_instance = null;
	public static function getInstance()
	{
		if (!isset(self::$_instance)) {
			self::$_instance = new self();
		}
		self::$_pdo=LunaPdo::GetInstance(self::$_database_cfg_node_name);
		return self::$_instance;
	}
	
	public  function getAppVersionId($AppRst,$phoneNo,$appId)
	{
		//first try get Status=0 and TestPhone contain $phoneNo  verison
		foreach ($AppRst as $rst){
			if($rst["AppId"]==$appId && $rst["State"]==0 && @strpos(",".(isset($rst["TestPhone"])?$rst["TestPhone"]:""),$phoneNo)){
				return $rst["AppVersionId"];
			}
		}
		//second try get Status==1 Version
		foreach ($AppRst as $rst){
			if($rst["AppId"]==$appId && $rst["State"]==1){
				return $rst["AppVersionId"];
			}
		}
		return "";
	}
	
	private function getCurrentAppPackageInfos($PromoterId)
	{
		$sql="select * from AppPackage where PromoterId=? ";
		$params=array($PromoterId);
		$appPackageInfo=self::$_pdo->query_with_prepare($sql,$params);
		if(isset($appPackageInfo) && is_array($appPackageInfo) && count($appPackageInfo)>0){
			return $appPackageInfo;
		}
		return array();
	}
	private function getAppPackageInfosHistory($PromoterId)
	{
		$sql="select a.*,b.AppId  from AppPackage a left join AppVersion b on b.AppVersionId=a.AppVersionId 
				where a.PromoterId = ? order by b.AppId asc,a.AppPackageId asc";
		$params=array($PromoterId);
		$appPackageInfo=self::$_pdo->query_with_prepare($sql,$params);
		if(isset($appPackageInfo) && is_array($appPackageInfo) && count($appPackageInfo)>0){
			return $appPackageInfo;
		}
		return array();
	}
	
	private function getPromoterShareUrlEx($AppId,$AppVersionId,$AppPackageInfos,$AppPackageHistoryInfos)
	{
		foreach ($AppPackageInfos as $packageItem){
			if($packageItem["AppVersionId"]==$AppVersionId){
				return $packageItem["PromotionUrl"];
			}
		}
		foreach ($AppPackageHistoryInfos as $packageItem){
			if($packageItem["AppId"]==$AppId){
				return $packageItem["PromotionUrl"];
			}
		}
		return "";
	}
	
	
	public function getPromoterShareUrl($AppId,$AppVersionId,$PromoterId)
	{
		//检查 当前版本 是否有推广短链接
		$sql="select * from AppPackage where AppVersionId=? and PromoterId=? ";
		$params=array($AppVersionId,$PromoterId);
		$appPackageInfo=self::$_pdo->query_with_prepare($sql,$params);
		if(isset($appPackageInfo) && is_array($appPackageInfo) && count($appPackageInfo)>0){
			return $appPackageInfo[0]["PromotionUrl"];
		}
		//查询是否有历史版本推广短链接
		$sql="select a.*,b.AppId  from AppPackage a left join AppVersion b on b.AppVersionId=a.AppVersionId where a.PromoterId = ? and b.AppId = ? order by AppPackageId asc limit 1";
		$params=array($PromoterId,$AppId);
		$appPackageInfo=self::$_pdo->query_with_prepare($sql,$params);
		if(isset($appPackageInfo) && is_array($appPackageInfo) && count($appPackageInfo)>0){
			return $appPackageInfo[0]["PromotionUrl"];
		}
		return "";
	}
	
	public function  getGameList($userPhone,$PromoterId)
	{
		$sql="SELECT DISTINCT AppVersion.AppVersionId, App.AppId, App.AppName,App.AppType, App.AppIntroduct, ProrateHistory.PromoterProrate, File.FileUrl,
				AppVersion.State, App.AppStatus, AppVersion.TestPhone AVTP, App.TestPhone ATP FROM App
				inner join AppVersion on App.AppId = AppVersion.AppId and AppVersion.State < 2
				left join File on App.FileID = File.FileID
				left join ProrateHistory on App.AppId = ProrateHistory.AppId and ProrateHistory.StartDt <= current_timestamp() and ProrateHistory.EndDt >= current_timestamp() and ProrateHistory.IsDelete = 0
				where App.AppStatus < 2 order by App.SortIndex asc,App.CreateDt desc";
		$appList=self::$_pdo->query_with_prepare($sql);
		$sql="select * from AppVersion where  State<2 and App_AppVersionId is null and PackageState=3 order by CreateDt desc";
		$appVersionRst=self::$_pdo->query_with_prepare($sql);
		$output = array();
		$AppConfig=LunaConfigMagt::getInstance()->getAppConfig();
		
		$appPackageInfos=$this->getCurrentAppPackageInfos($PromoterId);
		$appPackageHistoryInfos=$this->getAppPackageInfosHistory($PromoterId);
		
		foreach ($appList as $item){
			if(($item['State'] == 0 && @strpos($item['AVTP'], $userPhone) === false) && ($item['AppStatus'] == 0 && @strpos($item['ATP'], $userPhone) === false)) {
				continue;
			}
			$gameVersionId=$this->getAppVersionId($appVersionRst,$userPhone,$item['AppId']);
			if(empty($gameVersionId)){
				continue;
			}
				
			//$shareUrl=$this->getPromoterShareUrl($item['AppId'],$gameVersionId,$PromoterId);
			$shareUrl=$this->getPromoterShareUrlEx($item['AppId'], $gameVersionId, $appPackageInfos, $appPackageHistoryInfos);
				
			if(false==empty($shareUrl)){
				$shareUrl=$AppConfig['share_host'].$shareUrl;
			}
			$appItem=array(
					"id"			=>	$item['AppId'],
					"name"			=>	$item['AppName'],
					'AppType'       =>  $item['AppType'],
					"versionId"		=>	$gameVersionId,
					"summary"		=>	$item['AppIntroduct'],
					"returnsRate"	=>	bcmul($item['PromoterProrate'], "100",0),
					"hasShared"		=>	true,
					"shareUrl"		=>	$shareUrl,
					"downloadUrl"	=>	str_replace("/s/","/p/", $shareUrl),
			);
			$output[$item['AppId']] =	$appItem;
		}
		return $output;
	}	

}
