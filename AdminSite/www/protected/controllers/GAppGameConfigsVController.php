<?php

class GAppGameConfigsVController extends TableMagtController 
{
	private $_tableName="t_game_config";
	private $_searchName="gameid";
	private $_next_url="/GAppGameConfigsV/index?in_version=2.0";
	private $_columns=array("share_url","gameid","os_type","game_name","download_name","download_url","download_count","size","version","intro","content","developer","game_logo","game_img","state","focus_count","is_recommend","recommend_no","index_no","app_id","in_version","flag","label","topic_id","topic_no","game_type","topic_pic","hot_act_name","hot_act_url","area_url","game_bg_img");
	private $_title="G家-同城聊天管理-配置定义";
	private $_primaryKey="id";
	
	protected $_memcacheKey=array("t_game_config.{ID}");

	protected $_MEMCACHE_NODE_NAME='GApi';
	protected $_MEMCACHE_PREFIX_KEY="g_api_v2.0";		//Memcache 存储前缀
	
	
	public function beforeAction($action){
		return parent::beforeAction($action);
	}
	public function actionGetMCKeys(){
		$op = Yii::app()->request->getParam('op','');
		$key = Yii::app()->request->getParam('key',$this->_MEMCACHE_PREFIX_KEY);
		$mc_keys=$this->getMemcacheAllKeys($key,$op);
		var_dump($mc_keys);
	}
	private function getChacheKeys($number='',$order=1,$v='2.0')
	{
		$cacheKeys=array();
		$os_array=array(1,2,'');
		$gameid = Yii::app()->request->getParam('gameid','');
		$topic_id = Yii::app()->request->getParam('topic_id','');
		$sql="select id,topic_name from t_game_topic;";
		$topic_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
		$game_type = Yii::app()->request->getParam('game_type',1);
		$flag = Yii::app()->request->getParam('flag',null);
		$start=0;
		$limit=20;
		foreach ($topic_list as $topic){
			$topic_id=$topic['id'];
			foreach ($os_array as $os) {
				$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_list.%s,%s,%s,%s,%s,%s,%s,%s,%s,%s",$number,$order,$os,$gameid,$v,$game_type,$flag,$topic_id,$start,$limit);
				$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_list.%s,%s,%s,%s,%s,%s,%s,%s,%s,%s",$number,$order,$os,'',$v,$game_type,$flag,$topic_id,$start,$limit);
				$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_list.%s,%s,%s,%s,%s,%s,%s,%s,%s,%s",'',$order,$os,$gameid,$v,$game_type,$flag,$topic_id,$start,$limit);
				$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_list.%s,%s,%s,%s,%s,%s,%s,%s,%s,%s",'',$order,$os,'',$v,$game_type,$flag,$topic_id,$start,$limit);
				$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_detail.%s,%s,%s,%s",$gameid,$os,$v,$topic_id);
				$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_detail.%s,%s,%s,%s",$gameid,$os,$v,'');
				$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_detail.%s,%s,%s,%s",'',$os,$v,$topic_id);
				$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_focus.%s,%s,%s,%s",$gameid,'',$os,$v);
				$cacheKeys[]='g_api_v2.0topic_list.,1,2,,2.0,all';
				$cacheKeys[]='g_api_v2.0topic_list.,1,2,,1.0,all';
				$cacheKeys[]='g_api_v2.0topic_list.,1,2,'.$topic_id.',2.0,all';
				$cacheKeys[]='g_api_v2.0topic_list.,1,2,'.$topic_id.',1.0,all';				
				
			}
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
		
		$this->_EXTRA_SEARCH_FIELDS=array('game_name'=>array('compartion_type'=>'like','field_name'=>'game_name'),'game_type'=>array('compartion_type'=>'equal','field_name'=>'game_type'),'in_version'=>array('compartion_type'=>'equal','field_name'=>'in_version'));
		$in_version=Yii::app()->request->getParam('in_version','');
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index",'state DESC,flag DESC');
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