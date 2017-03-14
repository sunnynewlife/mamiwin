<?php
LunaLoader::import("luna_lib.util.LunaPdo");

class UserInfo extends ModelBase
{
	public static function getInstance()
	{
		return parent::instance(__CLASS__);
	}
	
	public function getUserInfo($uid)
	{
		/**
		 *
		 */
		$sql="select a.*,b.group_name,b.group_role from myadmin_user a left join myadmin_user_group b on b.group_id = a.user_group where a.user_id=?";
		$params=array($uid);
		$user=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($user) && is_array($user) && count($user)>0){
			return $user[0];
		}
		return false;
	}	
	public function getUserInfoByUserName($userName)
	{
		$sql="select a.*,b.group_name,b.group_role from myadmin_user a left join myadmin_user_group b on b.group_id = a.user_group where a.user_name=?";
		$params=array($userName);
		$user=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($user) && is_array($user) && count($user)>0){
			return $user[0];
		}
		return false;
	}
	public function getUsers($userGroup,$username,$offset=0,$pageSize = 10)
	{
		$sql="select a.*,b.group_name,b.group_role from myadmin_user a left join myadmin_user_group b on b.group_id = a.user_group";
		$params=array();
		if ($userGroup && $username){
			$sql=$sql." where a.user_group=? and a.user_name=? ";
			$params=array($userGroup,$username);
		}
		else if($userGroup){
			$sql=$sql." where a.user_group=? ";
			$params=array($userGroup);
		}else if($username){
			$sql=$sql." where a.user_name=? ";
			$params=array($username);
		}
		$sql=sprintf("%s limit %s,%s",$sql,$offset,$pageSize);
		$user=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($user) && is_array($user) && count($user)>0){
			return $user;
		}
		return false;
	}
	public function getUsersCount($userGroup,$username)
	{
		$sql="select  count(*) as num from myadmin_user";
		$params=array();
		if ($userGroup && $username){
			$sql=$sql." where user_group=? and user_name=? ";
			$params=array($userGroup,$username);
		}
		else if($userGroup){
			$sql=$sql." where user_group=? ";
			$params=array($userGroup);
		}else if($username){
			$sql=$sql." where user_name=? ";
			$params=array($username);
		}
		$user=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($user) && is_array($user) && count($user)>0){
			return $user[0];
		}
		return false;
	}
	public function delUser($uid)
	{
		$sql="delete  from myadmin_user where user_id=?";
		$params=array($uid);
		$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;
	}
	public function insertUserInfo($data)
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
		$sql=sprintf("insert into myadmin_user (%s) values (%s)",$sqlFields,$sqlParamFields);
		$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount; 
	}
	
	public function updateUserInfo($data,$uid)
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
		$sql=sprintf("update myadmin_user set %s where user_id=:user_id",$sqlFields);
		$params[":user_id"]=$uid;
		$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;
	}
	
	public function getMysqlVersion()
	{
		$sql="select version() as version from myadmin_user ";
		$params=array();
		$user=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($user) && is_array($user) && count($user)>0){
			return $user[0]["version"];
		}
	}	
}