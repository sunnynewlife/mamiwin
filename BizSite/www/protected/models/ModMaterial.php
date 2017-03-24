<?php
LunaLoader::import("luna_lib.util.LunaPdo");
require_once(dirname(__FILE__).'/../config/ConfTask.php');
class ModMaterial {
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}

	public function getMeterialList($File_Type = '',$pagesize = 10 ,$offSet = 0 ){
		$pagesize = (is_null($pagesize)) ? 10 : $pagesize;
		$offSet = (is_null($offSet)) ? 0 : $offSet;
		
		$params=array();
		$sql="select IDX,File_Title,File_Type,Location_Type,Mime_Type,Original_Name,File_Size,Download_Id,File_Content,Create_time,Update_time from material_files	where 1 = 1 ";
		if(!empty($File_Type) ){
			$sql .= " AND File_Type = ? ";
			$params[] = $File_Type;
			// $params = array_merge($params,array($del_flag));			
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
	 * 查询单条任务详情
	 * @param  [type] $task_id [description]
	 * @return [type]          [description]
	 */
	public function getMeterialDetail($IDX){
		$params=array();
		$sql="select IDX,File_Title,File_Type,Location_Type,Mime_Type,Original_Name,File_Size,Download_Id,File_Content,Create_time,Update_time from material_files where IDX = ? ";
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