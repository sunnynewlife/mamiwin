<?php

class GAppGameToolController extends TableMagtController 
{
	private $_tableName="t_game_tool";
	private $_searchName="gameid";
	private $_next_url="/gAppGameTool/index?in_version=2.0";
	private $_columns=array("gameid","os_type","tool_name","account_type","entry_url","download_count","size","version","intro","content","developer","game_logo","game_img","state","focus_count","index_no","app_id","in_version","label","flag","download_url","download_package_name");
	private $_title="G家-同城聊天管理-配置定义";
	private $_primaryKey="tool_id";
	
	protected $_memcacheKey=array("t_game_tool.{ID}");

	protected $_MEMCACHE_NODE_NAME='GApi';
	protected $_MEMCACHE_PREFIX_KEY="g_api_v2.0";		//Memcache 存储前缀
	
	
	public function beforeAction($action){
		return parent::beforeAction($action);
	}
	public function actionDelCache($op='delete'){
		return $this->getMemcacheAllKeys($this->_MEMCACHE_PREFIX_KEY,$op);
	}
	private function getChacheKeys($number='',$order=1,$v='2.0')
	{
 		$cacheKeys=array();
		$os_array=array(1,2,'');
		$gameid = Yii::app()->request->getParam('gameid','');
		$game_type = Yii::app()->request->getParam('game_type',1);
		$tool_id = Yii::app()->request->getParam('tool_id','');
		$start=0;
		$limit=20;
		foreach ($os_array as $os) {
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_tool.%s,%s,%s,%s,%s,%s,%s,%s",$number,$order,$os,$gameid,$v,$tool_id,$start,$limit);
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_tool.%s,%s,%s,%s,%s,%s,%s,%s",$number,$order,$os,'',$v,$tool_id,$start,$limit);
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_tool.%s,%s,%s,%s,%s,%s,%s,%s",'',$order,$os,$gameid,$v,$tool_id,$start,$limit);
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_tool.%s,%s,%s,%s,%s,%s,%s,%s",'',$order,$os,'',$v,$tool_id,$start,$limit);
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_tool.%s,%s,%s,%s,%s,%s,%s,%s",$number,$order,$os,'',$v,'',$start,$limit);
		}
		return $cacheKeys;
	}

	public function init()
	{   
		$this->_PDO_NODE_NAME="GApi";
		$this->_next_url.="&os_type=".Yii::app()->request->getParam('os_type',1);
		$this->_memcacheKey=$this->getChacheKeys();
	}
	public function actionIndex()
	{	
		$this->_searchName='os_type';
		$this->_EXTRA_SEARCH_FIELDS=array('tool_name'=>array('compartion_type'=>'equal','field_name'=>'tool_name'),'in_version'=>array('compartion_type'=>'equal','field_name'=>'in_version'));
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index",'state DESC,index_no asc');
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
	public function actionSort()
	{
		$this->_searchName='os_type';
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index");
	}
	
}
?>