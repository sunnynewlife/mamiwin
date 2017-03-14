<?php
class GAppGameActBannerVController extends TableMagtController 
{
	private $_tableName="t_ban_list";
	private $_searchName="gameid";
	private $_next_url="/gAppGameActBannerV/index";
	private $_columns=array("app_entrance","os_type","img","gameid","url","url_type","index_no","state","schema_info","appstore_url","pack_name","pack_download_url","activity_url_type","app_id","in_version","banner_type","activity_title");
	private $_title=" 	G家-同城聊天管理-配置定义";
	private $_primaryKey="id";
	
	protected $_memcacheKey=array("t_ban_list.{ID}");

	protected $_MEMCACHE_NODE_NAME='GApi';
	protected $_MEMCACHE_PREFIX_KEY="g_api_v2.0";		//Memcache 存储前缀
	
	private function getChacheKeys($number=5,$order=1,$v='2.0')
	{
		$cacheKeys=array();
		$os_array=array(1,2,'');
		$gameid = Yii::app()->request->getParam('gameid','');
		$banner_type = Yii::app()->request->getParam('banner_type',1);
		foreach ($os_array as $os) {
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_banner_list.%s,%s,%s,%s,%s,%s",$number,$order,$os,$gameid,$v,$banner_type);
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_banner_list.%s,%s,%s,%s,%s,%s",$number,$order,$os,'',$v,$banner_type);
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_banner_list.%s,%s,%s,%s,%s,%s",'',$order,$os,$gameid,$v,$banner_type);
			$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."tq_banner_list.%s,%s,%s,%s,%s,%s",'',$order,$os,'',$v,$banner_type);
		}
		return $cacheKeys;
	}

		
	public function init()
	{
		$this->_PDO_NODE_NAME="GApi";
		$os_type=Yii::app()->request->getParam('os_type',1);
		$banner_type=Yii::app()->request->getParam('banner_type',1);
		$in_version=Yii::app()->request->getParam('in_version','2.0');
		if($in_version=='1.0'){
			$this->_tableName="t_activity_banner";
			$this->_columns=array("app_entrance","os_type","img","gameid","url","url_type","no","state","schema_info","appstore_url","pack_name","pack_download_url","activity_url_type","app_id");
				
		}
		$this->_next_url.="?in_version=".$in_version."&os_type=".$os_type."&banner_type=".$banner_type;
		$this->_memcacheKey=$this->getChacheKeys();
		$this->_EXTRA_SEARCH_FIELDS=array('gameid'=>array('compartion_type'=>'equal','field_name'=>'gameid'));
	}
	public function actionIndex()
	{
		$this->_searchName='os_type';
		$this->_EXTRA_SEARCH_FIELDS=array('in_version'=>array('compartion_type'=>'equal','field_name'=>'in_version'),'banner_type'=>array('compartion_type'=>'equal','field_name'=>'banner_type'));
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
}
?>