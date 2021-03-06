<?php
LunaLoader::import("luna_lib.util.LunaPdo");
class EvQuestionData
{
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}
	
	public function insertEvQuestion($Question_Set_IDX,$Ability_Type_ID,$Question_Stems,$Option_A,$Option_B,$Option_C,$Option_D,$Option_E,$Option_F,$Point_A,$Point_B,$Point_C,$Point_D,$Point_E,$Point_F,$Order_Index)
	{
		$sql="insert into Evaluation_Questions(Question_Set_IDX,Ability_Type_ID,Question_Stems,Option_A,Option_B,Option_C,Option_D,Option_E,Option_F,Point_A,Point_B,Point_C,Point_D,Point_E,Point_F,Order_Index) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$params=array($Question_Set_IDX,$Ability_Type_ID,$Question_Stems,$Option_A,$Option_B,$Option_C,$Option_D,$Option_E,$Option_F,$Point_A,$Point_B,$Point_C,$Point_D,$Point_E,$Point_F,$Order_Index);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
	public function updateEvQuestion($Question_Set_IDX,$Ability_Type_ID,$Question_Stems,$Option_A,$Option_B,$Option_C,$Option_D,$Option_E,$Option_F,$Point_A,$Point_B,$Point_C,$Point_D,$Point_E,$Point_F,$Order_Index,$IDX)
	{
		$sql="update Evaluation_Questions set Question_Set_IDX=?,Ability_Type_ID=?,Question_Stems=?,Option_A=?,Option_B=?,Option_C=?,Option_D=?,Option_E=?,Option_F=?,Point_A=?,Point_B=?,Point_C=?,Point_D=?,Point_E=?,Point_F=?,Order_Index=?,Update_Time=now() where IDX=?";
		$params=array($Question_Set_IDX,$Ability_Type_ID,$Question_Stems,$Option_A,$Option_B,$Option_C,$Option_D,$Option_E,$Option_F,$Point_A,$Point_B,$Point_C,$Point_D,$Point_E,$Point_F,$Order_Index,$IDX);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}


	public function delEvQuestion($IDX)
	{
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$pdo->beginTransaction();
		$params=array($IDX);		
		$rowCount= $pdo->exec_with_prepare("delete from Evaluation_Questions where IDX=?",$params);
		if($rowCount>0){
			$pdo->commit();
			return $rowCount;
		}
		$pdo->rollBack();
		return 0;
	}
	
	
	public function  getEvQuestionByIDX($IDX)
	{
		$sql="select * from Evaluation_Questions where IDX=?";
		$params=array($IDX);
		$Material_info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		return (isset($Material_info) && is_array($Material_info) && count($Material_info)>0)?$Material_info[0]:array();
	}
	
	
	public function getEvQuestion()
	{
		$sql="select * from  Evaluation_Questions ";
		$params=array();
		$EvQuestion=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		return (isset($EvQuestion) && is_array($EvQuestion) && count($EvQuestion)>0)?$EvQuestion:array();		
	}
	public function getEvQuestionList($Set_IDX)
	{
		$sql="select * from  Evaluation_Questions where Question_Set_IDX = ? ";
		$params=array($Set_IDX);
		$EvQuestion=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		return (isset($EvQuestion) && is_array($EvQuestion) && count($EvQuestion)>0)?$EvQuestion:array();		
	}
	
	// public function insertTask_Material($Task_Type,$Task_Title,$Task_Status,$Min_Time,$Max_Time,$Min_Age,$Max_Age,$Child_Gender,$Parent_Gender,$Parent_Marriage,$Only_Children,$Matrial_IDX,$AbilityIds)
	// {
	// 	if(empty($Matrial_IDX)){
	// 		$sql="insert into Task_Material (Task_Type,Task_Title,Task_Status,Min_Time,Max_Time,Min_Age,Max_Age,Child_Gender,Parent_Gender,Parent_Marriage,Only_Children,Create_Time,Update_Time) values (?,?,?,?,?,?,?,?,?,?,?,now(),now())";
	// 		$params=array($Task_Type,$Task_Title,$Task_Status,$Min_Time,$Max_Time,$Min_Age,$Max_Age,$Child_Gender,$Parent_Gender,$Parent_Marriage,$Only_Children);
	// 	}else{
	// 		$sql="insert into Task_Material (Task_Type,Task_Title,Task_Status,Min_Time,Max_Time,Min_Age,Max_Age,Child_Gender,Parent_Gender,Parent_Marriage,Only_Children,Create_Time,Update_Time,Matrial_IDX) values (?,?,?,?,?,?,?,?,?,?,?,now(),now(),? )";
	// 		$params=array($Task_Type,$Task_Title,$Task_Status,$Min_Time,$Max_Time,$Min_Age,$Max_Age,$Child_Gender,$Parent_Gender,$Parent_Marriage,$Only_Children,$Matrial_IDX);
	// 	}
	// 	$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
	// 	$pdo->beginTransaction();
	// 	if($pdo->exec_with_prepare($sql,$params)>0){
	// 		$Task_IDX=$pdo->lastInsertId();
	// 		foreach ($AbilityIds as $Ability_IDX){
	// 			$sql="insert into Task_Ability (Task_IDX,Ability_IDX,Create_Time,Update_Time) values (?,?,now(),now())";
	// 			$params=array($Task_IDX,$Ability_IDX);
	// 			$pdo->exec_with_prepare($sql,$params);
	// 		}
	// 		$pdo->commit(); 
	// 		return true;
	// 	}else{
	// 		$pdo->rollBack();
	// 	}
	// 	return false;
	// }
	
	// public function updateTask_Material($Task_Type,$Task_Title,$Task_Status,$Min_Time,$Max_Time,$Min_Age,$Max_Age,$Child_Gender,$Parent_Gender,$Parent_Marriage,$Only_Children,$Matrial_IDX,$AbilityIds,$Task_IDX)
	// {
	// 	if(empty($Matrial_IDX)){
	// 		$sql="update Task_Material set Task_Type=?,Task_Title=?,Task_Status=?,Min_Time=?,Max_Time=?,Min_Age=?,Max_Age=?,Child_Gender=?,Parent_Gender=?,Parent_Marriage=?,Only_Children=?,Matrial_IDX=null,Update_Time=now() where IDX=?";
	// 		$params=array($Task_Type,$Task_Title,$Task_Status,$Min_Time,$Max_Time,$Min_Age,$Max_Age,$Child_Gender,$Parent_Gender,$Parent_Marriage,$Only_Children,$Task_IDX);
	// 	}else{
	// 		$sql="update Task_Material set Task_Type=?,Task_Title=?,Task_Status=?,Min_Time=?,Max_Time=?,Min_Age=?,Max_Age=?,Child_Gender=?,Parent_Gender=?,Parent_Marriage=?,Only_Children=?,Matrial_IDX=?,Update_Time=now() where IDX=?";
	// 		$params=array($Task_Type,$Task_Title,$Task_Status,$Min_Time,$Max_Time,$Min_Age,$Max_Age,$Child_Gender,$Parent_Gender,$Parent_Marriage,$Only_Children,$Matrial_IDX,$Task_IDX);
	// 	}
	// 	$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
	// 	$pdo->beginTransaction();
	// 	if($pdo->exec_with_prepare($sql,$params)>0){
	// 		$sql="delete from Task_Ability where Task_IDX=?";
	// 		$params=array($Task_IDX);
	// 		$pdo->exec_with_prepare($sql,$params);
	// 		foreach ($AbilityIds as $Ability_IDX){
	// 			$sql="insert into Task_Ability (Task_IDX,Ability_IDX,Create_Time,Update_Time) values (?,?,now(),now())";
	// 			$params=array($Task_IDX,$Ability_IDX);
	// 			$pdo->exec_with_prepare($sql,$params);
	// 		}
	// 		$pdo->commit();
	// 		return true;
	// 	}else{
	// 		$pdo->rollBack();
	// 	}
	// 	return false;
	// }
	
	// public function getTask_Material($IDX)
	// {
	// 	$sql="select * from  Task_Material where IDX=?";
	// 	$params=array($IDX);
	// 	$Task_Material=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
	// 	return (isset($Task_Material) && is_array($Task_Material) && count($Task_Material)>0)?$Task_Material[0]:array();		
	// }
	
	// public function getTask_Ability($TaskIDX)
	// {
	// 	$sql="select * from  Task_Ability where Task_IDX=?";
	// 	$params=array($TaskIDX);
	// 	$Task_Ability=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
	// 	return (isset($Task_Ability) && is_array($Task_Ability) && count($Task_Ability)>0)?$Task_Ability:array();		
	// }
	
	// public function deleteTask_Material($TaskIDX)
	// {
	// 	$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
	// 	$pdo->beginTransaction();
	// 	$params=array($TaskIDX);
	// 	$pdo->exec_with_prepare("delete from Task_Ability where Task_IDX=?",$params);
	// 	$rowCount=$pdo->exec_with_prepare("delete from Task_Material where IDX=?",$params);
	// 	if($rowCount>0){
	// 		$pdo->commit();
	// 		return $rowCount;
	// 	}
	// 	$pdo->rollBack();
	// 	return 0;
	// }
	
	// public function  getMaterialInfoByDownloadId($downloadId,$Return_File_Content=false)
	// {
	// 	$sql="select File_Title,File_Type,Mime_Type,Original_Name,File_Size,Location_Type from EvQuestion where Download_Id=?";
	// 	if($Return_File_Content){
	// 		$sql="select File_Title,File_Type,Mime_Type,Original_Name,File_Size,Location_Type,File_Content from EvQuestion where Download_Id=?";
	// 	}
	// 	$params=array($downloadId);
	// 	$Material_info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
	// 	return (isset($Material_info) && is_array($Material_info) && count($Material_info)>0)?$Material_info:array();
	// }
	
	// public function  getMaterialInfoDownloadByIDX($IDX)
	// {
	// 	$sql="select Download_Id from EvQuestion where IDX=?";
	// 	$params=array($IDX);
	// 	$Material_info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
	// 	return (isset($Material_info) && is_array($Material_info) && count($Material_info)>0)?$Material_info[0]["Download_Id"]:"";
	// }
		
	
}
