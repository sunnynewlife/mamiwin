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
	public function getUserTaskList($UserIDX,$Task_Type = 0 ,$Query_Day = 0  ,$page_size = 10 ,$offSet = 0 ,$Finish_Status = ''){
		$page_size = (is_null($page_size)) ? 10 : $page_size;
		$offSet = (is_null($offSet)) ? 0 : $offSet;
			
		$params=array();
		$sql=" SELECT a.*,b.Task_Type,b.Task_Title,b.Min_Time,b.Max_Time from User_Tasks a,Task_Material b where  a.Task_IDX = b.IDX and  a.UserIDX = ? ";
		$params[] = $UserIDX;			
		if(!empty($Task_Type) ){
			$sql .= " AND b.Task_Type = ? ";
			$params[] = $Task_Type;			
		}
		if(!empty($Query_Day) ){			
			$sql .= " AND YEAR('". $Query_Day ."') = YEAR(a.Finish_Date) " ;
			$sql .= " AND MONTH('". $Query_Day ."') = MONTH(a.Finish_Date) " ;
			$sql .= " AND DAY('". $Query_Day ."') = DAY(a.Finish_Date) " ;
		}
		if(!empty($Finish_Status) ){
			$sql .= " AND a.Finish_Status = ? ";
			$params[] = $Finish_Status;		
		}
		$sql .= " order by a.IDX asc " ;
		$sql .= " limit $offSet,$page_size " ; 
		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return $list;
		}
		return array();
	}

	/**
	 * 查询用户当前任务轮次
	 * @return [type] [description]
	 */
	public function getCurrentUserTaskTurn($UserIDX){
		$sql = "select Turn FROM User_Tasks where UserIDX = ? order by Turn desc limit 1";
		$params[] = $UserIDX;	
		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0 && isset($list[0]['Turn'])){
			return $list[0]['Turn'];
		}
		return 0;

	}

	/**
	 * 按轮次查询用户任务
	 * @param  [type]  $UserIDX   [description]
	 * @param  integer $Task_Type [description]
	 * @param  integer $Turn      [description]
	 * @param  integer $page_size [description]
	 * @param  integer $offSet    [description]
	 * @return [type]             [description]
	 */
	public function getUserTaskListByTrun($UserIDX,$Task_Type = 0 ,$Turn = 0  ,$page_size = 10 ,$offSet = 0){
		$page_size = (is_null($page_size)) ? 10 : $page_size;
		$offSet = (is_null($offSet)) ? 0 : $offSet;
			
		$params=array();
		$sql=" SELECT a.*,b.Task_Type,b.Task_Title,b.Min_Time,b.Max_Time from User_Tasks a,Task_Material b where  a.Task_IDX = b.IDX and  a.UserIDX = ? ";
		$params[] = $UserIDX;			
		if(!empty($Task_Type) ){
			$sql .= " AND b.Task_Type = ? ";
			$params[] = $Task_Type;			
		}
		if(!empty($Turn) ){
			$sql .= " AND Turn = ? ";
			$params[] = $Turn;			
		}else{
			// 默认按最大一轮次查询
			$sql .= " AND Turn = (select Turn FROM User_Tasks where UserIDX = ? order by Turn desc limit 1 ) " ;
			$params[] = $UserIDX;	
		}
		$sql .= " order by a.IDX asc " ;
		$sql .= " limit $offSet,$page_size " ; 
		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return $list;
		}
		return array();
	}

	/**
	 * 查询用户任务详情
	 * @param  [type] $UserIDX  [description]
	 * @param  [type] $Task_IDX [description]
	 * @return [type]           [description]
	 */
	public function getUserTaskDetail($UserIDX,$Task_IDX){
		$params=array();
		$sql=" SELECT a.*,b.Task_Type,b.Task_Title from User_Tasks a,Task_Material b where  a.Task_IDX = b.IDX and  a.UserIDX = ? AND a.Task_IDX = ? ";
		$params[] = $UserIDX;			
		$params[] = $Task_IDX;			
		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return $list[0];
		}		
		return array();
	}

	/**
	 * 按月查询用户任务数，分不同状态
	 * @param  [type] $UserIDX       [description]
	 * @param  [type] $Query_Month   [description]
	 * @param  [type] $Finish_Status [description]
	 * @return [type]                [description]
	 */
	public function queryUserTaskMonth($UserIDX,$Query_Month,$Finish_Status){
		$sql=" SELECT DISTINCT DATE_FORMAT(Finish_Date,'%Y-%m-%d') as Finish_Date ,COUNT(*) AS Task_Qty FROM User_Tasks  where  UserIDX = ? ";

		// $sql .= " AND YEAR('". $Query_Month ."') = YEAR(DATE_FORMAT(Finish_Date,'%Y-%m-%d')) " ;
		// $sql .= " AND MONTH('". $Query_Month ."') = MONTH(DATE_FORMAT(Finish_Date,'%Y-%m-%d')) " ;
		// $sql .= " AND DAY('". $Query_Month ."') = DAY(DATE_FORMAT(Finish_Date,'%Y-%m-%d')) " ;

		$params[] = $UserIDX;				
		if(!empty($Finish_Status) ){
			$sql .= " AND Finish_Status = ? ";
			$params[] = $Finish_Status;			
		}
		$sql .= " GROUP BY DATE_FORMAT(Finish_Date,'%Y-%m-%d') " ;
		
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

	//随机分配任务给用户,具体业务逻辑待定 
	// 如果当天有任务，不管是什么 任务的，当天都不再分配任务了
	public function generateUserTaskRandom($UserIDX,$Task_Type,$Turn,$Count){
		$sql_query = " SELECT * FROM User_Tasks where UserIDX = ? AND TO_DAYS(Assign_Date) = TO_DAYS(NOW()) " ; 
		$params_query = array($UserIDX);
		if(empty($Task_Type) == false){
			$sql_query .= " AND Task_Type = ? " ; 
			$params_query[] = $Task_Type;
		}		
		$list = LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql_query,$params_query,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return 0;
		}

		$sql = " INSERT INTO User_Tasks(UserIDX,Task_IDX,Turn,Task_Type) SELECT ?,IDX,?,Task_Type FROM Task_Material WHERE IDX not in (SELECT Task_IDX FROM User_Tasks)  ";
		$params = array($UserIDX,$Turn);
		if(empty($Task_Type) == false){
			$sql .= " AND Task_Type = ? " ; 
			$params[] = $Task_Type;

		}
		$sql .= "  and Task_Status = 2  " ; 	//只分配公开的任务
		$sql .= " order by RAND() limit  " . $Count . " ";
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}

	/**
	 * 分配任务,根据评测结果
	 * @param  [type] $UserIDX   [description]
	 * @param  [type] $Task_Type [description]
	 * @param  [type] $Turn      [description]
	 * @param  [type] $Count     [description]
	 * @return [type]            [description]
	 */
	public function generateUserTaskForEva($UserIDX,$Task_Type,$Turn,$Count){
		
		$sql = " INSERT INTO User_Tasks(UserIDX,Task_IDX,Turn,Task_Type) SELECT ?,IDX,?,Task_Type FROM Task_Material WHERE IDX not in (SELECT Task_IDX FROM User_Tasks WHERE UserIDX = ?) ";
		$params = array($UserIDX,$Turn,$UserIDX);
		if(empty($Task_Type) == false){
			$sql .= " AND Task_Type = ? " ; 
			$params[] = $Task_Type;

		}
		$sql .= "  and Task_Status = 2  " ; 	//只分配公开的任务
		$sql .= " order by RAND() limit  " . $Count . " ";
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
	//TODO Finish_Date 字段类型有问题，要去掉  on update CURRENT_TIMESTAMP 属性
	public function startUserTask($UserIDX,$Task_IDX){
		$sql="update User_Tasks set Assign_Date = now() ,Finish_Status=?,Finish_Date='2000-01-01' , Update_Time=now() where UserIDX = ? and Task_IDX = ?";
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
		$sql="update User_Tasks set Update_Time=NOW() " ;
		$params = array();
		if(!empty($Finish_Status)){
			$sql .= " ,Finish_Status = ?  ";
			$params[] = $Finish_Status;
			if(($Finish_Status == DictionaryData::User_Task_Status_Start)){
				$sql .= " ,Start_Date = NOW() , Finish_Date = '2000-01-01' ";				
			}else if(($Finish_Status == DictionaryData::User_Task_Status_Finish)){
				$sql .= " ,Finish_Date = NOW()  ";				
			}
		}			
		if(!empty($Finish_Score)){
			$sql .= " ,Finish_Score = ?  ";
			$params[] = $Finish_Score;
		}
		if(!empty($Finish_Pic)){
			$sql .= " ,Finish_Pic = ?  ";
			$params[] = $Finish_Pic;
		}
		if(!empty($Finish_Document)){
			$sql .= " ,Finish_Document = ?  ";
			$params[] = $Finish_Document;
		}
		$sql .= "where  UserIDX=? and Task_IDX = ?";
		$params[] = $UserIDX ;
		$params[] = $Task_IDX ;
		return (LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params)>0);
	}

	/**
	 * 按Task_IDX返回用户任务列表，包括评价
	 * @param  [type] $Task_IDX [description]
	 * @return [type]           [description]
	 */
	public function queryTaskEvaluteList($Task_IDX,$Is_Evalution){
		$params=array();
		$sql=" SELECT a.*,b.Task_Type,b.Task_Title from User_Tasks a,Task_Material b where  a.Task_IDX = b.IDX AND a.Task_IDX = ? ";		
		$params[] = $Task_IDX;	
		if($Is_Evalution){
			$sql .= " AND  a.Finish_Score > 0 " ;		//已评价
		}
		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return $list;
		}		
		return array();
	}

	/**
	 * 查询 任务评价平均分
	 * @param  [type] $Task_IDX [description]
	 * @return [type]           [description]
	 */
	public function queryTaskAvgScore($Task_IDX){
		$params=array();
		$sql=" SELECT Task_IDX,AVG(Finish_Score) AS score  FROM User_Tasks WHERE Task_IDX = ? ";		
		$params[] = $Task_IDX;	
		$list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($list) && is_array($list) && count($list)>0){
			return $list[0];
		}		
		return array();

	}
}	


?>