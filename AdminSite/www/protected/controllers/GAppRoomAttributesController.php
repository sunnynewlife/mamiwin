<?php

LunaLoader::import("luna_lib.util.LunaPdo");

class GAppRoomAttributesController extends TableMagtController 
{
	private $_tableName="t_chat_room_attributes";
	private $_searchName="chat_room_name";
	private $_next_url="/gAppRoomAttributes/index";
	private $_columns=array("chat_room_id","chat_room_name","cover_url","cover_url_small","advertisement_url","advertisement_text","sort_id","status","is_recommend","is_open","game_id");
	private $_title="G家-同城聊天管理-同城聊天室属性";
	private $_primaryKey="id";
	
	private $_g_ask_database_cfg_node_name="GAppAsk";

	
	protected  $_memcacheKey=array("t_chat_room_attributes");
	protected $_MEMCACHE_PREFIX_KEY="_gServ.sdo.com_";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="GAppCfg";
	}
	
	public function actionIndex()
	{	
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index");
	}

	public function actionAdd()
	{
		$submit = trim(Yii::app()->request->getParam('submit'),0);
		if ($submit){
			$roomId=$this->insertChatRoomInGAppAsk();
			if($roomId){
				$this->_actionAdd($this->_tableName, $this->_title, $this->_next_url, $this->_columns,"add",array(),array("chat_room_id"=>$roomId));
			}
			else{
				$this->alert('error',sprintf("增加%s失败",$this->_title));			
			}
		}else{
			$this->render("add",array());
		}
	}
	public function actionModify()
	{
		$this->_actionModify($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url, $this->_tableName, $this->_columns);
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url);
	}
	
	private function insertChatRoomInGAppAsk()
	{
		$chatRoomName=Yii::app()->request->getParam("chat_room_name",'');
		$initAttributeSql=array("SET ANSI_NULLS ON","SET QUOTED_IDENTIFIER ON","SET CONCAT_NULL_YIELDS_NULL ON","SET ANSI_WARNINGS ON","SET ANSI_PADDING ON");
		$pdo=LunaPdo::GetInstance($this->_g_ask_database_cfg_node_name);
		
		$sql="insert into Article (GameNo,Title,Gold,Status,UserId,NickName,OrderFlag,IsDelete,PublishIP,CreateDate) values (9999,?,10,0,0,'HELLO',0,0,'127.0.0.1',?)";
		$params=array($chatRoomName,date("Y-m-d H:i:s"));
		
		$count=$pdo->exec_with_prepare($sql,$params,$initAttributeSql);
		if($count>0){
			$sql="select ID from Article where GameNo=9999 and Title=? and CreateDate=? order by ID desc";
			$data=$pdo->query_with_prepare($sql,$params);
			if($data!=false && is_array($data) && count($data)>0){
				$rowItem=$data[0];
				return $rowItem["ID"];
			}			
		}
		return false;
	}
}
?>