<?php
LunaLoader::import("luna_lib.util.LunaPdo");
//任务配置项
class 	TaskConfigData
{
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}
	
	/**
	 * 获取所有的评测试题集
	 * @return [type] [description]
	 */
	public function getTaskConfigList()
	{
		$sql="select * from  Task_Config ";
		$params=array();
		$Data_Info =LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		return (isset($Data_Info) && is_array($Data_Info) && count($Data_Info)>0)?$Data_Info:array();		
	}
	
	public function  getTaskConfigByIDX($IDX)
	{
		$sql="select * from Task_Config where IDX=? ";
		$params=array($IDX);
		$Data_Info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		return (isset($Data_Info) && is_array($Data_Info) && count($Data_Info)>0)?$Data_Info[0]:array();
	}
	
	public function updateTaskConfig($Confg_Key,$Config_Value,$Config_Remark,$IDX)
	{
		$sql="update Task_Config set Config_Value=?,Config_Remark=?,Update_Time=now() where IDX=? ";
		$params=array($Config_Value,$Config_Remark,$IDX);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
}
