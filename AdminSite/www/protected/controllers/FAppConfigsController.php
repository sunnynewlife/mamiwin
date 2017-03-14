<?php

class FAppConfigsController extends TableMagtController 
{
	private $_tableName="SystemConfig";
	private $_searchName="ConfigKey";
	private $_next_url="/fAppConfigs/index";
	private $_columns=array("ConfigKey","ConfigValue","ConfigName");
	private $_title="分红管理后台-配置定义";
	private $_primaryKey="ConfigId";
	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
	}
	
	public function actionIndex()
	{
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index");
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