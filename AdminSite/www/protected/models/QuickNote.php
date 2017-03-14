<?php
LunaLoader::import("luna_lib.util.LunaPdo");

class QuickNote extends ModelBase
{
	public static function getInstance()
	{
		return parent::instance(__CLASS__);
	}
	
	public function getQuickNoteRandom()
	{
		$sql="select min(note_id) as min, max(note_id) as max from myadmin_quick_note";
		$params=array();
		$quickNote=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($quickNote) && is_array($quickNote) && count($quickNote)>0){
			$note_id=rand($quickNote[0]["min"],$quickNote[0]["max"]);
			return $this->getQuickNote($note_id);
		}
		return false;
	}
	public function getQuickNote($note_id)
	{
		$sql="select * from myadmin_quick_note where note_id=? limit 1";
		$params=array($note_id);
		$quickNote=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($quickNote) && is_array($quickNote) && count($quickNote)>0){
			return $quickNote[0];
		}
		return false;		
	}
	public function getQuickNotes($offset=0,$pageSize = 10)
	{		
		$sql=sprintf("select a.*,b.user_name as owner_name from myadmin_quick_note a left join myadmin_user b on b.user_id = a.owner_id limit %s,%s",$offset,$pageSize);
		$params=array();
		$quickNote=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($quickNote) && is_array($quickNote) && count($quickNote)>0){
			return $quickNote;
		}
		return false;		
	}
	public function getQuickNoteCount()
	{
		$sql="select count(*) as num from myadmin_quick_note";
		$params=array();
		$quickNote=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($quickNote) && is_array($quickNote) && count($quickNote)>0){
			return $quickNote[0];
		}
		return false;
	}
	
	public function delQuickNote($nid)
	{
		$sql="delete  from myadmin_quick_note where note_id=?";
		$params=array($nid);
		$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;
	}
	
	public function insertQuickNote($data)
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
		$sql=sprintf("insert into myadmin_quick_note (%s) values (%s)",$sqlFields,$sqlParamFields);
		$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;		
	}
	public function updateQuickNote($data,$note_id)
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
		$sql=sprintf("update myadmin_quick_note set %s where note_id=:note_id",$sqlFields);
		$params[":note_id"]=$note_id;
		$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;		
	}
}