<?php
LunaLoader::import("luna_lib.util.LunaPdo");
// 系统前端用户
class 	UserInfoData
{
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}

	public function newUserInfo($LoginName,$LoginPwd)
	{
		$AcctSource = "Fumuwin";
		$AcctStatus = 1 ;
		$Sign_From = 9 ;	//来自后台增加
		$sql="insert into User_Info(LoginName,LoginPwd,AcctSource,AcctStatus,Sign_From) values (?,?,?,?,?)";
		$params=array($LoginName,$LoginPwd,$AcctSource,$AcctStatus,$Sign_From);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}

	public function  queryUserInfo($LoginName)
	{
		$sql="select * from User_Info where LoginName =?";
		$params=array($LoginName);
		$Material_info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		return (isset($Material_info) && is_array($Material_info) && count($Material_info)>0)?$Material_info:array();
	}
	
	
	// /**
	//  * 获取所有的评测试题集
	//  * @return [type] [description]
	//  */
	// public function getEvaluationQuestionsSetList()
	// {
	// 	$sql="select * from  Evaluation_Questions_Set ";
	// 	$params=array();
	// 	$EvQuestion=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
	// 	return (isset($EvQuestion) && is_array($EvQuestion) && count($EvQuestion)>0)?$EvQuestion:array();		
	// }
	
	// public function updateEvQuestionSet($Set_Name,$Set_Qty,$Set_Type,$Remark,$Conditon_Child_Gender,$Condition_Parent_Gender,$Condition_Parent_Marriage,$Condition_Min_Age,$Condition_Max_age,$Condition_Only_Children,$IDX)
	// {
	// 	$sql="update Evaluation_Questions_Set set Set_Name=?,Set_Qty=?,Remark=?,Update_Time=now() where IDX=?";//Set_Type=?,Conditon_Child_Gender=?,Condition_Parent_Gender=?,Condition_Parent_Marriage=?,Condition_Min_Age=?,Condition_Max_age=?,Condition_Only_Children=?,
	// 	$params=array($Set_Name,$Set_Qty,$Remark,$IDX);//$Set_Type,$Conditon_Child_Gender,$Condition_Parent_Gender,$Condition_Parent_Marriage,$Condition_Min_Age,$Condition_Max_age,$Condition_Only_Children,
	// 	return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	// }


	
}
