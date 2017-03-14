<?php

class GAppRoomZoneCodeController extends TableMagtController 
{
	private $_tableName="t_chat_room_and_zone_code";
	private $_searchName="id";
	private $_next_url="/gAppRoomZoneCode/index";
	private $_columns=array("chat_room_name","zone_code");
	private $_title="G家-同城聊天管理-同城聊天室编号";
	private $_primaryKey="id";

	protected  $_memcacheKey=array("t_chat_room_and_zone_code.{ID}");
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
		$this->_actionModify($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url, $this->_tableName, $this->_columns,"modify","zone_code");
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url,"zone_code");
	}
}
?>