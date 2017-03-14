<?php

class GAppSortVController extends TableMagtController 
{
	private $_tableName="t_game_config";
	private $_searchName="gameid";
	private $_next_url="/gAppSortV/index";
	private $_columns=array("gameid","recommend_no","state");
	private $_title="G家-同城聊天管理-配置定义";
	private $_primaryKey="id";
	
	protected $_MEMCACHE_NODE_NAME='GApi';
	protected $_MEMCACHE_PREFIX_KEY="g_api_v2.0";		//Memcache 存储前缀
	
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
		foreach ($topic_list as $topic){
			$topic_id=$topic['id'];
			foreach ($os_array as $os) {
				$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_list.%s,%s,%s,%s,%s,%s,%s,%s",$number,$order,$os,$gameid,$v,$game_type,$flag,$topic_id);
				$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_list.%s,%s,%s,%s,%s,%s,%s,%s",$number,$order,$os,'',$v,$game_type,$flag,$topic_id);
				$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_list.%s,%s,%s,%s,%s,%s,%s,%s",'',$order,$os,$gameid,$v,$game_type,$flag,$topic_id);
				$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_list.%s,%s,%s,%s,%s,%s,%s,%s",'',$order,$os,'',$v,$game_type,$flag,$topic_id);
				$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_detail.%s,%s,%s",$gameid,$os,$v);
				$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_detail.%s,%s,%s",'',$os,$v);
				$cacheKeys[]=sprintf($this->_MEMCACHE_PREFIX_KEY."game_focus.%s,%s,%s,%s",$gameid,'',$os,$v);
			}
		}
		return $cacheKeys;
	}
		
	public function init()
	{
		$this->_PDO_NODE_NAME="GApi";
		$this->_next_url.="?os_type=".Yii::app()->request->getParam('os_type',1);
		$this->_EXTRA_SEARCH_FIELDS=array('game_name'=>array('compartion_type'=>'equal','field_name'=>'game_name'));
		$this->_memcacheKey=$this->getChacheKeys();
	}
	public function actionIndex()
	{	
		$this->_searchName='os_type';
		$this->_EXTRA_SEARCH_FIELDS=array('flag'=>array('compartion_type'=>'not null','field_name'=>'flag'),'game_type'=>array('compartion_type'=>'equal','field_name'=>'game_type'),'in_version'=>array('compartion_type'=>'equal','field_name'=>'in_version'));
		$in_version=Yii::app()->request->getParam('in_version','');
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
		$flag = Yii::app()->request->getParam('flag','');
		$os_type = Yii::app()->request->getParam('os_type','');
		$games = Yii::app()->request->getParam('games',array());
		$count = Yii::app()->request->getParam('count','');
		if(count($games)<$count){
			echo json_encode(array('return_code'=>-1,'return_msg'=>'请检查排序，不要重复'));
			return ;
		}
		return $this->_sort($games,$os_type,$flag);
	}
	private function _sort($games,$os_type,$flag){
		$_pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$_pdo->beginTransaction();
		if($flag=='not null'){
			$sql="UPDATE t_game_config SET recommend_no=? where gameid=? and os_type=?;";
		}else{
			$sql="UPDATE t_game_config SET index_no=? where gameid=? and os_type=?;";
		}
		foreach ($games as $index=>$game){
 			$params=array($game['no'],$game['gameid'],$os_type);
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