<?php

class FAppMsgMagtController extends TableMagtController 
{
	private $_tableName="";
	private $_searchName="";
	private $_next_url="/fAppMsgMagt/index";
	private $_columns=array();
	private $_title="分红管理后台-消息渠道、类型管理";
	private $_primaryKey="";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
	}
	
	public function actionIndex()
	{
		$fAppMsgDepend=new FAppMsgDepend();
		$channels=$fAppMsgDepend->GetChannelList();
		foreach ($channels as & $item){
			$item["types"]=$fAppMsgDepend->GetTypeList($item["id"]);
		}
		$channels=$this->appendLocalChannelProperty($channels);
		$this->renderData["Channels"]=$channels;
		$this->render("index",$this->renderData);
	}
	
	private function appendLocalChannelProperty($channels)
	{
		$fAppData=new FAppData();
		$localChannelDef=$fAppData->getChannelDefList();
		foreach ($channels as & $item){
			$item["DescContent"]	="";
			$item["ListUrl"]		="";
			$item["ListType"]		="";
			$item["SortOrder"]		="";						

			if(count($localChannelDef)>0){
				foreach ($localChannelDef as $rowItem){
					if($rowItem["ChannelId"]==$item["id"]){
						$item["DescContent"]	=$rowItem["DescContent"];
						$item["ListUrl"]		=$rowItem["ListUrl"];
						$item["ListType"]		=$rowItem["ListType"];
						$item["SortOrder"]		=$rowItem["SortOrder"];
						break;
					}
				}
			}
		}
		return $channels;
	}
	
	public function actionChannelModify()
	{
		$channelId=trim(Yii::app()->request->getParam('channelId',""));
		if(empty($channelId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$fAppMsgDepend=new FAppMsgDepend();
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$channelName	=trim(Yii::app()->request->getParam('ChannelName',""));
			$channelIcon	=trim(Yii::app()->request->getParam('ChannelPic',""));
			if(empty($channelName)==false){
				if(empty($channelIcon)==false){
					$channelIcon=$this->getPicIconUrl($channelIcon);
				}
				if($fAppMsgDepend->UpdateChannel($channelName, $channelIcon, $channelId)){
					
					$channelDesc	=	trim(Yii::app()->request->getParam('DescContent',""));
					$SortOrder	 	=	trim(Yii::app()->request->getParam('SortOrder',""));
					$ListType		=	trim(Yii::app()->request->getParam('ListType',""));
					$ListUrl		=	trim(Yii::app()->request->getParam('ListUrl',""));
					$Status			=	trim(Yii::app()->request->getParam('Status',"0"));
					$TestPhone		=	trim(Yii::app()->request->getParam('TestPhone',""));
					
					$fAppData=new FAppData();
					if($fAppData->updateChannelDef($channelName, $channelIcon, $channelDesc, $SortOrder, $ListType, $ListUrl, $channelId,$Status,$TestPhone)){
						$this->exitWithSuccess(sprintf("修改%s成功",$this->_title),$this->_next_url);
					}else{
						$this->alert('error',sprintf("修改%s失败",$this->_title));
					}
				}
			}
			else {
				$this->exitWithError("参数错误",$this->_next_url);
			}				
		}
		$channels=$fAppMsgDepend->GetChannelList();
		$this->renderData["back_url"]=$this->_next_url;
		$this->renderData["Channel"]=$this->GetChannelInfo($channelId, $channels);
		$this->SyncChannelIfNotExist($channelId, $channels);
		$this->render("channelModify",$this->renderData);
	}
	private function SyncChannelIfNotExist($channelId,$channelList)
	{
		foreach ($channelList as $item){
			if($item["id"]==$channelId){
				$fAppData=new FAppData();
				$channelRow=$fAppData->getChannelById($channelId);
				if(count($channelRow)==0){
					$fAppData->syncInsertChannelId($item["id"], $item["icon"], $item["name"]);
				}
				break;
			}
		}
	}
	private function GetChannelInfo($channelId,$channelList)
	{
		foreach ($channelList as & $item){
			if($item["id"]==$channelId){
				$item["icon_id"]=$item["icon"];
				
				$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
				$iconName=str_replace($appConfig["FApp_game_domain"], "", $item["icon"]);
				
				$fAppData=new FAppData();
				$fileDef=$fAppData->getFileIdByFileName($iconName);
				if(count($fileDef)>0){
					$item["icon_id"]=$fileDef["FileId"];
				}
				
				$rowItem=$fAppData->getChannelById($channelId);
				if(count($rowItem)>0){

					$item["DescContent"]	=$rowItem["DescContent"];
					$item["ListUrl"]		=$rowItem["ListUrl"];
					$item["ListType"]		=$rowItem["ListType"];
					$item["SortOrder"]		=$rowItem["SortOrder"];
					$item["Status"]			=$rowItem["Status"];
					$item["TestPhone"]		=$rowItem["TestPhone"];
				}else {
					$item["DescContent"]	="";
					$item["ListUrl"]		="";
					$item["ListType"]		="";
					$item["SortOrder"]		="";
					$item["Status"]			="";
					$item["TestPhone"]		="";
				}				
				return $item;
			}
		}
		return array("id"=>"","name"=>"","icon" =>"" ,"icon_id"=>"");
	}
	
	
	public function actionChannelAdd()
	{
		$fAppData=new FAppData();
		$this->renderData["channel_list_url"]=$fAppData->getSystemConfigItem("app.channel.list.url","");
		
		$this->renderData["back_url"]=$this->_next_url;
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if ($submit){
			$channelName	=trim(Yii::app()->request->getParam('ChannelName',""));
			$channelIcon	=trim(Yii::app()->request->getParam('ChannelPic',""));
			if(empty($channelName)==false){
				if(empty($channelIcon)==false){
					$channelIcon=$this->getPicIconUrl($channelIcon);
				}
				$fAppMsgDepend=new FAppMsgDepend();
				$addRet=$fAppMsgDepend->AddChannel($channelName,$channelIcon);
				if($addRet){
					
					$channelId		=	$addRet["channelId"];
					$channelDesc	=	trim(Yii::app()->request->getParam('DescContent',""));
					$SortOrder	 	=	trim(Yii::app()->request->getParam('SortOrder',""));
					$ListType		=	trim(Yii::app()->request->getParam('ListType',""));
					$ListUrl		=	trim(Yii::app()->request->getParam('ListUrl',""));
					$Status			=	trim(Yii::app()->request->getParam('Status',"0"));
					$TestPhone		=	trim(Yii::app()->request->getParam('TestPhone',""));
						
					$fAppData->insertChannelDef($channelName, $channelIcon, $channelDesc, $SortOrder, $ListType, $ListUrl, $channelId,$Status,$TestPhone);
					
					$this->exitWithSuccess(sprintf("增加%s成功",$this->_title),$this->_next_url);
				}				
			}
			else {
				$this->exitWithError("参数错误",$this->_next_url);
			}
		}
		$this->render("channelAdd",$this->renderData);		
	}
	
	public function actionTypeAdd()
	{
		$channelId=trim(Yii::app()->request->getParam('channelId',""));
		if(empty($channelId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		
		$this->renderData["back_url"]=$this->_next_url;
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if ($submit){
			$typeName	=trim(Yii::app()->request->getParam('typeName',""));
			if(empty($typeName)==false){
				$fAppMsgDepend=new FAppMsgDepend();
				if($fAppMsgDepend->AddType($channelId, $typeName)){
					$this->exitWithSuccess(sprintf("增加%s成功",$this->_title),$this->_next_url);
				}
			}
			else {
				$this->exitWithError("参数错误",$this->_next_url);
			}
		}
		$this->renderData["ChannelId"]=$channelId;
		$this->render("typeAdd",$this->renderData);		
	}
	
	public function actionTypeModify()
	{
		$channelId=trim(Yii::app()->request->getParam('channelId',""));
		if(empty($channelId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$typeId=trim(Yii::app()->request->getParam('typeId',""));
		if(empty($typeId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$typeName	=trim(Yii::app()->request->getParam('typeName',""));
		if(empty($typeName)){
			$typeName=trim(Yii::app()->request->getParam('name',""));
		}
		
		$this->renderData["back_url"]=$this->_next_url;
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if ($submit){
			if(empty($typeName)==false){
				$fAppMsgDepend=new FAppMsgDepend();
				if($fAppMsgDepend->UpdateType($channelId, $typeId, $typeName)){
					$this->exitWithSuccess(sprintf("更新%s成功",$this->_title),$this->_next_url);
				}
			}
			else {
				$this->exitWithError("参数错误",$this->_next_url);
			}
		}
		$this->renderData["typeName"]=$typeName;
		$this->renderData["ChannelId"]=$channelId;
		$this->renderData["TypeId"]=$typeId;
		$this->render("typeModify",$this->renderData);		
	}
	
	public function actionTypeDel()
	{
		$channelId=trim(Yii::app()->request->getParam('channelId',""));
		if(empty($channelId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$typeId=trim(Yii::app()->request->getParam('typeId',""));
		if(empty($typeId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		
		$fAppMsgDepend=new FAppMsgDepend();
		if($fAppMsgDepend->DeleteType($typeId)){
			$this->exitWithSuccess(sprintf("删除%s成功",$this->_title),$this->_next_url);
		}else {
			$this->exitWithError("参数错误",$this->_next_url);
		}
	}
	
	public function actionChannelDel()
	{
		$channelId=trim(Yii::app()->request->getParam('channelId',""));
		if(empty($channelId)){
			$this->exitWithError("参数错误1",$this->_next_url);
		}
		$fAppMsgDepend=new FAppMsgDepend();
		if($fAppMsgDepend->DeleteChannel($channelId)){
			$fAppData=new FAppData();
			$fAppData->delChannel($channelId);
			$this->exitWithSuccess(sprintf("删除%s成功",$this->_title),$this->_next_url);
		}else {
			$this->exitWithError(sprintf("删除%s失败",$this->_title),$this->_next_url);
		}
	}
	
	private function getPicIconUrl($fileId)
	{
		$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
		$fAppData=new FAppData();
		$fileDef=$fAppData->getFileById($fileId);
		if(count($fileDef)>0){
			return sprintf("%s%s",$appConfig["FApp_game_domain"],$fileDef["FileUrl"]);
		}
		return "";
	}
}
?>