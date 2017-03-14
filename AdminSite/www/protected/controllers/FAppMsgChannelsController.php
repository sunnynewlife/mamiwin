<?php

class FAppMsgChannelsController extends TableMagtController 
{
	private $_tableName="MsgChannels";
	private $_searchName="";
	private $_next_url="/fAppMsgChannels/index";
	private $_columns=array("typeKey","typeName","ChannelId","TypeId");
	private $_title="分红管理后台-内部消息频道Mapping";
	private $_primaryKey="RecId";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
	}
	
	private function getChannelList()
	{
		$fAppMsgDepend=new FAppMsgDepend();
		$channels=$fAppMsgDepend->GetChannelList();
		for($i=0,$channelLen=count($channels);$i<$channelLen;$i++){
			$channels[$i]["types"]=$fAppMsgDepend->GetTypeList($channels[$i]["id"]);
		}
		return $channels;
	}
	
	protected function getPageRowsExtentData($data)
	{
		if(isset($data) && is_array($data) && count($data)>0){
			$channels=$this->getChannelList();
			foreach ($data as & $rowItem){
				$rowItem["channel_name"]="";
				$rowItem["type_name"]="";
				foreach ($channels as $item){
					if($item["id"]==$rowItem["ChannelId"]){
						$rowItem["channel_name"]=$item["name"];
						if(count($item["types"])>0 && count($item["types"]["messageTypeList"])>0){
							foreach ($item["types"]["messageTypeList"] as $typeItem){
								if($typeItem["id"]==$rowItem["TypeId"]){
									$rowItem["type_name"]=$typeItem["name"];
									break;
								}
							}	
						}						
						break;
					}
				}				
			}	
		}
		return $data;
	}
	
	public function actionIndex()
	{
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index");
	}
	
	public function actionAdd()
	{
		$this->renderData["Channels"]=$this->getChannelList();
		$this->renderData["back_url"]=$this->_next_url;
		$this->_actionAdd($this->_tableName,$this->_title,$this->_next_url, $this->_columns);
	}
	public function actionModify()
	{
		$this->renderData["Channels"]=$this->getChannelList();
		$this->renderData["back_url"]=$this->_next_url;
		$this->_actionModify($this->_tableName, $this->_primaryKey, $this->_title,$this->_next_url, $this->_tableName,$this->_columns);
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, $this->_primaryKey, $this->_title,$this->_next_url);
	}
}
?>