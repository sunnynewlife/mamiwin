<?php
LunaLoader::import("luna_lib.util.LunaPdo");
require_once(dirname(__FILE__).'/../config/ConfTask.php');
// 用户分享日志 User_Share
class ModUserShare {
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}

/**
 * 记录分享日志
 * @param [type] $UserIDX    [description]
 * @param [type] $Share_Type [description]
 * @param [type] $Share_IDX  [description]
 * @param [type] $Share_To   [description]
 */
	public function addUserShare($UserIDX,$Share_Type,$Share_IDX,$Share_To,$Amount){
		$params=array();
		$sql="	INSERT INTO User_Share (UserIDX,Share_Type,Share_IDX,Share_To) values(?,?,?,?)";
		$params = array($UserIDX,$Share_Type,$Share_IDX,$Share_To);	
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$pdo->beginTransaction();
		if($pdo->exec_with_prepare($sql,$params)>0){
			$sql_user = "UPDATE User_Info set Exp_Points = Exp_Points  + ? WHERE IDX = ? " ; 
			$params_user = array($Amount,$UserIDX);
			$pdo->exec_with_prepare($sql_user,$params_user);
			$pdo->commit(); 
			return true;
		}else{
			$pdo->rollBack();
		}
		return false;
	}
}	
?>	