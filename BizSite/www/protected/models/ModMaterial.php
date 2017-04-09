<?php
LunaLoader::import("luna_lib.util.LunaPdo");
require_once(dirname(__FILE__).'/../config/ConfTask.php');
/*
	File_Title           varchar(500) not null,
   File_Type            int not null comment  素材类型'1 文本  2 音频   3 视频',
   Location_Type        int comment '1 本站HTML文本   2 本站二进制文件     3 外站点URL',
   Mime_Type            varchar(100),
   Original_Name        varchar(200),
   File_Size            int,
   Download_Id          varchar(100) not null,
   File_Content         longblob,
   Create_Time          timestamp default CURRENT_TIMESTAMP,
   Update_Time          timestamp default CURRENT_TIMESTAMP,

 */
class ModMaterial {
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}

	/**
	 * 查询素材列表
	 * @param  string  $File_Type [description]
	 * @param  integer $pagesize  [description]
	 * @param  integer $offSet    [description]
	 * @return [type]             [description]
	 */
	public function getMeterialList($File_Type = '',$Is_Show_Index = '',$pagesize = 10 ,$offSet = 0 ){
		$pagesize = (is_null($pagesize)) ? 10 : $pagesize;
		$offSet = (is_null($offSet)) ? 0 : $offSet;
		
		$params=array();
		$sql="select IDX,File_Title,File_Type,Location_Type,Mime_Type,Original_Name,File_Size,Download_Id,File_Content,Create_time,Update_time from Material_Files	where 1 = 1 ";
		if(!empty($File_Type) ){
			$sql .= " AND File_Type = ? ";
			$params[] = $File_Type;
			// $params = array_merge($params,array($del_flag));			
		}
		if($Is_Show_Index === 0 || $Is_Show_Index === 1 ){
			$sql .= " AND Is_Show_Index = ? ";
			$params[] = $Is_Show_Index;
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
	 * 查询单条素材详情
	 * @param  [type] $task_id [description]
	 * @return [type]          [description]
	 */
	public function getMeterialDetail($IDX){
		$params=array();
		$sql="select IDX,File_Title,File_Type,Location_Type,Mime_Type,Original_Name,File_Size,Download_Id,File_Content,Create_time,Update_time from Material_Files where IDX = ? ";
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