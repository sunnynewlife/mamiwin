<?php

LunaLoader::import("luna_core.LunaConfigMagt");
LunaLoader::import("luna_lib.http.HttpInterface");

class FAppMsgDepend
{
	private $_ExtraSystemName="MessageSystem";
	private $_InterfaceName="ApiPool";
	private $_osap_user="";
	private $_ticket=""; 
	private $_version="1.0";
	private $_FApp_msg_AppID="";
	
	public $_LastErrorMessage="";
	
	public function __construct()
	{
		$appCfg=LunaConfigMagt::getInstance()->getAppConfig();
		if(isset($appCfg["FApp_msg_user"])){
			$this->_osap_user=$appCfg["FApp_msg_user"];
		}
		if(isset($appCfg["FApp_msg_ticket"])){
			$this->_ticket=$appCfg["FApp_msg_ticket"];
		}
		if(isset($appCfg["FApp_msg_AppID"])){
			$this->_FApp_msg_AppID=$appCfg["FApp_msg_AppID"];
		}		
	}	

	private function submit($params=array())
	{
		$http=new HttpInterface($this->_ExtraSystemName, $this->_InterfaceName);
		$params["osap_user"]	=$this->_osap_user;
		$params["ticket"]		=$this->_ticket;
		$params["version"]		=$this->_version;
		
		$data=$http->submit($params,false,array("header" => array("Content-type: application/x-www-form-urlencoded")));
		if(isset($data) && is_array($data) && count($data)>0 && isset($data["return_code"])){
			if($data["return_code"]==0){
				if(isset($data["data"])){
					return $data["data"];
				}
				else {
					return true;
				}
			}
			$this->_LastErrorMessage=sprintf("Return Code:%s Message:%s",$data["return_code"],$data["return_message"]);
		}else{
			$this->_LastErrorMessage="Undocument Response Content.";
		}
		return false;
	} 
	
	public function GetChannelList()
	{
		$params=array(
				"method"	=> "push.getChannelList",
		);
		$data=$this->submit($params);
		if($data){
			return $data["channelList"];
		}
		return array();
	}
	
	public function GetTypeList($channId)
	{
		$params=array(
				"method"	=> "push.getMessageTypeList",
				"channelId"	=>	$channId,
		);
		$data=$this->submit($params);
		if($data){
			return $data;
		}
		return array();		
	}
	
	public function AddChannel($channelName,$channelIcon)
	{
		$params=array(
			"method"	=> 	"push.createChannel",
			"name"		=>	$channelName,
			"icon"		=>  $channelIcon,
		);
		return $this->submit($params);
	}
	
	public function DeleteChannel($channelId)
	{
		$params=array(
				"method"	=> 	"push.deleteChannel",
				"channelId"	=>	$channelId,
		);
		return $this->submit($params);		
	}
	
	public function UpdateChannel($channelName,$channelIcon,$channelId)
	{
		$params=array(
				"method"	=> 	"push.updateChannel",
				"name"		=>	$channelName,
				"icon"		=>  $channelIcon,
				"channelId"	=>	$channelId,
		);
		return $this->submit($params);		
	}
	
	
	public function AddType($channelId,$name)
	{
		$params=array(
				"method"	=> 	"push.createMessageType",
				"channelId"	=>	$channelId,
				"name"		=>	$name,
		);
		return $this->submit($params);
	}
	
	public function DeleteType($TypeId)
	{
		$params=array(
				"method"		=> 	"push.deleteMessageType",
				"messageTypeId"	=>	$TypeId,
		);
		return $this->submit($params);		
	}
	
	public function UpdateType($channelId,$typeId,$name)
	{
		$params=array(
				"method"		=> 	"push.updateMessageType",
				"channelId"		=>	$channelId,
				"messageTypeId"	=>	$typeId,
				"name"			=>	$name,
		);
		return $this->submit($params);		
	}
	
	public function sendMsg($sessionId,$title,$detailUrl,$absctract,$content,$imgUrl,$channelId,$msgTypeId,$notifyTitle,$phones,$scheduleTime="",$needRebuildConent=false,$InforMsgId=0)
	{
		if($needRebuildConent){
			$content=json_encode( array('id' => (0-$InforMsgId), 'text' => $content, 'channelId' => $channelId, 'channelType' => $msgTypeId, 'type' => 0));
		}
		
		$phoneList="";
		if(isset($phones) && is_array($phones) && count($phones)>0){
			foreach ($phones as $phoneId){
				$phoneList.=sprintf(",%s@%s",$phoneId,$this->_FApp_msg_AppID);
			}
			$phoneList=substr($phoneList, 1);
		}else if(isset($phones) && empty($phones)==false){
			$phoneList=sprintf("%s@%s",$phones,$this->_FApp_msg_AppID);
		}
		if(empty($phoneList)==false){
			$params=array(
				"method"		=>	"push.pushMessage",
				"dest"			=>	$phoneList,
				"title"			=>	empty($title)?"":$title,
				"detailUrl"		=>	empty($detailUrl)?"":$detailUrl,
				"abstract"		=>	empty($absctract)?"":$absctract,
				"notifyTitle"	=>	empty($notifyTitle)?"":$notifyTitle,
				"image"			=>	empty($imgUrl)?"":$imgUrl,
				"sessionId"		=>	$sessionId,
				"messageType"	=>	$msgTypeId,
				"content"		=>	empty($content)?"":$content,
				"scheduleTime"	=>  "",	
				"appId"			=>	$this->_FApp_msg_AppID,
				"channel"		=>	$channelId,
			);
			if(empty($scheduleTime)==false){
				$datetime=date_create($scheduleTime);
				if($datetime){
					$params["scheduleTime"]=date_timestamp_get($datetime);
				}
			}
			return $this->submit($params);
		}else{
			$this->_LastErrorMessage="phone is empty.";
		}
		return false;
	}
	
}