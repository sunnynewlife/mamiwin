<?php

class RegAppDefController extends TableMagtController 
{
	private $_tableName="reg_app_def";
	private $_searchName="app_id";
	private $_next_url="/regAppDef/index";
	private $_columns=array("home_type","account_type_phone","account_type_email","account_type_custom","display_real_name","can_override_display_real_name","app_id");
	private $_title="注册配置-应用接入定义";
	private $_primaryKey="idx";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="RegisterCfg";
		$this->_MEMCACHE_NODE_NAME="RegisterCfg";
		$this->_memcacheKey=array("reg_app_def");
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