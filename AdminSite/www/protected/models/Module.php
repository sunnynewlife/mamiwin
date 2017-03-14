<?php
LunaLoader::import("luna_lib.util.LunaPdo");

class Module extends ModelBase
{
	public static function getInstance()
	{
		return parent::instance(__CLASS__);
	}
	
	public function getModuleInfo($mid)
	{
		$sql="select * from myadmin_module where module_id=?";
		$params=array($mid);
		$module=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($module) && is_array($module) && count($module)>0){
			return $module[0];
		}
		return false;
	}
	
	public function getModuleList()
	{
		$sql="select * from myadmin_module where online = 1 order by module_sort asc";
		$params=array();
		$module=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($module) && is_array($module) && count($module)>0){
			return $module;
		}
		return false;
	}
	
	public function getAllModule()
	{
		$sql="select * from myadmin_module order by module_sort asc";
		$params=array();
		$module=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($module) && is_array($module) && count($module)>0){
			return $module;
		}
		return false;
	}
	
	public function insertModule($data)
	{
		$params=array();
		$sqlFields="";
		$sqlParamFields="";
		foreach ($data as $fieldName => $fieldValue){
			if(empty($sqlFields)){
				$sqlFields=$fieldName;
				$sqlParamFields=sprintf(":%s",$fieldName);
			}else{
				$sqlFields=sprintf("%s,%s",$sqlFields,$fieldName);
				$sqlParamFields=sprintf("%s,:%s",$sqlParamFields,$fieldName);
			}
			$params[sprintf(":%s",$fieldName)]=$fieldValue;
		}
		$sql=sprintf("insert into myadmin_module (%s) values (%s)",$sqlFields,$sqlParamFields);
		$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;
	}
	
	public function updateModule($data,$mid)
	{
		$params=array();
		$sqlFields="";
		foreach ($data as $fieldName => $fieldValue){
			if(empty($sqlFields)){
				$sqlFields=sprintf("%s=:%s",$fieldName,$fieldName);
			}else{
				$sqlFields=sprintf("%s,%s=:%s",$sqlFields,$fieldName,$fieldName);
			}
			$params[sprintf(":%s",$fieldName)]=$fieldValue;
		}
		$sql=sprintf("update myadmin_module set %s where module_id=:module_id",$sqlFields);
		$params[":module_id"]=$mid;
		$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;
	}
	
	public function delModule($mid)
	{
		$sql="delete  from myadmin_module where module_id=?";
		$params=array($mid);
		$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;
	}
}