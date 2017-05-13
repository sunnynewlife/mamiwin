<?php
LunaLoader::import("luna_lib.util.LunaPdo");
// 系统前端用户
class 	UserInfoData
{
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}

	public function newUserInfo($LoginName,$LoginPwd)
	{
		$AcctSource = "Fumuwin";
		$AcctStatus = 1 ;
		$Sign_From = 9 ;	//来自后台增加
		$sql="insert into User_Info(LoginName,LoginPwd,AcctSource,AcctStatus,Sign_From) values (?,?,?,?,?)";
		$params=array($LoginName,$LoginPwd,$AcctSource,$AcctStatus,$Sign_From);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}

	public function  queryUserInfo($LoginName)
	{
		$sql="select * from User_Info where LoginName =?";
		$params=array($LoginName);
		$Material_info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		return (isset($Material_info) && is_array($Material_info) && count($Material_info)>0)?$Material_info:array();
	}
	
	

}
