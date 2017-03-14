<?php

class FAppAdStartController extends TableMagtController 
{
	private $_tableName="SystemConfig";
	private $_searchName="ConfigKey";
	private $_next_url="/fAppAdStart/index";
	private $_columns=array("");
	private $_title="分红管理后台-游戏启动宣传图配置";
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
		$this->renderData["AdList"]=$fAppData->getAdStartListDef();
		$this->render("index",$this->renderData);
	}
	public function actionAdd()
	{
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
			$fAppData=new FAppData();
			$adGameListDef=$fAppData->getAdStartListDef();
			
			$pic=trim(Yii::app()->request->getParam("pic",""));
			$picUrl=sprintf("%s%s",$appConfig["FApp_game_domain"],$fAppData->getFileNameByFielId($pic));
			$item=array("pic" 		=> $picUrl,
			);
			$adGameListDef[]=$item;
			$adGameListStr=json_encode($adGameListDef,true);
			if($fAppData->updateAdStartListDef($adGameListStr)>0){
				$this->exitWithSuccess(sprintf("增加%s成功",$this->_title),$this->_next_url);
			}else{
				$this->alert('error',sprintf("增加%s失败",$this->_title));
			}				
		}
		$this->render("add");
	}
	public function actionModify()
	{
		$value = Yii::app()->request->getParam("frameId",'');
		if(empty($value)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$fAppData=new FAppData();
		$adGameListDef=$fAppData->getAdStartListDef();
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
			$picUrl=sprintf("%s%s",$appConfig["FApp_game_domain"],$fAppData->getFileNameByFielId($pic));
			$adGameListDef[$frameId-1]["pic"]=$picUrl;
			
			$adGameListStr=json_encode($adGameListDef,true);
			if($fAppData->updateAdStartListDef($adGameListStr)>0){
				$this->exitWithSuccess(sprintf("修改%s成功",$this->_title),$this->_next_url);
			}else{
				$this->alert('error',sprintf("修改%s失败",$this->_title));
			}
		}
		$this->render("modify",$this->renderData);
		
	}
	public function actionDel()
	{
		$value = Yii::app()->request->getParam("frameId",'');
		if(empty($value)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$fAppData=new FAppData();
		$adGameListDef=$fAppData->getAdStartListDef();
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
		if($fAppData->updateAdStartListDef($adGameListStr)>0){
			$this->exitWithSuccess(sprintf("删除%s成功",$this->_title),$this->_next_url);
		}else{
			$this->alert('error',sprintf("删除%s失败",$this->_title));
		}
	}
}
?>