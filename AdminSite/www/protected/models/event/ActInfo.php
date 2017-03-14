<?php

LunaLoader::import("luna_lib.util.LunaPdo");

class ActInfo extends ModelBase 
{
	public $_PDO_NODE_NAME="EvnPlatformCfg";
	public static function getInstance()
	{
		return parent::instance(__CLASS__);
	}
	
	public function getActInfoCount($name,$game_code,$type,$status)
	{
		$where=" where 1=1 ";
		if(empty($name)==false){
			$where.=" and name like '%$name%'";
		}
		if(empty($game_code)==false){
			$where.=" and game_code='$game_code'";
		}
		if(empty($type)==false){
			$where.=" and type=$type";
		}
		if(empty($status)==false){
			$where.=" and status=$status";
		}
		$sql="select count(*) as num from act_info ".$where;
		$params=array();
		$actInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($actInfo) && is_array($actInfo) && count($actInfo)>0){
			return $actInfo[0];
		}
	}
	public function getActInfo($name,$game_code,$type,$status,$page_size,$offSet)
	{
		$where=" where 1=1 ";
		if(empty($name)==false){
			$where.=" and name like '%$name%'";
		}
		if(empty($game_code)==false){
			$where.=" and game_code='$game_code'";
		}
		if(empty($type)==false){
			$where.=" and type=$type";
		}
		if(empty($status)==false){
			$where.=" and status=$status";
		}
		$sql="select * from act_info ".$where." limit $offSet,$page_size";
		$params=array();
		$actInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($actInfo) && is_array($actInfo) && count($actInfo)>0){
			return $actInfo;
		}
		return false;
	}
}

?>