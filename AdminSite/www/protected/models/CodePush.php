<?php
LunaLoader::import("luna_lib.util.LunaPdo");

class CodePush extends ModelBase
{
	public static function getInstance()
	{
		return parent::instance(__CLASS__);
	}
	
	/*
	 * 项目资源配置列表 - 总数
	 */
	public function countCodePushSource()
	{
		$sql = "select count(*) as num from myadmin_codepush_source";
		$params = array();
		$ret = LunaPdo::GetInstance('CodePushCfg')->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($ret) && is_array($ret) && count($ret)>0){
			return $ret[0];
		}
		return false;
	}
	/*
	 * 项目资源配置列表 - List
	 */
	public function loadCodePushSource($offset=0,$pageSize = 10)
	{		
		$sql = sprintf("select * from myadmin_codepush_source order by id desc limit %s,%s",$offset,$pageSize);
		$params = array();
		$ret = LunaPdo::GetInstance('CodePushCfg')->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($ret) && is_array($ret) && count($ret)>0){
			return $ret;
		}
		return false;		
	}
	/*
	 * 项目资源配置列表 - Detail
	 */
	public function getCodePushSource($id)
	{
		$sql = "select * from myadmin_codepush_source where id=? limit 1";
		$params = array($id);
		$ret = LunaPdo::GetInstance('CodePushCfg')->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($ret) && is_array($ret) && count($ret)>0){
			return $ret[0];
		}
		return false;		
	}
	/*
	 * 项目资源配置列表 - Add
	 */
	public function insertCodePushSource($data)
	{
		$params = array();
		$sqlFields = "";
		$sqlParamFields = "";
		foreach ($data as $fieldName => $fieldValue){
			if(empty($sqlFields)){
				$sqlFields = $fieldName;
				$sqlParamFields = sprintf(":%s",$fieldName);
			}else{
				$sqlFields = sprintf("%s,%s",$sqlFields,$fieldName);
				$sqlParamFields = sprintf("%s,:%s",$sqlParamFields,$fieldName);
			}
			$params[sprintf(":%s",$fieldName)]=$fieldValue;
		}
		$sql = sprintf("insert into myadmin_codepush_source (%s) values (%s)", $sqlFields, $sqlParamFields);
		$rowCount = LunaPdo::GetInstance('CodePushCfg')->exec_with_prepare($sql,$params);
		return $rowCount;		
	}
	
	/*
	 * 项目资源配置列表 - Update
	 */
	public function updateCodePushSource($data, $id)
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
		$sql = sprintf("update myadmin_codepush_source set %s where id=:id", $sqlFields, $sqlParamFields);
		$params[":id"] = $id;
		$rowCount = LunaPdo::GetInstance('CodePushCfg')->exec_with_prepare($sql,$params);
		return $rowCount;		
	}
	
	
	/*
	 * 打包列表 - 总数
	 */
	public function countCodePushPublish()
	{
		$sql = "select count(*) as num from myadmin_codepush_publish";
		$params = array();
		$ret = LunaPdo::GetInstance('CodePushCfg')->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($ret) && is_array($ret) && count($ret)>0){
			return $ret[0];
		}
		return false;
	}
	/*
	 * 打包列表 - List
	 */
	public function loadCodePushPublish($offset=0,$pageSize = 10)
	{		
		$sql = sprintf("select * from myadmin_codepush_publish order by id desc limit %s,%s",$offset,$pageSize);
		$params = array();
		$ret = LunaPdo::GetInstance('CodePushCfg')->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($ret) && is_array($ret) && count($ret)>0){
			return $ret;
		}
		return false;		
	}
	/*
	 * 打包列表 - Detail
	 */
	public function getCodePushPublish($id)
	{
		$sql = "select * from myadmin_codepush_publish where id=? limit 1";
		$params = array($id);
		$ret = LunaPdo::GetInstance('CodePushCfg')->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($ret) && is_array($ret) && count($ret)>0){
			return $ret[0];
		}
		return false;		
	}
	/*
	 * 打包列表 - Add
	 */
	public function insertCodePushPublish($data)
	{
		$params = array();
		$sqlFields = "";
		$sqlParamFields = "";
		foreach ($data as $fieldName => $fieldValue){
			if(empty($sqlFields)){
				$sqlFields = $fieldName;
				$sqlParamFields = sprintf(":%s",$fieldName);
			}else{
				$sqlFields = sprintf("%s,%s",$sqlFields,$fieldName);
				$sqlParamFields = sprintf("%s,:%s",$sqlParamFields,$fieldName);
			}
			$params[sprintf(":%s",$fieldName)]=$fieldValue;
		}
		$sql = sprintf("insert into myadmin_codepush_publish (%s) values (%s)", $sqlFields, $sqlParamFields);
		$rowCount = LunaPdo::GetInstance('CodePushCfg')->exec_with_prepare($sql,$params);
		return $rowCount;		
	}
	
	
	
	
	
	
	
	


	
	

}