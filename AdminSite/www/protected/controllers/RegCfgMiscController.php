<?php

class RegCfgMiscController extends TableMagtController 
{
	private $_tableName="reg_cfg_misc";
	private $_searchName="cfg_key";
	private $_next_url="/regCfgMisc/index";
	private $_columns=array("cfg_key","cfg_desc","cfg_value");
	private $_title="注册配置-其他配置定义";
	private $_primaryKey="idx";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="RegisterCfg";
		$this->_MEMCACHE_NODE_NAME="RegisterCfg";
		$this->_memcacheKey=array("reg_cfg_misc");
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