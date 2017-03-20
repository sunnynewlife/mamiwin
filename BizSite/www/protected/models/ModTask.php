<?php
LunaLoader::import("luna_lib.util.LunaPdo");
require_once(dirname(__FILE__).'/../config/ConfTask.php');
class ModTask {
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}

	public function getTaskList($del_flag = '',$type = 0 ,$content_type = 0 ,$pagesize = 10 ,$offSet = 0 ){
		$pagesize = (is_null($pagesize)) ? 10 : $pagesize;
		$offSet = (is_null($offSet)) ? 0 : $offSet;
		
		$params=array();
		$sql="select 
				id,task_type,content_type,title,url,min_duration,max_duration,min_age,max_age,child_sex,parent_sex,marriage,is_along,task_attribute,del_flag,create_time,update_time
			from tbl_task
			where 1 = 1 ";
		if($del_flag === 1 || $del_flag === 0 ){
			$sql .= " AND del_flag = ? ";
			$params[] = $del_flag;
			// $params = array_merge($params,array($del_flag));			
		}
		$sql .= " order by id asc ;" ;
		$sql .= " limit $offSet,$pagesize " ; 
		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return $list;
		}
		return array();
	}

	/**
	 * 查询单条任务详情
	 * @param  [type] $task_id [description]
	 * @return [type]          [description]
	 */
	public function getTaskDetail($task_id){
		$params=array();
		$sql="select 
				id,task_type,content_type,title,url,min_duration,max_duration,min_age,max_age,child_sex,parent_sex,marriage,is_along,task_attribute,del_flag,create_time,update_time
			from tbl_task
			where id = ? ";
		$params = array_merge($params,array($task_id));	
		// if($del_flag === 1 || $del_flag === 0 ){
		// 	$sql .= " AND del_flag = ? ";
		// 	$params[] = $del_flag;
		// 	// $params = array_merge($params,array($del_flag));			
		// }
		$sql .= " order by id asc ;" ;
		
		$data=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($data) && is_array($data) && count($data)>0){
			return $data[0];
		}
		return false;
	}

}	


?>