<?php

class RegEmailLoginDefController extends TableMagtController 
{
	private $_tableName="reg_email_login";
	private $_searchName="server_domain";
	private $_next_url="/regEmailLoginDef/index";
	private $_columns=array("server_domain","server_login_url");
	private $_title="注册配置-邮件登陆地址定义";
	private $_primaryKey="idx";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="RegisterCfg";
		$this->_MEMCACHE_NODE_NAME="RegisterCfg";
		$this->_memcacheKey=array("reg_email_login");		
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