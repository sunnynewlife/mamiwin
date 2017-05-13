<?php
LunaLoader::import("luna_lib.util.LunaPdo");
require_once(dirname(__FILE__).'/../config/ConfTask.php');
class ModUserBasicInfo {
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}

	public function addUserBasicInfo($UserIDX,$Parent_Gender,$Parent_Marriage,$Child_Gender,$Child_Birthday,$Avatar){
		$params=array();
		$sql="	INSERT INTO User_BasicInfo (UserIDX,Parent_Gender,Parent_Marriage,Child_Gender,Child_Birthday,Avatar) values(?,?,?,?,?,?)";
		$params = array($UserIDX,$Parent_Gender,$Parent_Marriage,$Child_Gender,$Child_Birthday,$Avatar);	
		$rowCount=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;
	}

	public function updateUserBasicInfo($UserIDX,$Parent_Gender,$Parent_Marriage,$Child_Gender,$Child_Birthday,$Avatar){
		$sql = "UPDATE User_Info SET   ";	
		$params=array();
		if(!empyt($Parent_Gender)){
			$sql .= " Parent_Gender = ? " ;
			$params = array_merge($params,array($Parent_Gender));
		}
		if(!empyt($Parent_Marriage)){
			$sql .= " Parent_Marriage = ? " ;
			$params = array_merge($params,array($Parent_Marriage));
		}
		if(!empyt($Child_Gender)){
			$sql .= " Child_Gender = ? " ;
			$params = array_merge($params,array($Child_Gender));
		}
		if(!empyt($Child_Birthday)){
			$sql .= " Child_Birthday = ? " ;
			$params = array_merge($params,array($Child_Birthday));
		}
		if(!empyt($Avatar)){
			$sql .= " Avatar = ? " ;
			$params = array_merge($params,array($Avatar));
		}
		
		$sql .= " ,Update_Time = NOW()  WHERE UserIDX = ?" ;
		$params = array_merge($params,array($UserIDX));
		return (LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params)>0);		
	
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