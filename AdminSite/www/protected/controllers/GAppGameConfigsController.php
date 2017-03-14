<?php

class GAppGameConfigsController extends TableMagtController 
{
	private $_tableName="t_game_config";
	private $_searchName="gameid";
	private $_next_url="/gAppGameConfigs/index";
	private $_columns=array("share_url","gameid","os_type","game_name","download_name","download_url","download_count","size","version","intro","content","developer","game_logo","game_img","state","focus_count","is_recommend","recommend_no","app_id");
	private $_title="G家-同城聊天管理-配置定义";
	private $_primaryKey="id";
	
	protected $_memcacheKey=array("t_game_config.{ID}");

	protected $_MEMCACHE_NODE_NAME='GApi';
	protected $_MEMCACHE_PREFIX_KEY="g_api_v2.0";		//Memcache 存储前缀
	
	public function actionDelCache(){
		$cacheKeys=array();
		$os_array=array(1,2,'');
		$number= Yii::app()->request->getParam('number','');
		$order=1;
		$sql="select gameid from t_game_config";
		$game_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
		$sql="select activity_id from t_game_activity";
		$activity_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
		foreach ($os_array as $os) {
				foreach ($game_list as $gameitem){
					$gameid=$gameitem['gameid'];
					$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_list_%s_%s_%s_%s",$number,$order,$os,$gameid);
					$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_list_%s_%s_%s_%s",$number,$order,$os,'');
					$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_detail_%s_%s",$gameid,$os);
					$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_detail_%s_%s",'',$os);
					$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_focus_%s_%s_%s",$gameid,'',$os);
					
					$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_banner_list_%s_%s_%s_%s",$number,$order,$os,$gameid);
					$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_banner_list_%s_%s_%s_%s",$number,$order,$os,'');
					$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_banner_list_%s_%s_%s_%s",'',$order,$os,$gameid);
					$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_banner_list_%s_%s_%s_%s",'',$order,$os,'');
					
					$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_medias_%s_%s_%s",$gameid,$number,$os);
					$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_medias_%s_%s_%s",'',$number,$os);
					
					foreach ($activity_list as $activityitem){
						$activity_id=$activityitem['activity_id'];
						$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_act_list_%s_%s_%s_%s",$number,$order,$os,$gameid);
						$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_act_list_%s_%s_%s_%s",$number,$order,$os,'');
						$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_act_list_%s_%s_%s_%s",'',$order,$os,$gameid);
						$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_act_list_%s_%s_%s_%s",'',$order,$os,'');
						$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."act_focus_%s_%s_%s",$activity_id,'',$os);
					}
			}
		}
		foreach ($cacheKeys as $delKey){
			echo LunaMemcache::GetInstance($this->_MEMCACHE_NODE_NAME,$this->_MEMCACHE_PREFIX_KEY)->delete($delKey);
		}
		return $cacheKeys;
	}
	private function getChacheKeys($number='',$order=1)
	{
		$os=Yii::app()->request->getParam('os_type','all');
		$gameid=Yii::app()->request->getParam('gameid','');
		$v=Yii::app()->request->getParam('in_version','1.0');
		$cacheKeys[]=GappCache::getGameListFromCache($number, $order, $os, $gameid, $v,0,'not null','',0,20);
		$cacheKeys[]=GappCache::getGameDetailFromCache($gameid, $os,$v,'');
		return $cacheKeys;
	}


	public function init()
	{   
		$this->_PDO_NODE_NAME="GApi";
		$this->_next_url.="?os_type=".Yii::app()->request->getParam('os_type',1);
		$this->_next_url.="&in_version=".Yii::app()->request->getParam('in_version','1.0');
		$this->_memcacheKey=$this->getChacheKeys();
	}
	
	public function actionIndex_android(){
		header('Location:/gAppGameConfigs/index?os_type=1&in_version=1.0');
	}
	public function actionIndex_ios(){
		header('Location:/gAppGameConfigs/index?os_type=2&in_version=1.0');
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