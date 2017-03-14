<?php
LunaLoader::import("luna_lib.util.LunaPdo");

class ActPackageRulesController extends TableMagtController  
{
	private $_columns=array("pid","rid","conditionReturnValue");
	private $_tableName="act_package_rules";
	private $_title="活动礼包领取规则信息";
	private $_index_url="/actPackageRules/index";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="EvnPlatformCfg";
	}
	public function actionIndex()
	{
		$this->renderData["PACKAGE_MAP"]=$this->getPackageMap();
		$this->renderData["RULE_MAP"]=$this->getRuleMap();
		$this->_actionIndex($this->_tableName, "pid", $this->_index_url, "act_package_rules","index","pid");
	}
	public function actionAdd()
	{
		$this->renderData["PACKAGE_MAP"]=$this->getPackageMap();
		$this->renderData["RULE_MAP"]=$this->getRuleMap();		
		$this->_actionAdd($this->_tableName, $this->_title, $this->_index_url, $this->_columns);
	}
	public function actionModify()
	{
		$this->renderData["PACKAGE_MAP"]=$this->getPackageMap();
		$this->renderData["RULE_MAP"]=$this->getRuleMap();		
		$this->_actionModify($this->_tableName, "ID", $this->_title, $this->_index_url, "act_package_rule", $this->_columns);
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, "ID", $this->_title, $this->_index_url);
	}
	
	private function getPackageMap()
	{	
		$sql=sprintf("select name,pid from act_package_info");
		$params=array();
		$packList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($packList) && is_array($packList) && count($packList)>0){
			return $packList;
		}
		return array();
	}
	private function getRuleMap()
	{
		$sql=sprintf("select name,rid from act_rules");
		$params=array();
		$packList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($packList) && is_array($packList) && count($packList)>0){
			return $packList;
		}
		return array();		
	}	
}

?>