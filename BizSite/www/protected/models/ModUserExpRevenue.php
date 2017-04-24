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

	public function queryUserExperience($UserIDX,$Config_Key,$Query_Date){
		$params=array();
		$sql=" SELECT * from User_Exp_Revenue	where UserIDX = ? ";
		$params[] = $UserIDX;			
		if(!empty($Config_Key) ){
			$sql .= " AND Config_Key = ? ";
			$params[] = $Config_Key;			
		}
		if(!empty($Query_Date) ){
			$sql .= " AND YEAR(Create_Time) = YEAR(NOW()) AND MONTH(Create_Time) = MONTH(NOW()) AND DAY(Create_Time) = DAY(NOW()) ";				
		}
		$sql .= " order by IDX asc ;" ;
		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return $list;
		}
		return array();
	}

	/**
	 * 记录用户经验值增加记录，同时更新用户表经验值
	 * @param [type] $UserIDX [description]
	 * @param [type] $DWType  [description]
	 * @param [type] $Amount  [description]
	 * @param [type] $DWMemo  [description]
	 */
	public function recordUserExpRevenue($UserIDX,$Config_Key,$DWType,$Amount,$DWMemo){
		$params=array();
		$sql="	INSERT INTO User_Exp_Revenue (UserIDX,Config_Key,DWType,Amount,DWMemo,Create_Time,Update_TIme) values(?,?,?,?,?,now(),now())";
		$params = array($UserIDX,$Config_Key,$DWType,$Amount,$DWMemo);	
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