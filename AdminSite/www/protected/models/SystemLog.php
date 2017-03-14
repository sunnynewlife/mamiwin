<?php
LunaLoader::import("luna_lib.util.LunaPdo");

class SystemLog extends ModelBase
{
	public static function getInstance()
	{
		return parent::instance(__CLASS__);
	}	
	public function addLog($data)
	{
		$data['op_time'] = time();
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
		$sql=sprintf("insert into myadmin_sys_log (%s) values (%s)",$sqlFields,$sqlParamFields);
		$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;
	}	
    public function getLogCount($type,$startTime,$endTime,$user_name)
    {
    	$where = ' where 1=1 ';
    	if ($type) $where .=' and class_name = "'.$type."\"";
    	if ($startTime) {
    		$startTime = strtotime($startTime);
    		$where .=" and op_time >= $startTime";
    	}
    	if ($endTime) {
    		$endTime = strtotime($endTime);
    		$where .=" and op_time <= $endTime ";
    	}    	
    	if($user_name){
    		$where .=' and user_name = "'.$user_name.'"';
    	}
    	$sql="select count(*) as num from myadmin_sys_log ".$where;
    	$params=array();
    	$logData=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
    	if(isset($logData) && is_array($logData) && count($logData)>0){
    		return $logData[0]["num"];
    	}
    	return false;
    }	
	public function getLog($type,$startTime,$endTime,$user_name,$limit,$offset)
	{   		
		$where = ' where 1=1 ';
		if ($type) $where .=' and class_name = "'.$type."\"";
		if ($startTime) {
			$startTime = strtotime($startTime);
			$where .=" and op_time >= $startTime ";
		}
		if ($endTime) {
			$endTime = strtotime($endTime);
			$where .=" and op_time <= $endTime ";
		}		
		if($user_name){
			$where .=' and user_name = "'.$user_name.'"';
		}
		$sql=sprintf("select * from myadmin_sys_log %s order by op_time desc limit %s,%s",$where,$offset,$limit);
		$params=array();
		$logData=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($logData) && is_array($logData) && count($logData)>0){
			return $logData;
		}
		return false;
	}
}