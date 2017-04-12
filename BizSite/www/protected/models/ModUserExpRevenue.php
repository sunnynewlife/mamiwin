<?php
LunaLoader::import("luna_lib.util.LunaPdo");
require_once(dirname(__FILE__).'/../config/ConfTask.php');
// 用户经验值日志 User_Exp_Revenue
class ModUserExpRevenue {
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}

	/**
	 * 记录用户经验值增加记录，同时更新用户表经验值
	 * @param [type] $UserIDX [description]
	 * @param [type] $DWType  [description]
	 * @param [type] $Amount  [description]
	 * @param [type] $DWMemo  [description]
	 */
	public function addUserExpRevenue($UserIDX,$DWType,$Amount,$DWMemo){
		$params=array();
		$sql="	INSERT INTO User_Exp_Revenue (UserIDX,DWType,Amount,DWMemo,Create_Time,Update_TIme) values(?,?,?,?,now(),now())";
		$params = array($UserIDX,$DWType,$Amount,$DWMemo);	
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