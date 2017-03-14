<?php
class GAppGameTopicController extends TableMagtController 
{
	private $_tableName="t_game_topic";
	private $_searchName="gameId";
	private $_next_url="/gAppGameTopic/index?in_version=2.0";

	private $_columns=array("os_type","in_version","topic_name","state","update_time","topic_start_time","topic_end_time","flag","topic_no","topic_name_en");
	private $_title="G家-游戏专题管理-配置定义";
	private $_primaryKey="id";
	
	protected $_memcacheKey=array("t_game_topic.{ID}");
	private $_game_name_list=array();

	protected $_MEMCACHE_NODE_NAME='GApi';
	protected $_MEMCACHE_PREFIX_KEY="g_api_v2.0";		//Memcache 存储前缀
	
	private function getChacheKeys($number='',$order=1,$v='2.0')
	{
		$cacheKeys=array();
		$os_array=array(1,2,'');
		$gameid = Yii::app()->request->getParam('gameid','');
		$topicid = Yii::app()->request->getParam('topic_id','');
		$flag = Yii::app()->request->getParam('flag','all');
		foreach ($os_array as $os) {
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."topic_list.%s,%s,%s,%s,%s,%s",$number,$order,$os,$topicid,$v,'all');
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."topic_list.%s,%s,%s,%s,%s,%s",$number,$order,$os,'',$v,'all');
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."topic_list.%s,%s,%s,%s,%s,%s",$number,$order,$os,$topicid,$v,'not null');
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."topic_list.%s,%s,%s,%s,%s,%s",$number,$order,$os,'',$v,'not null');
		}
		return $cacheKeys;
	}
	public function init()
	{
		$this->_PDO_NODE_NAME="GApi";
		$this->_searchName='os_type';
		$this->_EXTRA_SEARCH_FIELDS=array('in_version'=>array('compartion_type'=>'equal','field_name'=>'in_version'));
		$this->_next_url.="&os_type=".Yii::app()->request->getParam('os_type',1);
		$this->_memcacheKey=$this->getChacheKeys();
	}
	public function actionIndex()
	{
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index",'state DESC,topic_end_time DESC');
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