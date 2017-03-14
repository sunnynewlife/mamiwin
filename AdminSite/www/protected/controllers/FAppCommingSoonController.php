<?php

class FAppCommingSoonController extends TableMagtController 
{
	private $_tableName="AppComingSoon";
	private $_searchName="Name";
	private $_next_url="/fAppCommingSoon/index";
	private $_columns=array("Name","SortIndex","Introduction","LogoPicId");
	private $_title="分红管理后台-配置定义";
	private $_primaryKey="RecId";
	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
		$this->_SERACH_FIELD_COMPARE_TYPE=array("Name" =>"like");
	}
	
	private function getFileUrl($fileId,$PicFiles)
	{
		foreach ($PicFiles as $row){
			if($row["FileId"]==$fileId){
				return $row["FileUrl"];
			}
		}
		return "";
	}
	
	protected function getPageRowsExtentData($data)
	{
		$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
		$fAppData=new FAppData();
		$PicFiles=$fAppData->getFiles();
		foreach ($data as & $item){
			$item["DownloadUrl"]=sprintf("%s%s",$appConfig["FApp_game_domain"],$this->getFileUrl($item["LogoPicId"], $PicFiles));
		}
		return $data;
	}
	
	public function actionIndex()
	{
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index","SortIndex asc");
	}
	public function actionAdd()
	{
		$this->_actionAdd($this->_tableName, $this->_title, $this->_next_url, $this->_columns);
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