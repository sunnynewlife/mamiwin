<?php
LunaLoader::import("luna_lib.util.LunaPdo");
require_once(dirname(__FILE__).'/../config/ConfTask.php');
class ModUserBasicInfo {
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}

	public function addUserBasicInfo($UserIDX,$Parent_Gender,$Parent_Marriage,$Child_Gender,$Child_Birthday){
		$params=array();
		$sql="	INSERT INTO User_BasicInfo (UserIDX,Parent_Gender,Parent_Marriage,Child_Gender,Child_Birthday) values(?,?,?,?,?)";
		$params = array($UserIDX,$Parent_Gender,$Parent_Marriage,$Child_Gender,$Child_Birthday);	
		$rowCount=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;
	}

	/**
	 * 查询用户填写的基础资料
	 * @param  [type] $UserIDX [description]
	 * @return [type]          [description]
	 */
	public function queryUserBasicInfo($UserIDX){
		$sql="select * from User_BasicInfo where UserIDX=? ";
		$params=array($UserIDX);
		$user_info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		return (isset($user_info) && is_array($user_info) && count($user_info)>0)?$user_info:array();
	}
}	
?>	