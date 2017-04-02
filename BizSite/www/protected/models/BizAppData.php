<?php
LunaLoader::import("luna_lib.util.LunaPdo");
class BizAppData
{
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}
	
	public function	getUserInfoByLoginName($loginName,$acctSource)
	{
		$sql="select * from User_Info where LoginName=? and AcctSource=?";
		$params=array($loginName,$acctSource);
		$user_info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		return (isset($user_info) && is_array($user_info) && count($user_info)>0)?$user_info:array();
	} 
	
	public function registUserInfo($loginName,$acctSource,$loginPwd)
	{
		$sql="insert into User_Info (LoginName,AcctSource,LoginPwd,AcctStatus,CreateTime,UpdateTime) values (?,?,?,?,NOW(),NOW())";
		$params=array($loginName,$acctSource,$loginPwd,BizDataDictionary::User_AcctStatus_Valid);
		return (LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params)>0);
	}
	
	public function registThirdUserInfo($openId,$acctSource)
	{
		$sql="insert into User_Info (LoginName,AcctSource,OpenId,AcctStatus,CreateTime,UpdateTime) values (?,?,?,?,NOW(),NOW())";
		$params=array($openId,$acctSource,$openId,BizDataDictionary::User_AcctStatus_Valid);
		return (LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params)>0);		
	}

	public function  getMaterialInfoByDownloadId($downloadId,$Return_File_Content=false)
	{
		$sql="select File_Title,File_Type,Mime_Type,Original_Name,File_Size from Material_Files where Download_Id=?";
		if($Return_File_Content){
			$sql="select File_Title,File_Type,Mime_Type,Original_Name,File_Size,File_Content from Material_Files where Download_Id=?";
		}
		$params=array($downloadId);
		$Material_info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		return (isset($Material_info) && is_array($Material_info) && count($Material_info)>0)?$Material_info:array();
	}
}
