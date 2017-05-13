<?php
LunaLoader::import("luna_lib.util.LunaPdo");
require_once(dirname(__FILE__).'/../config/ConfTask.php');

// 用户评测结果表 User_Evaluation_Result
class ModUserEvaluationResult {
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}


	public function getUserEvaluationResultList($UserIDX,$Question_Set_IDX){
		$params=array();
		$sql=" SELECT * from User_Evaluation_Result	where UserIDX = ? and Question_Set_IDX = ?";
		$params[] = $UserIDX;
		$params[] = $Question_Set_IDX;
		$sql .= " order by IDX asc ;" ;
		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return $list;
		}
		return array();
	}

}	