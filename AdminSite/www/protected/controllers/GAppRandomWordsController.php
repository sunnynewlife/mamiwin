<?php

class GAppRandomWordsController extends TableMagtController 
{
	private $_tableName="t_random_words";
	private $_searchName="id";
	private $_next_url="/gAppRandomWords/index";
	private $_columns=array("random_words","is_valid");
	private $_title=" 	G家-同城聊天管理-随机赠言定义";
	private $_primaryKey="id";
	
	protected  $_memcacheKey=array("t_random_words");
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