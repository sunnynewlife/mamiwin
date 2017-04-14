<?php
LunaLoader::import("luna_lib.util.LunaPdo");
require_once(dirname(__FILE__).'/../config/ConfTask.php');

// 用户任务表 User_Tasks
//
class ModUserTask {
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}

	/**
	 * 获取用户任务
	 * @param  [type]  $UserIDX [description]
	 * @param  integer $pagesize [description]
	 * @return [type]            [description]
	 */
	public function getUserTaskList($UserIDX,$Task_Type = 0  ,$page_size = 10 ,$offSet = 0){
		$page_size = (is_null($page_size)) ? 10 : $page_size;
		$offSet = (is_null($offSet)) ? 0 : $offSet;
			
		$params=array();
		$sql=" SELECT a.*,b.Task_Type,b.Task_Title from User_Tasks a,Task_Material b where  a.Task_IDX = b.IDX and  a.UserIDX = ? ";
		$params[] = $UserIDX;			
		if(!empty($Task_Type) ){
			$sql .= " AND b.Task_Type = ? ";
			$params[] = $Task_Type;			
		}
		$sql .= " order by a.IDX asc ;" ;
		$sql .= " limit $offSet,$page_size " ; 
		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return $list;
		}
		return array();
	}

	//为用户分配任务
	public function generateUserTask($UserIDX,$Task_IDX){
		$sql = " INSERT INTO User_Tasks(UserIDX,Task_IDX) VALUES(?,?) ";
		$params = array($UserIDX,$Task_IDX);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}

	// 获取要做的下一个任务
	public function getNextTask($UserIDX){
		$sql="SELECT a.UserIDX,a.Task_IDX,b.Task_Title,b.Matrial_IDX,c.File_Title,c.File_Type,c.Location_Type,c.Mime_Type,c.Original_Name,c.File_Size,c.Download_Id,c.File_Content FROM User_Tasks a ,Task_Material b ,Material_Files c  where a.Task_IDX = b.IDX and b.Matrial_IDX = c.IDX AND a.UserIDX=? AND a.Finish_Status = 0  order by a.IDX  limit 1 ";
		$params=array($UserIDX);
		$ret=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		return (isset($ret) && is_array($ret) && count($ret)>0)?$ret[0]:array();
	}

	//开始任务
	public function startUserTask($UserIDX,$Task_IDX){
		$sql="update User_Tasks set Assign_Date = now() ,Finish_Status=?,Update_Time=now() where UserIDX = ? and Task_IDX = ?";
		$params=array($UserIDX,$Task_IDX);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}	

	// 结束任务
	public function finishUserTask($UserIDX,$Task_IDX){
		$sql="update User_Tasks set Finish_Date = now() ,Finish_Status=?,Update_Time=now() where UserIDX = ? and Task_IDX = ?";
		$params=array($UserIDX,$Task_IDX);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}	


	/**
	 * 修改用户任务信息，评价
	 * @param  [type] $UserIDX        [description]
	 * @param  [type] $Task_IDX        [description]
	 * @param  [type] $Finish_Status   [description]
	 * @param  [type] $Finish_Date     [description]
	 * @param  [type] $Finish_Score    [description]
	 * @param  [type] $Finish_Pic      [description]
	 * @param  [type] $Finish_Document [description]
	 * @return [type]                  [description]
	 */
	public function updateUserTask($UserIDX,$Task_IDX,$Finish_Status,$Finish_Date,$Finish_Score,$Finish_Pic,$Finish_Document){
		$sql="update User_Tasks set UpdateTime=NOW() " ;
		$params = array();
		if(!empty($Finish_Status)){
			$sql .= " ,Finish_Status = ?  ";
			$params['Finish_Status'] = $$Finish_Status;
			if(($Finish_Status == DictionaryData::User_Task_Status_Finish)){
				$sql .= " ,Finish_Date = NOW()  ";				
			}
		}			
		if(!empty($Finish_Score)){
			$sql .= " ,Finish_Score = ?  ";
			$params['Finish_Score'] = $Finish_Score;
		}
		if(!empty($Finish_Pic)){
			$sql .= " ,Finish_Pic = ?  ";
			$params['Finish_Pic'] = $Finish_Pic;
		}
		if(!empty($Finish_Document)){
			$sql .= " ,Finish_Document = ?  ";
			$params['Finish_Document'] = $Finish_Document;
		}
		$sql .= "where  UserIDX=? and Task_IDX=?";
		$params=array($UserIDX,$Task_IDX);
		return (LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params)>0);

	}
}	


?>