<?php
LunaLoader::import("luna_lib.util.LunaPdo");
require_once(dirname(__FILE__).'/../config/ConfTask.php');


class ModEvaluationQuesitonsSet {
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}

	/**
	 * 查询测评题集列表
	 * @param  string  $File_Type [description]
	 * @param  integer $pagesize  [description]
	 * @param  integer $offSet    [description]
	 * @return [type]             [description]
	 */
	public function getEvaluationQuesitonsSetList($IDX = '',$Conditon_Child_Gender = 1 ,$Condition_Parent_Gender = 1 ,$Condition_Parent_Marriage = 	1 , $Condition_Age = 0 , $Condition_Only_Children = 1 ,$Is_Random = 0 ,$pagesize = 10 ,$offSet = 0 ){
		$pagesize = (is_null($pagesize)) ? 10 : $pagesize;
		$offSet = (is_null($offSet)) ? 0 : $offSet;
		
		$params=array();
		$sql =" SELECT * from Evaluation_Questions_Set where 1 = 1 ";
		if(!empty($IDX) ){
			$sql .= " AND IDX = ? ";
			$params[] = $IDX;			
		}else{
			if(($Conditon_Child_Gender != 1 ) ){
				$sql .= " AND Conditon_Child_Gender = ? ";
				$params[] = $Conditon_Child_Gender;			
			}
			if(($Condition_Parent_Gender != 1 ) ){
				$sql .= " AND Condition_Parent_Gender = ? ";
				$params[] = $Condition_Parent_Gender;			
			}
			if(($Condition_Parent_Marriage != 1 ) ){
				$sql .= " AND Condition_Parent_Marriage = ? ";
				$params[] = $Condition_Parent_Marriage;			
			}
			if(($Condition_Age > 0  ) ){
				$sql .= " AND $Condition_Age between  Condition_Min_Age AND Condition_Max_age ";
			}
			if(($Condition_Only_Children != 1 ) ){
				$sql .= " AND Condition_Only_Children = ? ";
				$params[] = $Condition_Only_Children;			
			}
		}
			
		$sql .= " order by IDX asc ;" ;
		$sql .= " limit $offSet,$pagesize " ; 
		if($Is_Random){
			$sql = " SELECT * FROM Evaluation_Questions_Set WHERE 1 = 1 ORDER BY RAND() LIMIT 1 ;" ;
			$params = array();
		}
		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return $list;
		}
		return array();
	}

	/**
	 * 查询题集列表，及用户答题情况
	 * @param  [type] $UserIDX [description]
	 * @return [type]          [description]
	 */
	public  function getUserEvaluationQuesitonsSetList($UserIDX){
		
		$params=array();
		$sql =" SELECT * FROM Evaluation_Questions_Set a LEFT JOIN (SELECT DISTINCT Question_Set_IDX,COUNT(Question_IDX)  AS Counts FROM User_Evaluation_Questions WHERE UserIDX = ?  AND Option_Chose IS NOT NULL GROUP BY Question_Set_IDX) b 
ON a.IDX = b.Question_Set_IDX  ";
		$params[] = $UserIDX; 
		$sql .= " order by IDX asc ;" ;

		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return $list;
		}
		return array();
	}



	public function getEvaluationQuesitonsSet($IDX){
		$params=array();
		$sql="select * from Evaluation_Questions_Set where IDX = ? ";
		$params = array_merge($params,array($IDX));	
		// if($del_flag === 1 || $del_flag === 0 ){
		// 	$sql .= " AND del_flag = ? ";
		// 	$params[] = $del_flag;
		// 	// $params = array_merge($params,array($del_flag));			
		// }
		$sql .= " order by IDX asc ;" ;
		
		$data=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($data) && is_array($data) && count($data)>0){
			return $data[0];
		}
		return array();
	}
	


}	
?>