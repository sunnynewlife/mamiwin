<?php
class FTaskInfoController extends TableMagtController 
{
	private $_tableName="tbl_task_info";
	private $_searchName="event_id";
	private $_next_url="/fTaskInfo/index";
	private $_columns=array("event_id", "title", "info", "task_condition", "item_id", "item_number", "end_time", "multiple", "finish_time", "min_time", "max_time", "pre_task_id", "ext_task_id", "open_multiple", "status");
	private $_title="菜鸟任务信息管理";
	private $_primaryKey="id";
	
	protected $_memcacheKey=array("t_activity_banner.{ID}");

	protected $_MEMCACHE_NODE_NAME='GApi';
	protected $_MEMCACHE_PREFIX_KEY="g_api_";		//Memcache 存储前缀
	
	private function getChacheKeys($number=5,$order=1)
	{
		$cacheKeys=array();
		$os_array=array(1,2,'');
		$gameid = Yii::app()->request->getParam('gameid','');
		foreach ($os_array as $os) {
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_banner_list_%s_%s_%s_%s",$number,$order,$os,$gameid);
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_banner_list_%s_%s_%s_%s",$number,$order,$os,'');
		}
		return $cacheKeys;
	}

		
	public function init()
	{
		$this->_PDO_NODE_NAME="CainiaoCfg";
		//$this->_memcacheKey=$this->getChacheKeys();
	}
	public function actionIndex()
	{	
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index");
	}

	public function actionAdd()
	{
		$this->_actionAdd($this->_tableName, $this->_title, $this->_next_url, $this->_columns);

	}
	public function actionModify()
	{
		$this->_actionModify($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url, $this->_tableName, $this->_columns,"modify","id");
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url,"id");
	}
}
?>