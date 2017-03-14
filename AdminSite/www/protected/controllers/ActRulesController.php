<?php
class ActRulesController extends TableMagtController  
{
	private $_columns=array("name","type","rule_node_name","data","error_msg");
	private $_tableName="act_rules";
	private $_title="活动规则信息";
	private $_index_url="/actRules/index";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="EvnPlatformCfg";
	}
	public function actionIndex()
	{
		$this->_actionIndex($this->_tableName, "name", $this->_index_url, "act_rules");
	}
	public function actionAdd()
	{
		$this->_actionAdd($this->_tableName, $this->_title, $this->_index_url, $this->_columns);
	}
	public function actionModify()
	{
		$this->_actionModify($this->_tableName, "rid", $this->_title, $this->_index_url, "act_rule", $this->_columns);
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, "rid", $this->_title, $this->_index_url);
	}
}

?>