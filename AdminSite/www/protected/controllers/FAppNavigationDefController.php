<?php

class FAppNavigationDefController extends TableMagtController 
{
	private $_tableName="AppNavigationDef";
	private $_searchName="";
	private $_next_url="/fAppNavigationDef/index";
	private $_columns=array("FileId","CategoryId","PositionIndex","Games");
	private $_title="分红管理后台-游戏分类设置";
	private $_primaryKey="RecId";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
	}
	
	public function actionIndex()
	{
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index","PositionIndex");
	}
	
	protected function getPageRowsExtentData($data)
	{
		$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
		$fAppData=new FAppData();
		$games			=	$fAppData->getApp();
		$gameTypeDef	=	$fAppData->getGameTypeDefinition();
		$picFiles		=	$fAppData->getFiles();
		if(isset($data)  && is_array($data) ){
			$dataCount=count($data);
			for($index=0;$index<$dataCount;$index++){
				$data[$index]["DownloadUrl"]=sprintf("%s%s",$appConfig["FApp_game_domain"],$this->getPicFileUrlByFileId($data[$index]["FileId"], $picFiles));
				$data[$index]["CategoryName"]=$this->getCategoryNameById($data[$index]["CategoryId"], $gameTypeDef);
				$data[$index]["GameNames"]=$this->getAppNames($data[$index]["Games"], $games);
			}
		}
		return $data;
	}	
	
	private function getCategoryNameById($categoryId,$CategoryList)
	{
		foreach ($CategoryList as $row){
			if($row["code"]==$categoryId){
				return $row["name"];
			}
		}
		return "";
	}
	private function getPicFileUrlByFileId($fileId,$PicFiles)
	{
		foreach ($PicFiles as $row){
			if($row["FileId"]==$fileId){
				return $row["FileUrl"];
			}
		}
		return "";
	}
	private function getAppNames($appIds,$AppList)
	{
		$names=array();
		$gameAppIds=explode(",", $appIds);
		foreach ($gameAppIds as $appId){
			foreach ($AppList as $row){
				if($appId==$row["AppId"]){
					$names[]=$row["AppName"];
					break;
				}
			}
		}
		return implode(",", $names);
	}
	
	private function getAppCategory4Select($fAppData,$keptCategoryId="")
	{
		$selectedArray=array();
		$gameTypeDef	=	$fAppData->getGameTypeDefinition();
		$appNaviDefs	= 	$fAppData->getAppNavigationDefs();
		foreach ($gameTypeDef as $row){
			$code=$row["code"];
			$notFound=true;
			foreach ($appNaviDefs as $naviDef){
				if($naviDef["CategoryId"]==$code){
					$notFound=false;
					break;
				}
			}
			if($notFound || $code==$keptCategoryId){
				$selectedArray[$code]=$row["name"];
			}
		}
		return $selectedArray;
	}
	
	public function actionAdd()
	{
		$fAppData=new FAppData();
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if ($submit){
			$CategoryId		= trim(Yii::app()->request->getParam('CategoryId',""));
			$FileId			= trim(Yii::app()->request->getParam('FileId',""));
			$PositionIndex	= trim(Yii::app()->request->getParam('PositionIndex',""));
			$Games			= trim(Yii::app()->request->getParam('Games',""));
			
			if($fAppData->insertAppNavigationDef($CategoryId, $FileId, $PositionIndex, $Games)>0){
				$this->exitWithSuccess("增加游戏分类成功",$this->_next_url);
			}else{
				$this->alert('error',"增加游戏分类失败");
			}			
		}
		$this->renderData["Category"]=$this->getAppCategory4Select($fAppData);
		$this->render("add",$this->renderData);
	}
	public function actionModify()
	{
		$fAppData=new FAppData();
		$submit = trim(Yii::app()->request->getParam('submit',0));
		$recId= trim(Yii::app()->request->getParam('RecId',""));
		if(empty($recId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$appNavDef=$fAppData->getAppNavigationDefById($recId);
		if($appNavDef==false || is_array($appNavDef)==false || count($appNavDef)==0){
			$this->exitWithError("参数值错误",$this->_next_url);
		}
		if ($submit){
			$CategoryId		= trim(Yii::app()->request->getParam('CategoryId',""));
			$FileId			= trim(Yii::app()->request->getParam('FileId',""));
			$PositionIndex	= trim(Yii::app()->request->getParam('PositionIndex',""));
			$Games			= trim(Yii::app()->request->getParam('Games',""));
				
			if($fAppData->updateAppNavigationDefById($CategoryId, $FileId, $PositionIndex, $Games, $recId)>0){
				$this->exitWithSuccess("修改游戏分类成功",$this->_next_url);
			}else{
				$this->alert('error',"修改游戏分类失败");
			}
		}
		$this->renderData["Category"]=$this->getAppCategory4Select($fAppData,$appNavDef["CategoryId"]);
		$this->renderData["AppNavDef"]=$appNavDef;
		$this->render("modify",$this->renderData);		
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url);
	}
}
?>