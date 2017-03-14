<?php

class FAppAdGameListController extends TableMagtController 
{
	private $_tableName="SystemConfig";
	private $_searchName="ConfigKey";
	private $_next_url="/fAppAdGameList/index";
	private $_columns=array("");
	private $_title="分红管理后台-游戏列表页走马灯配置";
	private $_primaryKey="ConfigId";
	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
	}
	public function actionIndex()
	{
		$fAppData=new FAppData();
		$this->renderData["AdList"]=$fAppData->getAdGameListDef();
		$this->render("index",$this->renderData);
	}
	public function actionAdd()
	{
		$submit = trim(Yii::app()->request->getParam('submit',0));
		$fAppData=new FAppData();
		if($submit){
			$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
			$adGameListDef=$fAppData->getAdGameListDef();
			
			$pic=trim(Yii::app()->request->getParam("pic",""));
			$url=trim(Yii::app()->request->getParam("url",""));
			$needUserId=Yii::app()->request->getParam("needUserId","0");
			$ip=trim(Yii::app()->request->getParam("ip",""));
			$picUrl=sprintf("%s%s",$appConfig["FApp_game_domain"],$fAppData->getFileNameByFielId($pic));
			$target=trim(Yii::app()->request->getParam("target",""));
			$AppId=trim(Yii::app()->request->getParam("gameId","0"));
			$item=array("pic" 		=> $picUrl,
					"url"			=> $url,
					"needUserId"	=> $needUserId,
					"ip"			=> $ip,
					"target"		=> $target,
					"appId"			=> $AppId,
					"versionId"		=> "",	
			);
			$adGameListDef[]=$item;
			$adGameListStr=json_encode($adGameListDef,true);
			if($fAppData->updateAdGameListDef($adGameListStr)>0){
				$this->exitWithSuccess(sprintf("增加%s成功",$this->_title),$this->_next_url);
			}else{
				$this->alert('error',sprintf("增加%s失败",$this->_title));
			}				
		}
		$this->renderData["AppList"]=$fAppData->getApp();
		$this->render("add",$this->renderData);
	}
	public function actionMove()
	{
		$fAppData=new FAppData();
		$direct = trim(Yii::app()->request->getParam('direct',""));
		$frameId= trim(Yii::app()->request->getParam('frameId',""));
		if(($direct=="up" || $direct=="down") && empty($frameId)==false){
			$adGameListDef=$fAppData->getAdGameListDef();
			if($frameId>count($adGameListDef) || $frameId<1){
				$this->exitWithError("参数错误",$this->_next_url);
			}
				
			$src=$adGameListDef[$frameId-1];
			if($direct=="up"){
				if($frameId>1){
					$tmp=$adGameListDef[$frameId-2];
					$adGameListDef[$frameId-2]=$src;
					$adGameListDef[$frameId-1]=$tmp;
	
					$adGameListStr=json_encode($adGameListDef,true);
					$fAppData->updateAdGameListDef($adGameListStr);
				}else{
					$this->exitWithError("参数错误",$this->_next_url);
				}
			}else{
				if($frameId==count($adGameListDef)){
					$this->exitWithError("参数错误",$this->_next_url);
				}else{
					$tmp=$adGameListDef[$frameId];
					$adGameListDef[$frameId]=$src;
					$adGameListDef[$frameId-1]=$tmp;
						
					$adGameListStr=json_encode($adGameListDef,true);
					$fAppData->updateAdGameListDef($adGameListStr);
				}
			}
		}
		$this->renderData["AdList"]=$fAppData->getAdGameListDef();
		$this->render("index",$this->renderData);
	}		
	public function actionModify()
	{
		$value = Yii::app()->request->getParam("frameId",'');
		if(empty($value)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$fAppData=new FAppData();
		$adGameListDef=$fAppData->getAdGameListDef();
		$frameId=intval($value);
		if($frameId>count($adGameListDef) || $frameId<1){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$appConfig=LunaConfigMagt::getInstance()->getAppConfig();

		$picName=str_replace($appConfig["FApp_game_domain"], "", $adGameListDef[$frameId-1]["pic"]);
		$fileDef=$fAppData->getFileIdByFileName($picName);
		if(count($fileDef)>0){
			$adGameListDef[$frameId-1]["pic"]=$fileDef["FileId"];
		}
		
		$this->renderData["frameId"]=$value;
		$this->renderData["AdItem"]=$adGameListDef[$frameId-1];
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$pic=trim(Yii::app()->request->getParam("pic",""));
			$url=trim(Yii::app()->request->getParam("url",""));
			$needUserId=Yii::app()->request->getParam("needUserId","0");
			$ip=trim(Yii::app()->request->getParam("ip",""));
			$picUrl=sprintf("%s%s",$appConfig["FApp_game_domain"],$fAppData->getFileNameByFielId($pic));
			$target=trim(Yii::app()->request->getParam("target",""));
			$AppId=trim(Yii::app()->request->getParam("gameId","0"));
				
			
			$adGameListDef[$frameId-1]["pic"]=$picUrl;
			$adGameListDef[$frameId-1]["url"]=$url;
			$adGameListDef[$frameId-1]["needUserId"]=$needUserId;
			$adGameListDef[$frameId-1]["ip"]=$ip;
			$adGameListDef[$frameId-1]["target"]=$target;
			$adGameListDef[$frameId-1]["appId"]=$AppId;
			$adGameListDef[$frameId-1]["versionId"]="";
				
			$adGameListStr=json_encode($adGameListDef,true);
			if($fAppData->updateAdGameListDef($adGameListStr)>0){
				$this->exitWithSuccess(sprintf("修改%s成功",$this->_title),$this->_next_url);
			}else{
				$this->alert('error',sprintf("修改%s失败",$this->_title));
			}
		}
		$this->renderData["AppList"]=$fAppData->getApp();
		$this->render("modify",$this->renderData);
		
	}
	public function actionDel()
	{
		$value = Yii::app()->request->getParam("frameId",'');
		if(empty($value)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$fAppData=new FAppData();
		$adGameListDef=$fAppData->getAdGameListDef();
		$frameId=intval($value);
		if($frameId>count($adGameListDef) || $frameId<1){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$newAdGameList=array();
		$adLens=count($adGameListDef);
		for($index=1;$index<=$adLens;$index++){
			if($index!=$frameId){
				array_push($newAdGameList,$adGameListDef[$index-1]);
			}
		}
		$adGameListStr=json_encode($newAdGameList,true);
		if($fAppData->updateAdGameListDef($adGameListStr)>0){
			$this->exitWithSuccess(sprintf("删除%s成功",$this->_title),$this->_next_url);
		}else{
			$this->alert('error',sprintf("删除%s失败",$this->_title));
		}
	}
	
	public function actionQAppIndex()
	{
		$fAppData=new FAppData();
		$this->renderData["AdList"]=$fAppData->getSystemConfigItem("wx.ad.gamelist",array(),true);
		$this->render("qapp_index",$this->renderData);
	}
	public function actionQAppAdd()
	{
		$this->_title="分红管理后台-微信游戏列表页走马灯配置";
		$this->_next_url="/fAppAdGameList/qAppIndex";
		
		$submit = trim(Yii::app()->request->getParam('submit',0));
		$fAppData=new FAppData();
		if($submit){
			$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
			$adGameListDef=$fAppData->getSystemConfigItem("wx.ad.gamelist",array(),true);
				
			$pic=trim(Yii::app()->request->getParam("pic",""));
			$url=trim(Yii::app()->request->getParam("url",""));
			$acl=trim(Yii::app()->request->getParam("acl",""));
			
			$picUrl=sprintf("%s%s",$appConfig["FApp_game_domain"],$fAppData->getFileNameByFielId($pic));

			$item=array(
					"pic" 			=> $picUrl,
					"url"			=> $url,
					"acl"			=> $acl,
			);
			$adGameListDef[]=$item;
			$adGameListStr=json_encode($adGameListDef,true);
			
			if($fAppData->updateSystemConfigItem("wx.ad.gamelist",$adGameListStr)>0){
				$this->exitWithSuccess(sprintf("增加%s成功",$this->_title),$this->_next_url);
			}else{
				$this->alert('error',sprintf("增加%s失败",$this->_title));
			}
		}
		$this->render("qapp_add",$this->renderData);		
	}
	public function actionQAppModify()
	{
		$this->_title="分红管理后台-微信游戏列表页走马灯配置";
		$this->_next_url="/fAppAdGameList/qAppIndex";
		
		$value = Yii::app()->request->getParam("frameId",'');
		if(empty($value)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$fAppData=new FAppData();
		$adGameListDef=$fAppData->getSystemConfigItem("wx.ad.gamelist",array(),true);
		$frameId=intval($value);
		if($frameId>count($adGameListDef) || $frameId<1){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
		
		$picName=str_replace($appConfig["FApp_game_domain"], "", $adGameListDef[$frameId-1]["pic"]);
		$fileDef=$fAppData->getFileIdByFileName($picName);
		if(count($fileDef)>0){
			$adGameListDef[$frameId-1]["pic"]=$fileDef["FileId"];
		}
		
		$this->renderData["frameId"]=$value;
		$this->renderData["AdItem"]=$adGameListDef[$frameId-1];
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$pic=trim(Yii::app()->request->getParam("pic",""));
			$url=trim(Yii::app()->request->getParam("url",""));
			$acl=trim(Yii::app()->request->getParam("acl",""));
			$picUrl=sprintf("%s%s",$appConfig["FApp_game_domain"],$fAppData->getFileNameByFielId($pic));
				
			$adGameListDef[$frameId-1]["pic"]=$picUrl;
			$adGameListDef[$frameId-1]["url"]=$url;
			$adGameListDef[$frameId-1]["acl"]=$acl;
		
			$adGameListStr=json_encode($adGameListDef,true);
			if($fAppData->updateSystemConfigItem("wx.ad.gamelist",$adGameListStr)>0){
				$this->exitWithSuccess(sprintf("修改%s成功",$this->_title),$this->_next_url);
			}else{
				$this->alert('error',sprintf("修改%s失败",$this->_title));
			}
		}
		$this->render("qapp_modify",$this->renderData);		
	}
	public function actionQAppMove()
	{
		$this->_title="分红管理后台-微信游戏列表页走马灯配置";
		$this->_next_url="/fAppAdGameList/qAppIndex";
		
		$fAppData=new FAppData();
		$direct = trim(Yii::app()->request->getParam('direct',""));
		$frameId= trim(Yii::app()->request->getParam('frameId',""));
		if(($direct=="up" || $direct=="down") && empty($frameId)==false){
			$adGameListDef=$fAppData->getSystemConfigItem("wx.ad.gamelist",array(),true);
			if($frameId>count($adGameListDef) || $frameId<1){
				$this->exitWithError("参数错误",$this->_next_url);
			}
		
			$src=$adGameListDef[$frameId-1];
			if($direct=="up"){
				if($frameId>1){
					$tmp=$adGameListDef[$frameId-2];
					$adGameListDef[$frameId-2]=$src;
					$adGameListDef[$frameId-1]=$tmp;
		
					$adGameListStr=json_encode($adGameListDef,true);
					$fAppData->updateSystemConfigItem("wx.ad.gamelist",$adGameListStr);
				}else{
					$this->exitWithError("参数错误",$this->_next_url);
				}
			}else{
				if($frameId==count($adGameListDef)){
					$this->exitWithError("参数错误",$this->_next_url);
				}else{
					$tmp=$adGameListDef[$frameId];
					$adGameListDef[$frameId]=$src;
					$adGameListDef[$frameId-1]=$tmp;
		
					$adGameListStr=json_encode($adGameListDef,true);
					$fAppData->updateSystemConfigItem("wx.ad.gamelist",$adGameListStr);
				}
			}
		}
		$this->renderData["AdList"]=$fAppData->getSystemConfigItem("wx.ad.gamelist",array(),true);
		$this->render("qapp_index",$this->renderData);		
	}
	public function actionQAppDel()
	{
		$this->_title="分红管理后台-微信游戏列表页走马灯配置";
		$this->_next_url="/fAppAdGameList/qAppIndex";
		
		$value = Yii::app()->request->getParam("frameId",'');
		if(empty($value)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$fAppData=new FAppData();
		$adGameListDef=$fAppData->getSystemConfigItem("wx.ad.gamelist",array(),true);
		$frameId=intval($value);
		if($frameId>count($adGameListDef) || $frameId<1){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$newAdGameList=array();
		$adLens=count($adGameListDef);
		for($index=1;$index<=$adLens;$index++){
			if($index!=$frameId){
				array_push($newAdGameList,$adGameListDef[$index-1]);
			}
		}
		$adGameListStr=json_encode($newAdGameList,true);
		if($fAppData->updateSystemConfigItem("wx.ad.gamelist",$adGameListStr)>0){
			$this->exitWithSuccess(sprintf("删除%s成功",$this->_title),$this->_next_url);
		}else{
			$this->alert('error',sprintf("删除%s失败",$this->_title));
		}		
	}
}
?>