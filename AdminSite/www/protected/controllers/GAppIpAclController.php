<?php

class GAppIpAclController extends TableMagtController 
{
	private $_tableName="t_ip_acl";
	private $_searchName="ip";
	private $_next_url="/gAppIpAcl/index";
	private $_columns=array("ip","name");
	private $_title="G家-同城聊天管理-接口访问IP配置";
	private $_primaryKey="idx";
	
	protected $_memcacheKey=array("t_ip_acl");
	protected $_MEMCACHE_PREFIX_KEY="_gServ.sdo.com_";
	
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
		$this->_actionModify($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url, $this->_tableName, $this->_columns);
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url);
	}
}
?>