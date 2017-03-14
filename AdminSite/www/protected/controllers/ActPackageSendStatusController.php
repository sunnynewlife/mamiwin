<?php


class ActPackageSendStatusController extends TableMagtController
{
	private $_columns=array("pid","areaid","date","sendNum");
	private $_tableName="act_package_send_status";
	private $_title="活动礼包领取量记录";
	private $_index_url="/actPackageSendStatus/index";
	private $_primary_key="ID";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="EvnPlatformCfg";
	}
	public function actionIndex()
	{
		$this->_actionIndex($this->_tableName, "pid", $this->_index_url, "act_package_send_status");
	}
	public function actionModify()
	{
		$this->_actionModify($this->_tableName, $this->_primary_key, $this->_title, $this->_index_url, "act_package_send_status", $this->_columns);
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, $this->_primary_key, $this->_title, $this->_index_url);
	}	
}

?>