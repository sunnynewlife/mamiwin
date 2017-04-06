<?php
LunaLoader::import("luna_lib.util.LunaPdo");
require_once(dirname(__FILE__).'/../config/ConfTask.php');
//用户表
class ModUserInfo {
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}

	//用户登录
	public function	getUserInfoByLoginName($loginName,$acctSource)
	{
		$sql="select * from User_Info where LoginName=? and AcctSource=?";
		$params=array($loginName,$acctSource);
		$user_info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		return (isset($user_info) && is_array($user_info) && count($user_info)>0)?$user_info:array();
	} 
	//用户注册
	public function registUserInfo($loginName,$acctSource,$loginPwd)
	{
		$sql="insert into User_Info (LoginName,AcctSource,LoginPwd,AcctStatus,CreateTime,UpdateTime) values (?,?,?,?,NOW(),NOW())";
		$params=array($loginName,$acctSource,$loginPwd,BizDataDictionary::User_AcctStatus_Valid);
		return (LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params)>0);
	}

}	


?>