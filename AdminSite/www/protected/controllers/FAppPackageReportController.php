<?php

class FAppPackageReportController  extends CController 
{
	
	protected $_PDO_NODE_NAME="";			//PDO 配置节点名称
	protected $_MEMCACHE_NODE_NAME="";		//Memcache 配置节点名称
	protected $_MEMCACHE_PREFIX_KEY="";		//Memcache 存储前缀
	
	protected $_memcacheKey=array();
	
	protected $_SERACH_FIELD_COMPARE_TYPE=array();
	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
	}
	
	public function actionTaskReport()
	{
		$raw_post = file_get_contents("php://input");
		$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
		if (!empty($raw_post)) {			
			$post_data = json_decode($raw_post, true);
			$gameId			=$post_data["gameid"];
			$gameVersion	=$post_data["version"];
			$channelUrl		=$post_data["result"][$appConfig["FApp_game_packing_channel_id"]];
				
		}else{
			$gameId			=trim(Yii::app()->request->getParam('gameid',""));
			$gameVersion	=trim(Yii::app()->request->getParam('version',""));
			$channelUrl		=trim(Yii::app()->request->getParam($appConfig["FApp_game_packing_channel_id"],""));
		}
		if(empty($gameId)==false && empty($gameVersion)==false && empty($channelUrl)==false){
			$fAppData=new FAppData();
			$fAppData->updateAppVersionTaskStatus($gameId, $gameVersion, $channelUrl);
		}
		echo "{\"return_code\":0,\"return_msg\":\"success.\"}";
	}
	
	private function appendPromoterPackage()
	{
		$iscdnurl="0";
		$raw_post = file_get_contents("php://input");
		if (!empty($raw_post)) {
			$post_data = json_decode($raw_post, true);
		
			$promoterNo		=$post_data["promoter"];
			$downloadUrl	=$post_data["url"];
			$appId			=$post_data["gameid"];
			$appVersionId	=$post_data["version"];
			if(isset($post_data["iscdnurl"])){
				$iscdnurl=$post_data["iscdnurl"];
			}				
		}else{
			$promoterNo		=trim(Yii::app()->request->getParam('promoter',""));
			$downloadUrl	=trim(Yii::app()->request->getParam('url',""));
			$appId			=trim(Yii::app()->request->getParam('gameid',""));
			$appVersionId	=trim(Yii::app()->request->getParam('version',""));
			$iscdnurl		=trim(Yii::app()->request->getParam('iscdnurl',"0"));
		}
		$isCDNPool= ($iscdnurl=="1");
		if(empty($promoterNo)==false && empty($downloadUrl)==false && empty($appId)==false && empty($appVersionId)==false ){
			$fAppData=new FAppData();
			if($fAppData->insertAppPackage($appVersionId, $promoterNo, $downloadUrl,$isCDNPool)>0){
				list(,,$promoterId)=explode("_",$promoterNo);
				$fAppData->updateAppPackaging_Log($appVersionId, $promoterId);
			}
		}
		echo "{\"return_code\":0,\"return_msg\":\"success.\"}";
	}
	
	public function actionAppend()
	{
		$this->appendPromoterPackage();
	} 
	
	public function actionAppendCdn()
	{
		$this->appendPromoterPackage();
	}
}
?>