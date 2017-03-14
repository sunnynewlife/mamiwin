<?php
class GAppActivityController extends TableMagtController 
{
	private $_tableName="t_game_activity";
	private $_searchName="gameid";
	private $_next_url="/gAppActivity/index";
	private $_columns=array("app_entrance","os_type","activity_id","activity_name","activity_title","gameid","activity_content","activity_img","activity_detail_url","activity_no","activity_start_time","activity_end_time","activity_url_type","activity_time_range","focus_count","state","schema_info","appstore_url","pack_name","pack_download_url","app_id");
	private $_title=" 	G家-配置定义";
	private $_primaryKey="activity_id";
	
	protected $_MEMCACHE_NODE_NAME='GApi';
	protected $_MEMCACHE_PREFIX_KEY="g_api_v2.0";		//Memcache 存储前缀
	
	private function getChacheKeys($number='',$order=1)
	{
		$os=Yii::app()->request->getParam('os_type','all');
		$gameid=Yii::app()->request->getParam('gameid','');
		$v=Yii::app()->request->getParam('in_version','1.0');
		$cacheKeys[]=GappCache::getActListFromCache($number, $order, $os, '', $v);
		return $cacheKeys;
	}
	
	public function init()
	{
		$this->_PDO_NODE_NAME="GApi";
		$this->_next_url.="?os_type=".Yii::app()->request->getParam('os_type',1);
		$this->_next_url.="&in_version=".Yii::app()->request->getParam('in_version','1.0');
		$this->_memcacheKey=$this->getChacheKeys();
	}
	public function actionIndex_android()
	{
		header('Location:/gAppActivity/index?os_type=1&in_version=1.0');
	}
	public function actionIndex_ios()
	{
		header('Location:/gAppActivity/index?os_type=2&in_version=1.0');
	}
	public function actionIndex()
	{	
		$this->_searchName='os_type';
		$this->_EXTRA_SEARCH_FIELDS=array('gameid'=>array('compartion_type'=>'equal','field_name'=>'gameid'),'in_version'=>array('compartion_type'=>'equal','field_name'=>'in_version'));
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