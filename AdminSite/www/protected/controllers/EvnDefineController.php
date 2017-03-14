<?php

class EvnDefineController extends TableMagtController 
{
	public function init()
	{
		$this->_PDO_NODE_NAME="EvnPlatformCfg";
	}
	public function actionIndex()
	{
		$this->_actionIndex("evn_define", "event_code", "/evnDefine/index", "evn_define_list","index");
	}
	
	public function actionAdd()
	{
		$columnNames=array("event_code","event_status","auth_type","auth_type_cfg_name","aid_list");
		$this->_actionAdd("evn_define", "游戏活动接入资料", "/evnDefine/index", $columnNames,"add");
	}
	
	public function actionModify()
	{
		$columnNames=array("event_code","event_status","auth_type","auth_type_cfg_name","aid_list");
		$this->_actionModify("evn_define", "event_code", "游戏活动接入资料", "/evnDefine/index", "evn_define", $columnNames,"modify");
	}
	
	public function actionDel()
	{
		$this->_actionDel("evn_define", "event_code", "游戏活动接入资料", "/evnDefine/index");
	}
}

?>