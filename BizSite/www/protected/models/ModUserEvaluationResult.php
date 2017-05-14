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

	/**
	 * 统计记录用户测评题，属性得分，
	 * @param  [type] $UserIDX          [description]
	 * @param  [type] $Question_Set_IDX [description]
	 * @return [type]                   [description]
	 */
	public function calUserUserEvaluationResult($UserIDX,$Question_Set_IDX,$Turn){
		$sql_del = "DELETE FROM User_Evaluation_Result WHERE  UserIDX = ? and Set_IDX = ? and Turn = ? ; ";
		$params[] = $UserIDX;
		$params[] = $Question_Set_IDX;
		$params[] = $Turn;
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql_del,$params);
		
		$params=array();
		$sql=" INSERT INTO User_Evaluation_Result(UserIDX,Set_IDX,Ability_Type_IDX,Ability_Score,Turn)  SELECT DISTINCT ?,?,Ability_Type_ID,SUM(POINT) AS points,? FROM User_Evaluation_Questions  WHERE  UserIDX = ? and Question_Set_IDX = ? AND POINT > 0 GROUP BY Ability_Type_ID;";
		$params = array($UserIDX,$Question_Set_IDX,$Turn,$UserIDX,$Question_Set_IDX);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
		
	}


	/**
	 * 查询 
	 * @param  [type] $UserIDX          [description]
	 * @param  [type] $Question_Set_IDX [description]
	 * @return [type]                   [description]
	 */
	public function getUserEvaluationResultList($UserIDX,$Question_Set_IDX,$Turn){
		$params=array();
		$sql=" SELECT * from User_Evaluation_Result	where UserIDX = ? and Set_IDX = ? and Turn = ? ";
		$params[] = $UserIDX;
		$params[] = $Question_Set_IDX;
		$params[] = $Turn;
		$sql .= " order by IDX asc ;" ;
		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return $list;
		}
		return array();
	}

}	