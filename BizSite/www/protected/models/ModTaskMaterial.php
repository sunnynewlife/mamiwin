<?php
LunaLoader::import("luna_lib.util.LunaPdo");
require_once(dirname(__FILE__).'/../config/ConfTask.php');
// 任务表 Task_Material
// IDX                  int not null auto_increment,
// Task_Type            int not null comment '任务类型   1 学习任务   2 陪伴任务',
// Task_Title           varchar(100) not null 任务标题,
// Task_Status          int 任务发布状态,
// Min_Time             int not null comment 任务最小完成时间 '单位: 分钟' ,
// Max_Time             int not null 任务最大完成时间 ,
// Matrial_IDX          int,
// Min_Age              int not null comment '单位：岁',
// Max_Age              int not null,
// Child_Gender         int not null comment 孩子性别 ：'1 不限制       2 女孩       3 男孩',
// Parent_Gender        int not null default 0 comment 父母性别 '1 不限制            2 母亲       3 父亲',
// Parent_Marriage      int not null default 0 comment 父母婚姻状态 '1 不限          2 单亲', 
// Only_Children        int not null default 0 comment 是否独生 '1 不限        2 独生小孩',

class ModTaskMaterial {
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}

	/**
	 * 查询任务列表
	 * @param  string  $File_Type [description]
	 * @param  integer $pagesize  [description]
	 * @param  integer $offSet    [description]
	 * @return [type]             [description]
	 */
	public function getTaskMeterialList($File_Type = '',$pagesize = 10 ,$offSet = 0 ){
		$pagesize = (is_null($pagesize)) ? 10 : $pagesize;
		$offSet = (is_null($offSet)) ? 0 : $offSet;
		
		$params=array();
		$sql=" SELECT IDX,Task_Type,Task_Title,Task_Status,Min_Time,Max_Time,Matrial_IDX,Min_Age,Max_Age,Child_Gender,Parent_Gender,Parent_Marriage,Only_Children,Create_time,Update_time from Task_Material	where 1 = 1 ";
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
	 * 查询单条任务详情
	 * @param  [type] $task_id [description]
	 * @return [type]          [description]
	 */
	public function getTaskMeterialDetail($IDX){
		$params=array();
		$sql="SELECT a.*,b.File_Title,b.File_Type,b.Location_Type,b.Original_Name,b.Download_Id,b.File_Content,b.Is_Show_Index from Task_Material a, Material_Files b  where b.Matrial_IDX = a.IDX and  a.IDX = ? ";
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