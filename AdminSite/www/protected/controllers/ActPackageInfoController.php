<?php

class ActPackageInfoController  extends TableMagtController 
{
	private $_columns=array("name","type","package_data","icon","game_code","aid","period_type","period_range","limit_type","limit_qty","user_limit_type","user_limit_qty","area_range","status","order_id","total_package_count","push_title","push_msg","msg_abstract");
	private $_tableName="act_package_info";
	private $_title="活动礼包信息";
	private $_index_url="/actPackageInfo/index";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="EvnPlatformCfg";
	}
	public function actionIndex()
	{
		$this->_actionIndex("act_package_info", "name",$this->_index_url, "package_info","index");	
	}
	
	public function actionAdd()
	{
		$this->_actionAdd($this->_tableName, $this->_title, $this->_index_url, $this->_columns);
	}
	
	public function actionModify()
	{
		$this->_actionModify($this->_tableName,"pid", $this->_title, $this->_index_url, "package_info", $this->_columns);		
	}
	
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, "pid", $this->_title, $this->_index_url);
	}
}

?>