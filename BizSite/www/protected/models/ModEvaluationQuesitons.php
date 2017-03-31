<?php
LunaLoader::import("luna_lib.util.LunaPdo");
require_once(dirname(__FILE__).'/../config/ConfTask.php');
/*
   IDX                  int not null auto_increment,
   Question_Stems       varchar(500),
   Ability_Type_ID      int,
   Option_A             varchar(500),
   Option_B             varchar(500),
   Option_C             varchar(500),
   Option_D             varchar(500),
   Point_A              int default 3,
   Point_B              int default 2,
   Point_C              int default 1,
   Point_D              int default 0,
   Create_Time          timestamp default CURRENT_TIMESTAMP,
   Update_Time          timestamp default CURRENT_TIMESTAMP,
 */

class ModEvaluationQuesitons {
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
	public function getEvaluationQuesitonsList($File_Type = '',$pagesize = 10 ,$offSet = 0 ){
		$pagesize = (is_null($pagesize)) ? 10 : $pagesize;
		$offSet = (is_null($offSet)) ? 0 : $offSet;
		
		$params=array();
		$sql=" SELECT IDX,Question_Stems,Ability_Type_ID,Option_A,Option_B,Option_C,Option_D,Point_A,Point_B,Point_C,Point_D,Create_time,Update_time from Evaluation_Quesitons	where 1 = 1 ";
		if(!empty($Task_Type) ){
			$sql .= " AND Task_Type = ? ";
			$params[] = $Task_Type;			
		}
		$sql .= " order by IDX asc ;" ;
		$sql .= " limit $offSet,$pagesize " ; 
		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return $list;
		}
		return array();
	}

	/**
	 * 查询单条测评题详情
	 * @param  [type] $task_id [description]
	 * @return [type]          [description]
	 */
	public function getEvaluationQuesitons($IDX){
		$params=array();
		$sql="SELECT IDX,Question_Stems,Ability_Type_ID,Option_A,Option_B,Option_C,Option_D,Point_A,Point_B,Point_C,Point_D,Create_time,Update_time from Evaluation_Quesitons where IDX = ? ";
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