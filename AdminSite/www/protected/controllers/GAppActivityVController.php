<?php
class GAppActivityVController extends TableMagtController 
{
	private $_tableName="t_game_activity";
	private $_searchName="gameid";
	private $_next_url="/gAppActivityV/index";
	private $_columns=array("app_entrance","os_type","activity_id","activity_name","activity_title","gameid","activity_content","activity_img","activity_detail_url","activity_no","activity_start_time","activity_end_time","activity_url_type","activity_time_range","focus_count","state","schema_info","appstore_url","pack_name","pack_download_url","app_id","in_version","flag");
	private $_title=" 	G家-配置定义";
	private $_primaryKey="activity_id";
	
	protected $_MEMCACHE_NODE_NAME='GApi';
	protected $_MEMCACHE_PREFIX_KEY="g_api_v2.0";		//Memcache 存储前缀
	
	private function getChacheKeys($number=5,$order=1,$v='2.0')
	{
		$cacheKeys=array();
		$os_array=array(1,2,'');
		$gameid = Yii::app()->request->getParam('gameid','');
		$activity_id = Yii::app()->request->getParam('activity_id','');
		foreach ($os_array as $os) {
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_act_list.%s,%s,%s,%s,%s",$number,$order,$os,$gameid,$v);
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_act_list.%s,%s,%s,%s,%s",$number,$order,$os,'',$v);
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_act_list.%s,%s,%s,%s,%s",'',$order,$os,$gameid,$v);
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_act_list.%s,%s,%s,%s,%s",'',$order,$os,'',$v);
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_act_list.%s,%s,%s,%s,%s",'',$order,$os,$gameid,$v);
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_act_list.%s,%s,%s,%s,%s",'',$order,$os,'',$v);
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."act_focus.%s,%s,%s",$activity_id,'',$os);
		}	
		return $cacheKeys;
	}
	
	public function init()
	{
		$this->_PDO_NODE_NAME="GApi";
		$in_version=Yii::app()->request->getParam('in_version','2.0');
		$this->_next_url.="?in_version=".$in_version."&os_type=".Yii::app()->request->getParam('os_type',1);
		$this->_memcacheKey=$this->getChacheKeys();
	}
	public function actionIndex_android()
	{
		header('Location:/gAppActivity/index?os_type=1');
	}
	public function actionIndex_ios()
	{
		header('Location:/gAppActivity/index?os_type=2');
	}
	public function actionIndex()
	{	
		$this->_searchName='os_type';
		$this->_EXTRA_SEARCH_FIELDS=array('game_name'=>array('compartion_type'=>'equal','field_name'=>'game_name'),'in_version'=>array('compartion_type'=>'equal','field_name'=>'in_version'));
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index",'state DESC');
	}
	public function actionAdd()
	{
		$this->_actionAdd($this->_tableName, $this->_title, $this->_next_url, $this->_columns);
	}
	public function actionModify()
	{
		$this->_actionModify($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url, $this->_tableName, $this->_columns,"modify","activity_id");
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url,"activity_id");
	}
}
?>