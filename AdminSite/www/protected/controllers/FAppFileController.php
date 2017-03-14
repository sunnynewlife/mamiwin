<?php

class FAppFileController extends TableMagtController 
{
	private $_tableName="File";
	private $_searchName="Scene";
	private $_next_url="/fAppFile/index";
	private $_columns=array("Scene","FileUrl");
	private $_title="分红管理后台-图片资源管理";
	private $_primaryKey="FileId";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
		$this->_SERACH_FIELD_COMPARE_TYPE=array("Scene" =>"like");		
	}
	
	public function actionIndex()
	{
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index");
	}
	
	private function getGameNameByAppId($appId,$appDef)
	{
		foreach ($appDef as $item){
			if($item["AppId"]==$appId){
				return $item["AppName"];
			}
		}
		return $appId;	
	}
	protected function getPageRowsExtentData($data)
	{
		$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
		$fAppData=new FAppData();
		$games=$fAppData->getApp();
		if(isset($data)  && is_array($data) ){
			$dataCount=count($data);
			for($index=0;$index<$dataCount;$index++){
				$data[$index]["SceneName"]=$this->getGameNameByAppId($data[$index]["Scene"], $games);
				$data[$index]["DownloadUrl"]=sprintf("%s%s",$appConfig["FApp_game_domain"],$data[$index]["FileUrl"]);
			}
		}
		return $data;
	}	
	
	private function checkExtensionName($extName)
	{
		$allowedExtensionName=array("jpg","JPG","png","PNG","gif","GIF",);
		return in_array($extName, $allowedExtensionName);
	}
	
	public function actionAdd()
	{
		$submit = trim(Yii::app()->request->getParam('submit'),0);
		if ($submit){
			if(isset($_FILES["GamePic"])){
				$files=$_FILES["GamePic"];
				$Scene =trim(Yii::app()->request->getParam('Scene'),"");
				
				if($files["name"]!=""){
					$name=$files["name"];
					$str_name=pathinfo($name);
					$extname=strtolower($str_name["extension"]);
					if($this->checkExtensionName($extname)==false){
						$this->alert('error',sprintf("增加%s失败",$this->_title));
						return;
					}
					$filename=date("YndHis").rand(1000,9999).".".$extname;
					$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
					$picDir=$appConfig["FApp_game_pic_path"];
					if(!file_exists($picDir)){
						mkdir($picDir);
					}
					if(move_uploaded_file($files["tmp_name"],$picDir.DIRECTORY_SEPARATOR.$filename)){
						$fAppData=new FAppData();
						if($fAppData->insertFile($Scene, $filename, $files["name"], $files["type"])>0){
							$this->exitWithSuccess(sprintf("增加%s成功",$this->_title),$this->_next_url);
						}
						else {
							$this->alert('error',sprintf("增加%s失败",$this->_title));
						}	
					}
				}
			}						
		}
		$this->render("add",$this->renderData);
	}
	
	
	public function actionModify()
	{
		$this->_actionModify($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url, $this->_tableName, $this->_columns);
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url);
	}
}
?>