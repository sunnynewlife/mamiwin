<?php
LunaLoader::import("luna_lib.util.LunaPdo");
require_once(dirname(__FILE__).'/../config/ConfTask.php');
class ModTaskConfig {
	private $_PDO_NODE_NAME="BizDatabase";
	
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}

	/**
	 * 查询用户填写的基础资料
	 * @param  [type] $UserIDX [description]
	 * @return [type]          [description]
	 */
	public function getConfigByKey($Config_Key){
		$sql="select * from Task_Config where Config_Key = ? ";
		$params=array($Config_Key);
		$data=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($data) && is_array($data) && count($data)>0){
			return $data[0];
		}
	}
}	
?>	