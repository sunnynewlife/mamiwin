<?php

LunaLoader::import("luna_lib.util.CGuidManager");

class FAppMsgHistoryController extends TableMagtController 
{
	private $_tableName="Information";
	private $_searchName="";
	private $_next_url="/fAppMsgHistory/index";
	private $_columns=array("");
	private $_title="分红管理后台-消息查看";
	private $_primaryKey="InformationId";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
	}
	public function actionHisIndex()
	{
		$this->renderData["MsgList"]=array();
		$currentTime=time();
		$defaultStartDt		=	date("Y-m-d 00:00:00",$currentTime-(7*24*60*60));
		$defaultEndDt		=	date("Y-m-d 23:59:59",$currentTime);
		$startDt			=	trim(Yii::app()->request->getParam('startDt',$defaultStartDt));
		$endDt				=	trim(Yii::app()->request->getParam('endDt',$defaultEndDt));
		if($defaultStartDt!=$startDt){
			if(empty($startDt)){
				$startDt=$defaultStartDt;
			}else{
				$startDt.=" 00:00:00";
			}
		}
		if($defaultEndDt!=$endDt){
			if(empty($endDt)){
				$endDt=$defaultEndDt;
			}else{
				$endDt.=" 23:59:59";
			}
		}
		$title				=	trim(Yii::app()->request->getParam('title',""));
		$fAppData			=new FAppData();
		$this->renderData["MsgList"]=$fAppData->getInformationList($startDt, $endDt, 1,$title);
		$this->renderData["DefaultStart"]=substr($startDt, 0,10);
		$this->renderData["DefaultEnd"]=substr($endDt, 0,10);
		
		$this->render("hisIndex",$this->renderData);
	}
	
	public function actionIndex()
	{
		$this->renderData["MsgList"]=array();
		$currentTime=time();
		$defaultStartDt		=	date("Y-m-d 00:00:00",$currentTime-(7*24*60*60));
		$defaultEndDt		=	date("Y-m-d 23:59:59",$currentTime);
		$startDt			=	trim(Yii::app()->request->getParam('startDt',$defaultStartDt));
		$endDt				=	trim(Yii::app()->request->getParam('endDt',$defaultEndDt));
		if($defaultStartDt!=$startDt){
			if(empty($startDt)){
				$startDt=$defaultStartDt;
			}else{
				$startDt.=" 00:00:00";
			}
		}
		if($defaultEndDt!=$endDt){
			if(empty($endDt)){
				$endDt=$defaultEndDt;
			}else{
				$endDt.=" 23:59:59";
			}
		}
		$title				=	trim(Yii::app()->request->getParam('title',""));
		$fAppData	=new FAppData();
		$this->renderData["MsgList"]=$fAppData->getInformationList($startDt, $endDt, 0,$title);
		$this->renderData["DefaultStart"]=substr($startDt, 0,10);
		$this->renderData["DefaultEnd"]=substr($endDt, 0,10);
		$this->render("index",$this->renderData);
	}
	
	private function getChannelList()
	{
		$fAppMsgDepend=new FAppMsgDepend();
		$fAppData=new FAppData();
		$ChannelDefList=$fAppData->getChannelDefList();
		$ExcludeChannel=array();
		foreach ($ChannelDefList as $row){
			if($row["Status"]==2){
				$ExcludeChannel[$row["ChannelId"]]=$row["ChannelId"];
			}
		}
		$channelsDef=$fAppMsgDepend->GetChannelList();
		$channels=array();
		for($i=0,$channelLen=count($channelsDef);$i<$channelLen;$i++){
			$channelId=$channelsDef[$i]["id"];
			if(isset($ExcludeChannel[$channelId])==false){
				$row=$channelsDef[$i];
				$row["types"]=$fAppMsgDepend->GetTypeList($channelId);
				$channels[]=$row;
			}
		}
		return $channels;
	}
	private function getExcludeChannelId()
	{
		$fAppData=new FAppData();
		return $fAppData->getSystemConfigItem("exclude.channelId.sending","13");
	}
	
	public function actionAdd()
	{
		$this->renderData["Channels"]=$this->getChannelList();
		$this->renderData["ExcludeChannelId"]=$this->getExcludeChannelId();
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$ChannelId	=	trim(Yii::app()->request->getParam('ChannelId',""));
			$TypeId		=	trim(Yii::app()->request->getParam('TypeId',""));
			$Title		=	trim(Yii::app()->request->getParam('Title',""));
			$NotifyTitle=	trim(Yii::app()->request->getParam('NotifyTitle',""));
			$Abstract	=	trim(Yii::app()->request->getParam('Abstract',""));
			$ImageUrl	=	trim(Yii::app()->request->getParam('ImageUrl',""));
			$DetailUrl	=	trim(Yii::app()->request->getParam('DetailUrl',""));
			$Content	=	trim(Yii::app()->request->getParam('Content',""));
			$ConentType	=	trim(Yii::app()->request->getParam('ConentType',""));
			if($ConentType=="url"){
				$Content="";
			} else if($ConentType=="text"){
				$DetailUrl="";
			}
			
			$WeixinTitle	=	trim(Yii::app()->request->getParam('WeixinTitle',""));
			$WeixinPic		=	trim(Yii::app()->request->getParam('WeixinPic',""));
			$WeixinContent	=	trim(Yii::app()->request->getParam('WeixinContent',""));
			$WeiBoContent	=	trim(Yii::app()->request->getParam('WeiBoContent',""));
			$WeiBoPic		=	trim(Yii::app()->request->getParam('WeiBoPic',""));
			$PhoneMsg		=	trim(Yii::app()->request->getParam('PhoneMsg',""));
			
			$msgSessionId	=	CGuidManager::GetFullGuid();
			$sourceType		=	"MsgMagt";
			
			$fAppData=new FAppData();
			if($fAppData->insertInformation($ChannelId, $TypeId, $Title, $NotifyTitle, $Abstract, $ImageUrl, $DetailUrl, $Content, $WeixinTitle, $WeixinPic, $WeixinContent, $WeiBoContent, $WeiBoPic, $PhoneMsg, $msgSessionId, $sourceType)>0){
				$InformationId=$fAppData->getLastInsertInformationId($Title);
				$this->exitWithSuccess("保存消息成功","/fAppMsgHistory/modify?InformationId=".$InformationId);
			}else{
				$this->alert('error',"保存消息失败");
			}			
		}
		$this->render("add",$this->renderData);
	}
	
	public function actionModify()
	{
		$InformationId = Yii::app()->request->getParam("InformationId",'');
		if(empty($InformationId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$fAppData=new FAppData();
		$Information=$fAppData->getInformationById($InformationId);
		if(count($Information)==0){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$this->renderData["Information"]=$Information;
		$this->renderData["Channels"]=$this->getChannelList();
		$this->renderData["ExcludeChannelId"]=$this->getExcludeChannelId();
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$ChannelId	=	trim(Yii::app()->request->getParam('ChannelId',""));
			$TypeId		=	trim(Yii::app()->request->getParam('TypeId',""));
			$Title		=	trim(Yii::app()->request->getParam('Title',""));
			$NotifyTitle=	trim(Yii::app()->request->getParam('NotifyTitle',""));
			$Abstract	=	trim(Yii::app()->request->getParam('Abstract',""));
			$ImageUrl	=	trim(Yii::app()->request->getParam('ImageUrl',""));
			$DetailUrl	=	trim(Yii::app()->request->getParam('DetailUrl',""));
			$Content	=	trim(Yii::app()->request->getParam('Content',""));
			$ConentType	=	trim(Yii::app()->request->getParam('ConentType',""));
			if($ConentType=="url"){
				$Content="";
			} else if($ConentType=="text"){
				$DetailUrl="";
			}
				
			$WeixinTitle	=	trim(Yii::app()->request->getParam('WeixinTitle',""));
			$WeixinPic		=	trim(Yii::app()->request->getParam('WeixinPic',""));
			$WeixinContent	=	trim(Yii::app()->request->getParam('WeixinContent',""));
			$WeiBoContent	=	trim(Yii::app()->request->getParam('WeiBoContent',""));
			$WeiBoPic		=	trim(Yii::app()->request->getParam('WeiBoPic',""));
			$PhoneMsg		=	trim(Yii::app()->request->getParam('PhoneMsg',""));
			
			
			if($fAppData->updateInformation($ChannelId, $TypeId, $Title, $NotifyTitle, $Abstract, $ImageUrl, $DetailUrl, $Content, $WeixinTitle, $WeixinPic, $WeixinContent, $WeiBoContent, $WeiBoPic, $PhoneMsg, $InformationId)>0){
				$this->exitWithSuccess("保存消息成功","/fAppMsgHistory/modify?InformationId=".$InformationId);
			}else{
				$this->alert('error',"保存消息失败");
			}		
		}
		$this->render("modify",$this->renderData);
	}
	
	public function actionSendTestMsg()
	{
		$data=array(
			"return_code"	=>	-1,
			"return_msg"	=>	"unknown",
		);
		
		$InformationId = Yii::app()->request->getParam("InformationId",'');
		if(empty($InformationId)){
			$data["return_code"]	=	-1001;
			$data["return_msg"]		=	"参数错误，请输入InformationId。";
			echo json_encode($data,true);
			return;
		}
		$TestPhone = Yii::app()->request->getParam("TestPhone",'');
		if(empty($TestPhone)){
			$data["return_code"]	=	-1001;
			$data["return_msg"]		=	"参数错误，请输入TestPhone。";
			echo json_encode($data,true);
			return;
		}
		$fAppData=new FAppData();
		$Information=$fAppData->getInformationById($InformationId);
		if(count($Information)==0){
			$data["return_code"]	=	-1002;
			$data["return_msg"]		=	"参数值错误，请输入正确的InformationId。";
			echo json_encode($data,true);
			return;				
		}
		$TestPhone=str_replace(array("\n","\r","，","。",".",";","'","\"","`","~","#","%","*"), ",", $TestPhone);
		$phones=explode(",",$TestPhone);
		
		$ChannelDef=$fAppData->getChannelById($Information["ChannelId"]);
		if(count($ChannelDef)>0){
			if($ChannelDef["Status"]==0){
				$ChannelShownPhones=",".$ChannelDef["TestPhone"];
				foreach ($phones as $p){
					if(strpos($ChannelShownPhones, $p)==false){
						$data["return_code"]	=	-1003;
						$data["return_msg"]		=	"对不起，本频道消息目前只能针对白名单用户发送，请检查发送号码(".$p.")，重新提交！";
						echo json_encode($data,true);
						return;						
					}					
				}
			}
		}
		
		$fMsgDepend=new FAppMsgDepend();
		
		$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
		$picUrl=sprintf("%s%s",$appConfig["FApp_game_domain"],$fAppData->getFileNameByFielId($Information["ImageUrl"]));
		
		$havePromoterId=false;
		foreach ($phones as $p){
			$promoterRow=$fAppData->getPromoterByPhone($p);
			if(count($promoterRow)>0){
				$fAppData->insertPromoterMsg($promoterRow["PromoterId"], $InformationId);
				$havePromoterId=true;
			}
		}
		if($havePromoterId==false){
			$data["return_code"]	=	-1003;
			$data["return_msg"]		=	"手机号码没有对应的推广员，请输入正确的手机号码。";
			echo json_encode($data,true);
			return;				
		}
		
		if($fMsgDepend->sendMsg($Information["SessionId"], $Information["Title"], $Information["DetailUrl"], 
				$Information["Abstract"], $Information["Content"], $picUrl,
				 $Information["ChannelId"], $Information["ChannelTypeId"], $Information["NotifyTitle"], $phones,"",true,$InformationId)){
			$data["return_code"]	=	0;
			$data["return_msg"]	=	"send successful.";
		}else{
			$data["return_code"]	=	-1003;
			$data["return_msg"]		=	$fMsgDepend->_LastErrorMessage;				
		}
		echo json_encode($data,true);
	}
	
	private function getSendingPhoneList($Information)
	{
		$phoneList=array();
		if(isset($Information["PhoneList"])){
			$phones=explode(",",$Information["PhoneList"]);
			foreach ($phones as $p){
				if(empty($p)==false){
					$phoneList[]=$p;
				}
			}
		}
		return $phoneList;
	}
	
	private function getPreviewPhoneTxt($Information)
	{
		if(isset($Information["PhoneListFileCounts"])){
			$phones=explode(",",$Information["PhoneList"]);
			if($Information["PhoneListFileCounts"]>6){
				$phoneTxt="";
				$indexCount=0;
				foreach ($phones as $p){
					if(empty($p)==false){
						$phoneTxt.=",".$p;
						$indexCount++;
					}
					if($indexCount>=3)
						break;
				}
				$phoneTxt.=",...\n...,\n";
				$indexCount=0;
				$posIndex=count($phones)-1;
				$phoneLastArr=array();
				for(;$posIndex>0;$posIndex--){
					$p=$phones[$posIndex];
					if(empty($p)==false){
						$phoneLastArr[]=$p;
						$indexCount++;
					}
					if($indexCount>=3){
						break;
					}
				}
				$phoneTxt.=$phoneLastArr[2].",".$phoneLastArr[1].",".$phoneLastArr[0];
				return substr($phoneTxt, 1);
				
			}else{
				$phoneTxt="";
				foreach ($phones as $p){
					if(empty($p)==false){
						$phoneTxt.=",".$p;
					}
				}
				return substr($phoneTxt,1);
			}			
		}
		return "";
	}
	
	public function actionMsgUser()
	{
		$InformationId = Yii::app()->request->getParam("InformationId",'');
		if(empty($InformationId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$fAppData=new FAppData();
		$Information=$fAppData->getInformationById($InformationId);
		if(count($Information)==0){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$this->renderData["Information"]=$Information;
		$this->renderData["PhonePreview"]=$this->getPreviewPhoneTxt($Information);
		
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$sendTime		=	Yii::app()->request->getParam("sendTime",'');
			$scheduleTime	=	Yii::app()->request->getParam("scheduleTime",'');
			if($sendTime=="now"){
				$scheduleTime="";
			}
			$needUpdatePhoneList=false;
			$phoneList="";
			$phoneListCount=0;
			$repeatPhone=array();
			$haveRepeatPhone="";
			if(isset($_FILES["PhoneFile"])){
				$files=$_FILES["PhoneFile"];
				if(empty($files["tmp_name"])==false){
					$phoneList=file_get_contents($files["tmp_name"]);
					$phoneList=str_replace(array("\n","\r","，","。",".",";","'","\"","`","~","#","%","*"), ",", $phoneList);
					if(empty($phoneList)==false){
						$phones=explode(",",$phoneList);
						foreach ($phones as $p){
							if(empty($p)==false){
								if(isset($repeatPhone[$p])){
									$haveRepeatPhone=$p;
									break;
								}
								$repeatPhone[$p]=1;
								$phoneListCount++;
							}
						}
						if($phoneListCount>0){
							$needUpdatePhoneList=true;
						}
					}
				}
			}
			if(empty($haveRepeatPhone)==false){
				$this->alert('error',"手机号吗:".$haveRepeatPhone."重复。");
			}else{
				if($fAppData->updateInfomationPhoneList($InformationId, $scheduleTime, $phoneList, $phoneListCount, $needUpdatePhoneList)){
					$this->exitWithSuccess("保存发送设置成功","/fAppMsgHistory/msgUser?InformationId=".$InformationId);
				}else{
					$this->alert('error',"保存发送设置失败");
				}
			}
		}		
		$this->render("msgUser",$this->renderData);
	}

	public function actionDel()
	{
		$this->_actionDel($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url);
	}
	private function getPendingSendPhoneList($ChannelId,$fAppData,$phoneList)
	{
		$ChannelDef=$fAppData->getChannelById($ChannelId);
		if(count($ChannelDef)>0 && $ChannelDef["Status"]==0){
			$phoneOutput=array();
			$ChannelShownPhones=",".$ChannelDef["TestPhone"];
			foreach ($phoneList as $p){
				if(strpos($ChannelShownPhones, $p)==false){
					continue;
				}
				$phoneOutput[]=$p;
			}
			return $phoneOutput;
		}
		return $phoneList;
	}
	public function actionSendMsg()
	{
		$data=array(
				"return_code"	=>	-1,
				"return_msg"	=>	"unknown",
		);
		
		$InformationId = Yii::app()->request->getParam("InformationId",'');
		if(empty($InformationId)){
			$data["return_code"]	=	-1001;
			$data["return_msg"]		=	"参数错误，请输入InformationId。";
			echo json_encode($data,true);
			return;
		}
		
		$fAppData=new FAppData();
		$Information=$fAppData->getInformationById($InformationId);
		if(count($Information)==0){
			$data["return_code"]	=	-1002;
			$data["return_msg"]		=	"参数值错误，请输入正确的InformationId。";
			echo json_encode($data,true);
			return;
		}
		
		$finishedCount	=	Yii::app()->request->getParam("finished",0);
		$sentNumPer		=	$fAppData->getSystemConfigItem("msg.send.num.per",100);
		
		$fMsgDepend=new FAppMsgDepend();
		
		$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
		$picUrl=sprintf("%s%s",$appConfig["FApp_game_domain"],$fAppData->getFileNameByFielId($Information["ImageUrl"]));
		$phoneList=$this->getSendingPhoneList($Information);
		$phoneList=$this->getPendingSendPhoneList($Information["ChannelId"],$fAppData,$phoneList);	//取出不在白名单的手机号码
		
		$startIndex=$finishedCount;
		$endIndex=$startIndex+$sentNumPer-1;
		$phoneListCount=count($phoneList);
		if($endIndex>=$phoneListCount){
			$endIndex=$phoneListCount-1;
		}
		$phoneSending=array();
		for($posIndex=$startIndex;$posIndex<=$endIndex;$posIndex++){
			$phoneSending[]=$phoneList[$posIndex];
		}
		if($fMsgDepend->sendMsg($Information["SessionId"], $Information["Title"], $Information["DetailUrl"],
				$Information["Abstract"], $Information["Content"], $picUrl,
				$Information["ChannelId"], $Information["ChannelTypeId"], $Information["NotifyTitle"], $phoneSending,"",true,$InformationId)){
			
			$prmoters=$fAppData->getPromoterIdByPhones($phoneSending);
			foreach ($phoneSending as $p){
				$promtoerId="";
				foreach ($prmoters as $row){
					if($row["PhoneNo"]==$p){
						$promtoerId=$row["PromoterId"];
						break;
					}
				}
				if(empty($promtoerId)==false){
					$fAppData->insertPromoterMsg($promtoerId, $InformationId);	
				}				
			}
			$data["total_num"]		=   $phoneListCount;
			$data["finished"]		=   $finishedCount+ ($endIndex-$startIndex)+1;
			if($data["finished"]>=$data["total_num"]){
				$fAppData->updateInformationState(1, $InformationId,$fAppData->getSentCountByInformationId($InformationId));
			}
			$data["return_code"]	=	0;
			$data["return_msg"]		=	"send successful.";
		}else{
			$data["return_code"]	=	-1003;
			$data["return_msg"]		=	$fMsgDepend->_LastErrorMessage;
		}
		echo json_encode($data,true);		
	}

	public function actionResend()
	{
		$InformationId = Yii::app()->request->getParam("InformationId",'');
		if(empty($InformationId)){
			$this->exitWithError("参数错误","/fAppMsgHistory/hisIndex");
		}
		$fAppData=new FAppData();
		$Information=$fAppData->getInformationById($InformationId);
		if(count($Information)==0){
			$this->exitWithError("参数错误","/fAppMsgHistory/hisIndex");
		}
		if($Information["State"]!=1){
			$this->exitWithError("源消息未发送，无需重发","/fAppMsgHistory/hisIndex");
		}
		
		if($fAppData->insertInformation($Information["ChannelId"],$Information["ChannelTypeId"],$Information["Title"],$Information["NotifyTitle"],
			$Information["Abstract"],$Information["ImageUrl"],$Information["DetailUrl"],$Information["Content"],
			$Information["WeixinTitle"],$Information["WeixinPic"],$Information["WeixinContent"],$Information["WeiBoContent"],
			$Information["WeiBoPic"],$Information["PhoneMsg"],CGuidManager::GetFullGuid(),$Information["SourceType"])>0)
		{
			
			$InformationId=$fAppData->getLastInsertInformationId($Information["Title"]);
			$this->exitWithSuccess("复制重发消息成功","/fAppMsgHistory/modify?InformationId=".$InformationId);
		}else{
			$this->exitWithError("复制重发消息失败","/fAppMsgHistory/hisIndex");
		}
	}
}
?>