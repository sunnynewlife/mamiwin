<?php


class ActSendStatusUserController extends TableMagtController  
{
	private $_columns=array("uid","aid","areaid","date","sendNum");
	private $_tableName="user_act_send_status";
	private $_title="用户活动领取量记录";
	private $_index_url="/actSendStatusUser/index";
	private $_primary_key="ID";

	public function init()
	{
		$this->_PDO_NODE_NAME="EvnPlatformCfg";
	}
	public function actionIndex()
	{
		$this->_actionIndex($this->_tableName, "uid", $this->_index_url, "user_act_send_status");
	}
	public function actionModify()
	{
		$this->_actionModify($this->_tableName, $this->_primary_key, $this->_title, $this->_index_url, "user_act_send_status", $this->_columns);
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, $this->_primary_key, $this->_title, $this->_index_url);
	}	
}

?>