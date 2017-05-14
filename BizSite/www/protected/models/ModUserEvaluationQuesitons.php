<?php
LunaLoader::import("luna_lib.util.LunaPdo");
require_once(dirname(__FILE__).'/../config/ConfTask.php');

// 用户评测题表 User_Evaluation_Questions
// UserIDX
// Question_Set_IDX
// Question_IDX
// Option
// Point
// Status   答题状态 0：未答题；1：已答；9：跳过
class ModUserEvaluationQuesitons {
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}

	/**
	 * 查询测评题列表
	 * @param  string  $File_Type [description]
	 * @param  integer $pagesize  [description]
	 * @param  integer $offSet    [description]
	 * @return [type]             [description]
	 */
	public function getEvaluationQuesitonsList($UserIDX,$pagesize = 10 ,$offSet = 0 ){
		$pagesize = (is_null($pagesize)) ? 10 : $pagesize;
		$offSet = (is_null($offSet)) ? 0 : $offSet;
		
		$params=array();
		$sql=" SELECT * from User_Evaluation_Questions	where UserIDX = ? ";
		// if(!empty($Question_Set_IDX) ){
		// 	$sql .= " AND Question_Set_IDX = ? ";
		// 	$params[] = $Question_Set_IDX;			
		// }
		$params[] = $UserIDX;
		$sql .= " order by IDX asc ;" ;
		$sql .= " limit $offSet,$pagesize " ; 
		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return $list;
		}
		return array();
	}

	//为用户生成评测题
	public function generateUserQuestion($UserIDX,$Question_Set_IDX){
		$sql = " INSERT INTO User_Evaluation_Questions(UserIDX,Question_Set_IDX,Question_IDX,Ability_Type_ID,Status) SELECT $UserIDX,$Question_Set_IDX,IDX,Ability_Type_ID,0 FROM Evaluation_Questions WHERE Question_Set_IDX = ? ";
		$params = array($Question_Set_IDX);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}

	// 获取要做的下一题
	public function getNextQuestion($UserIDX,$Question_Set_IDX){
		$sql="SELECT a.UserIDX,a.Question_Set_IDX,a.Question_IDX,b.Question_Stems,b.Option_A,b.Option_B,b.Option_C,b.Option_D,b.Option_E,b.Option_F,c.Set_Qty from User_Evaluation_Questions a ,Evaluation_Questions b,Evaluation_Questions_Set c where a.Question_IDX = b.IDX and a.Question_Set_IDX = c.IDX  AND a.Question_Set_IDX=? AND a.UserIDX=? AND a.status = 0  order by a.IDX  limit 1 ";
		$params=array($Question_Set_IDX,$UserIDX);
		$ret=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		return (isset($ret) && is_array($ret) && count($ret)>0)?$ret[0]:array();
	}

	public function getUserEvaluationQuesitonsList($UserIDX,$Question_Set_IDX,$Question_Answer_Status  ){
		$params=array();
		$sql=" SELECT * from User_Evaluation_Questions	where UserIDX = ? and Question_Set_IDX = ?";
		// if(!empty($Question_Set_IDX) ){
		// 	$sql .= " AND Question_Set_IDX = ? ";
		// 	$params[] = $Question_Set_IDX;			
		// }
		$params[] = $UserIDX;
		$params[] = $Question_Set_IDX;
		if($Question_Answer_Status === 0 ||$Question_Answer_Status === 1){
			$sql .= " AND  status = ?";
			$params[] = $Question_Answer_Status;
		}
		$sql .= " order by IDX asc ;" ;
		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return $list;
		}
		return array();
	}



	/**
	 * 提交评测题答案
	 * @param  [type] $UserIDX     [description]
	 * @param  [type] $Question_IDX [description]
	 * @param  [type] $Point        [description]
	 * @return [type]               [description]
	 */
	public function recordUserQuestionResult($UserIDX,$Question_IDX,$Option,$Point){
		$sql="update User_Evaluation_Questions set Option_Chose=?,Point=?,Status=1,Update_Time=now() where UserIDX=? and Question_IDX = ?";
		$params=array($Option,$Point,$UserIDX,$Question_IDX);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}

	/**
	 * 计算用户整套评测题得分，并以此分配任务 
	 * @return [type] [description]
	 */
	public function recordUserEvaluationResult($UserIDX,$Question_Set_IDX){
		$sql = "INSERT INTO User_Evaluation_Result(UserIDX,Set_IDX,Ability_Type_IDX,Ability_Score) SELECT $UserIDX,$Question_Set_IDX,Ability_Type_ID,SUM(POINT) AS Ability_Score FROM user_evaluation_questions WHERE UserIDX = ? AND Question_Set_IDX = ? GROUP BY Ability_Type_ID" ;
		$params=array($UserIDX,$Question_Set_IDX);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);

	}



	
}	
?>