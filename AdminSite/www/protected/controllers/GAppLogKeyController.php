<?php
class GAppLogKeyController extends TableMagtController 
{
	private $_tableName="gapp_log_key";
	private $_searchName="gameId";
	private $_next_url="/gAppLogKey/index";
	private $_columns=array("ProductId","des_key","Product_intr");
	private $_title=" 	G家-统一日志Key管理";
	private $_primaryKey="id";
	
	protected $_memcacheKey=array("t_gapp_key.%s");
	private $_game_name_list=array();
		
	protected $_MEMCACHE_NODE_NAME='GApi';
	protected $_MEMCACHE_PREFIX_KEY="g_api_";		//Memcache 存储前缀
	/**
	 * 构造memcache的key
	 *
	 * @return string
	 */
	private function _buildKey($key) {
		return $this->_MEMCACHE_PREFIX_KEY.$key;
	}
	public function delMc(){
		$ProductId = Yii::app()->request->getParam('ProductId','');
		if($ProductId){
			$mckey=$this->_buildKey('t_gapp_key_' . $ProductId);
			$memcache = LunaMemcache::GetInstance($this->_MEMCACHE_NODE_NAME,$this->_MEMCACHE_PREFIX_KEY);
			$memcache->delete($mckey);
		}
	}
	
	public function init()
	{
		$this->_PDO_NODE_NAME="GAppLog";
	}
	public function actionIndex()
	{	$this->_searchName='ProductId';
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index");
	}

	public function actionAdd()
	{
		$this->delMc();
		$this->_actionAdd($this->_tableName, $this->_title, $this->_next_url, $this->_columns);
		
	}
	public function actionModify()
	{
		$this->delMc();
		$this->_actionModify($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url, $this->_tableName, $this->_columns,"modify","id");
	}
	public function actionDel()
	{
		$this->delMc();
		$this->_actionDel($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url,"id");
	}
}
?>