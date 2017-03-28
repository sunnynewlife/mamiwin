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
}	
?>	