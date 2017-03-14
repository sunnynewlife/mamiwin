<?php

class GDataController extends TableMagtController 
{
	private $_tableName="gapp_log";
	private $_searchName="";
	private $_next_url="/gAppGameConfigs/index";
	private $_columns=array("SystemVersion","gameid","os_type","game_name","download_name","download_url","download_count","size","version","intro","content","developer","game_logo","game_img","state","focus_count","is_recommend","recommend_no","Phone","Extend1","Extend2","Extend3");
	private $_title="G家-同城聊天管理-配置定义";
	private $_primaryKey="id";
	
	protected $_memcacheKey=array("t_game_config.{ID}");

	protected $_MEMCACHE_NODE_NAME='GAppLog';
	protected $_MEMCACHE_PREFIX_KEY="g_api_";		//Memcache 存储前缀
	
	private function getChacheKeys($number=6,$order=1)
	{
		$cacheKeys=array();
		$os_array=array(1,2,'');
		$gameid = Yii::app()->request->getParam('gameid','');
		foreach ($os_array as $os) {
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_list_%s_%s_%s_%s",$number,$order,$os,$gameid);
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_list_%s_%s_%s_%s",$number,$order,$os,'');
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_detail_%s_%s",$gameid,$os);
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_detail_%s_%s",'',$os);
		}
		return $cacheKeys;
	}

	public function init()
	{   
		$this->_PDO_NODE_NAME="GAppLog";
		$this->_memcacheKey=$this->getChacheKeys();
	}
	
	public function actionIndex()
	{	
		$date = Yii::app()->request->getParam('date',date('Y_m_d'));
		$this->_tableName='gapp_log_'.$date;
		$this->_searchName='ProductId';
		$this->_EXTRA_SEARCH_FIELDS=array('Phone'=>array('compartion_type'=>'equal','field_name'=>'Phone'));
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, 'gapp_log',"index",'id desc');
	}
	
}
?>