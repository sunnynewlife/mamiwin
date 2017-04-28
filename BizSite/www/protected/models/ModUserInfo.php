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

	//查询用户信息
	public function queryUserInfo($UserIDX){
		$sql="select * from User_Info where UserIDX=? ";
		$params=array($UserIDX);
		$user_info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		return (isset($user_info) && is_array($user_info) && count($user_info)>0)?$user_info[0]:array();
	}


	/**
	 * 绑定第三方账号，绑定系统手机账号
	 * @param  [type] $openId     [description]
	 * @param  [type] $acctSource [description]
	 * @return [type]             [description]
	 */
	public function bindThirdUserInfo($UserIDX,$openId,$acctSource)
	{
		// $sql="insert into User_Info (LoginName,AcctSource,OpenId,AcctStatus,CreateTime,UpdateTime) values (?,?,?,?,NOW(),NOW())";
		$sql = "UPDATE User_Info SET OpenId = ? ,AcctSource = ?,Update_Time = NOW()  WHERE IDX = ? ";		
		$params=array($openId,$acctSource,$UserIDX);
		return (LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params)>0);		
	}
}	


?>