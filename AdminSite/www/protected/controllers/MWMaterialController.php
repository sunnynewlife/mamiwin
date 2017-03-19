<?php

class MWMaterialController extends TableMagtController
{
	private $_tableName="Task_Material";
	private $_searchName="";
	private $_next_url="/mWMaterial/index";
	private $_columns=array("Task_Type","Task_Title","Task_Status","Min_Time","Max_Time","Matrial_IDX","Min_Age","Max_Age","Child_Gender","Parent_Gender","Parent_Marriage","Only_Children","Matrial_IDX");
	private $_title="父母赢管理后台-任务库定义";
	private $_primaryKey="IDX";
	
	protected $_EXTRA_SEARCH_FIELDS=array(
			"Task_Type" 		=> array("compartion_type" =>"equal","field_name" =>"Task_Type"),
			"Task_Status" 		=> array("compartion_type" =>"equal","field_name" =>"Task_Status"),
			"Min_Time" 			=> array("compartion_type" =>"greater","field_name" =>"Min_Time"),
			"Max_Time" 			=> array("compartion_type" =>"less","field_name" =>"Max_Time"),
			"Min_Age" 			=> array("compartion_type" =>"greater","field_name" =>"Min_Age"),
			"Max_Age" 			=> array("compartion_type" =>"less","field_name" =>"Max_Age"),
			"Child_Gender" 		=> array("compartion_type" =>"equal","field_name" =>"Child_Gender"),
			"Parent_Gender" 	=> array("compartion_type" =>"equal","field_name" =>"Parent_Gender"),
			"Parent_Marriage" 	=> array("compartion_type" =>"equal","field_name" =>"Parent_Marriage"),
			"Only_Children" 	=> array("compartion_type" =>"equal","field_name" =>"Only_Children"),
			"Task_Title" 		=> array("compartion_type" =>"equal","field_name" =>"Task_Title"),
	);	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="BizDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
	
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
