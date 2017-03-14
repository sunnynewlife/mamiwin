<?php

class RegMsgDefController extends TableMagtController 
{
	private $_tableName="reg_cfg_message";
	private $_searchName="error_code";
	private $_next_url="/regMsgDef/index";
	private $_columns=array("error_code","error_msg");
	private $_title="注册配置-错误信息转换定义";
	private $_primaryKey="idx";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="RegisterCfg";
		$this->_MEMCACHE_NODE_NAME="RegisterCfg";
		$this->_memcacheKey=array("reg_cfg_message");		
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