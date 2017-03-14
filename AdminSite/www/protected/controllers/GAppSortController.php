<?php

class GAppSortController extends TableMagtController 
{
	private $_tableName="t_game_config";
	private $_searchName="gameid";
	private $_next_url="/gAppSort/index";
	private $_columns=array("gameid","recommend_no","state");
	private $_title="G家-同城聊天管理-配置定义";
	private $_primaryKey="id";
	
	protected $_MEMCACHE_NODE_NAME='GApi';
	protected $_MEMCACHE_PREFIX_KEY="g_api_v2.0";		//Memcache 存储前缀
	
	private function getChacheKeys($number=6,$order=1)
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
		$this->_EXTRA_SEARCH_FIELDS=array('game_name'=>array('compartion_type'=>'equal','field_name'=>'game_name'),'in_version'=>array('compartion_type'=>'equal','field_name'=>'in_version'));
		$this->_memcacheKey=$this->getChacheKeys();
	}
	public function actionIndex_android(){
		header('Location:/gAppSort/index?os_type=1&in_version=1.0');
	}
	public function actionIndex_ios(){
		header('Location:/gAppSort/index?os_type=2&in_version=1.0');
	}
	public function actionIndex()
	{	
		$this->_searchName='os_type';
		
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index",'state DESC,recommend_no asc');
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
		$os_type = Yii::app()->request->getParam('os_type','');
		$count=Yii::app()->request->getParam('count',6);
		$games=array();
		for($i=1;$i<$count+1;$i++){
			$games[$i] = Yii::app()->request->getParam('index_'.$i,'');
			if(!$games[$i]){
				echo json_encode(array('return_code'=>-1,'return_msg'=>'ban_'.$i.' empty'));
				yii::app()->end();			
			}
		}
		$games=array_unique($games);
		if(count($games)<$count){
			echo json_encode(array('return_code'=>-1,'return_msg'=>'请检查排序，不要重复'));
			return ;
		}
		return $this->_sort($games,$os_type);
	}
	private function _sort($games,$os_type){
		$_pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$_pdo->beginTransaction();
		foreach ($games as $index=>$gameid){
			$sql="UPDATE t_game_config SET recommend_no=? where gameid=? and os_type=?;";
			$params=array($index,$gameid,$os_type);
			$count=$_pdo->exec_with_prepare($sql,$params);
			if(0>$count){
				$_pdo->rollBack();
				echo json_encode(array('return_code'=>-1,''));
				return false;
			} 
		}
		$_pdo->commit();
		echo json_encode(array('return_code'=>0,'return_msg'=>'ok'));
		return true;
	}
	
}
?>