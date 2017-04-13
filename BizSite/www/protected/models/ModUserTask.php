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
	 * @param  [type]  $User_IDX [description]
	 * @param  integer $pagesize [description]
	 * @return [type]            [description]
	 */
	public function getUserTaskList($User_IDX,$pagesize = 10 ){
		$pagesize = (is_null($pagesize)) ? 10 : $pagesize;
		$offSet = (is_null($offSet)) ? 0 : $offSet;
			
		$params=array();
		$sql=" SELECT * from User_Tasks	where User_IDX = ? ";
		// if(!empty($Question_Set_IDX) ){
		// 	$sql .= " AND Question_Set_IDX = ? ";
		// 	$params[] = $Question_Set_IDX;			
		// }
		$sql .= " order by IDX asc ;" ;
		$sql .= " limit $pagesize " ; 
		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return $list;
		}
		return array();
	}

	//为用户分配任务
	public function generateUserTask($User_IDX,$Task_IDX){
		$sql = " INSERT INTO User_Tasks(User_IDX,Task_IDX) VALUES(?,?) ";
		$params = array($User_IDX,$Task_IDX);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}

	// 获取要做的下一个任务
	public function getNextTask($User_IDX){
		$sql="SELECT a.User_IDX,a.Task_IDX,b.Task_Title,b.Matrial_IDX,c.File_Title,c.File_Type,c.Location_Type,c.Mime_Type,c.Original_Name,c.File_Size,c.Download_Id,c.File_Content FROM User_Tasks a ,Task_Material b ,Material_Files c  where a.Task_IDX = b.IDX and b.Matrial_IDX = c.IDX AND a.User_IDX=? AND a.Finish_Status = 0  order by a.IDX  limit 1 ";
		$params=array($User_IDX);
		$ret=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		return (isset($ret) && is_array($ret) && count($ret)>0)?$ret[0]:array();
	}

	//开始任务
	public function startUserTask($User_IDX,$Task_IDX){
		$sql="update User_Tasks set Assign_Date = now() ,Finish_Status=?,Update_Time=now() where User_IDX = ? and Task_IDX = ?";
		$params=array($User_IDX,$Task_IDX);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}	

	// 结束任务
	public function finishUserTask($User_IDX,$Task_IDX){
		$sql="update User_Tasks set Finish_Date = now() ,Finish_Status=?,Update_Time=now() where User_IDX = ? and Task_IDX = ?";
		$params=array($User_IDX,$Task_IDX);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}	


	/**
	 * 修改用户任务信息，评价
	 * @param  [type] $User_IDX        [description]
	 * @param  [type] $Task_IDX        [description]
	 * @param  [type] $Finish_Status   [description]
	 * @param  [type] $Finish_Date     [description]
	 * @param  [type] $Finish_Score    [description]
	 * @param  [type] $Finish_Pic      [description]
	 * @param  [type] $Finish_Document [description]
	 * @return [type]                  [description]
	 */
	public function updateUserTask($User_IDX,$Task_IDX,$Finish_Status,$Finish_Date,$Finish_Score,$Finish_Pic,$Finish_Document){
		$sql="update User_Tasks set UpdateTime=NOW() " ;
		$params = array();
		if(!empty($Finish_Status)){
			$sql .= " ,Finish_Status = ?  ";
			$params['Finish_Status'] = $$Finish_Status;
		}
		if(!empty($Finish_Date)){
			$sql .= " ,Finish_Date = ?  ";
			$params['Finish_Date'] = $Finish_Date;
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
		$sql .= "where  LoginName=? and AcctSource=?";
		$params=array($loginPwd,$loginName,$acctSource);
		return (LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params)>0);

	}
}	


?>