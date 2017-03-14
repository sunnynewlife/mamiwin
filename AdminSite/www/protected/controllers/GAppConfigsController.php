<?php

class GAppConfigsController extends TableMagtController 
{
	private $_tableName="t_app_configs";
	private $_searchName="config_key";
	private $_next_url="/gAppConfigs/index";
	private $_columns=array("config_key","config_value");
	private $_title=" 	G家-同城聊天管理-配置定义";
	private $_primaryKey="id";
	
	protected $_memcacheKey=array("t_app_config.{ID}");
	protected $_MEMCACHE_PREFIX_KEY="_gServ.sdo.com_";		//Memcache 存储前缀
		
	public function init()
	{
		$this->_PDO_NODE_NAME="GAppCfg";
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
		$this->_actionModify($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url, $this->_tableName, $this->_columns,"modify","config_key");
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url,"config_key");
	}
}
?>