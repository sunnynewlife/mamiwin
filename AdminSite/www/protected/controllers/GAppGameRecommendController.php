<?php

class GAppGameRecommendController extends TableMagtController 
{
	private $_tableName="t_game_recommend";
	private $_searchName="gameId";
	private $_next_url="/gAppGameRecommend/index";
	private $_columns=array("gameId","No");
	private $_title=" 	G家-同城聊天管理-配置定义";
	private $_primaryKey="id";
	
	protected $_memcacheKey=array("t_game_recommend.{ID}");
	protected $_MEMCACHE_PREFIX_KEY="_gGameServ.sdo.com_";		//Memcache 存储前缀
		
	public function init()
	{
		$this->_PDO_NODE_NAME="GAppVFive";
	}
	
	public function actionIndex()
	{	
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index");
	}

	public function actionAdd()
	{
		$this->_actionAdd($this->_tableName, $this->_title, $this->_next_url, $this->_columns);
	}
	public function actionModify()
	{
		$this->_actionModify($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url, $this->_tableName, $this->_columns,"modify","config_key");
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url,"config_key");
	}
}
?>