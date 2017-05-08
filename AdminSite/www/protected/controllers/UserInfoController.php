<?php
LunaLoader::import("luna_lib.util.CGuidManager");

class UserInfoController extends TableMagtController 
{
	private $_tableName="User_Info";
	private $_searchName="";
	private $_next_url="/userInfo/index";
	private $_columns=array("IDX","LoginName","AcctSource","OpenId","Exp_Points","Create_Time","Update_Time");
	private $_title="用户信息";
	private $_primaryKey="IDX";
	
	protected $_EXTRA_SEARCH_FIELDS=array(
		"LoginName" 	=> array("compartion_type" =>"like","field_name" =>"LoginName"),
	);
	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="BizDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
	}
	
	public function actionIndex()
	{	
		$this->_actionIndex("User_Info", $this->_searchName, $this->_next_url, $this->_tableName,"index");	
	}

}
