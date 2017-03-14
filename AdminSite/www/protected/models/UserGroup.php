<?php

LunaLoader::import("luna_lib.util.LunaPdo");

class UserGroup extends ModelBase
{
	public static function getInstance()
	{
		return parent::instance(__CLASS__);
	}
	
	public function getGroupInfo($gid)
	{
		
		$sql="select * from myadmin_user_group where group_id=?";
		$params=array($gid);
		$group=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($group) && is_array($group) && count($group)>0){
			return $group[0];
		}
		return false;
	}
	public function getGroups()
	{		
		$sql="select a.*,b.user_name as owner_name from myadmin_user_group a left join myadmin_user b on b.user_id=a.owner_id ";
		$params=array();
		$group=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($group) && is_array($group) && count($group)>0){
			return $group;
		}
		return false;
	}
	public function delGroup($gid)
	{
		$sql="delete  from myadmin_user_group where group_id=?";
		$params=array($gid);
		$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;
	}
	public function insertGroup($data)
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
		$sql=sprintf("insert into myadmin_user_group (%s) values (%s)",$sqlFields,$sqlParamFields);
		$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;		
	}	
	public function updateGroup($data,$gid)
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
		$sql=sprintf("update myadmin_user_group set %s where group_id=:group_id",$sqlFields);
		$params[":group_id"]=$gid;
		$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;		
	}	
}