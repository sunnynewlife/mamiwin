<?php
LunaLoader::import("luna_lib.util.LunaMemcache");
LunaLoader::import("luna_lib.http.HttpInterface");

class WxBizController extends CController 
{
	private $_TOKEN				="ddf2015sumO9DS0jOkmSd5x";
	private $_EncodingAESKey	="yTsOO7eqtT3bMad3yGFdoE8v7Oj12tF5E9Z2uEB3oX1";
	
	public function actionIndex()
	{
		$signature	=Yii::app()->request->getParam('signature',"");
		$timestamp 	=Yii::app()->request->getParam('timestamp',"");
		$nonce 		=Yii::app()->request->getParam('nonce',"");
		$echoStr 	=Yii::app()->request->getParam('echostr',"");
		
		if(empty($signature)==false &&  empty($timestamp)==false  &&  empty($nonce)==false){
			$tmpArr = array($this->_TOKEN, $timestamp, $nonce);
			sort($tmpArr, SORT_STRING);
			$tmpStr = implode( $tmpArr);
			$tmpStr = sha1( $tmpStr );
			LunaLogger::getInstance()->info("[Compare Signature] receive($signature) calculate($tmpStr)");
			if( $tmpStr == $signature ){
				if(Yii::app()->request->getRequestType()=="POST"){
					$this->handleMessageEvent();
				}else{				
					echo $echoStr;
				}	 
			}else{
				echo "signature is invalid.";
			}
		}
	}
	
	private function handleMessageEvent()
	{
		//接收消息、事件
		$raw_post = file_get_contents("php://input");
		LunaLogger::getInstance()->info("[PostBody] $raw_post");
		if(empty($raw_post)==false){
			libxml_disable_entity_loader(true);
			$postObj = simplexml_load_string($raw_post, 'SimpleXMLElement', LIBXML_NOCDATA);
			$fromUsername = $postObj->FromUserName;
			$toUsername	  = $postObj->ToUserName;
			$msgType	  = $postObj->MsgType;
			switch ($msgType){
				case "event":
					//关注
					if($postObj->Event=="subscribe"){
						$this->doSubscribe($fromUsername, $toUsername);
					}else if($postObj->Event=="unsubscribe"){
						$this->doUnsubscribe($fromUsername, $toUsername);
					}
					break;
				case "text":
					$this->doUserSentMsg($fromUsername, $toUsername, $postObj->MsgId, $msgType, $postObj->CreateTime, $postObj->Content);
					break;
				case "image":
					$this->doUserSentMsg($fromUsername, $toUsername, $postObj->MsgId, $msgType, $postObj->CreateTime, sprintf("PicUrl=%s MediaId=%s",$postObj->PicUrl,$postObj->MediaId));
					break;
				case "voice":
					$this->doUserSentMsg($fromUsername, $toUsername, $postObj->MsgId, $msgType, $postObj->CreateTime, sprintf("Format=%s MediaId=%s",$postObj->Format,$postObj->MediaId));
					break;
				case "video":
				case "shortvideo":
					$this->doUserSentMsg($fromUsername, $toUsername, $postObj->MsgId, $msgType, $postObj->CreateTime, sprintf("ThumbMediaId=%s MediaId=%s",$postObj->ThumbMediaId,$postObj->MediaId));
					break;
				case "location":
					$this->doUserSentMsg($fromUsername, $toUsername, $postObj->MsgId, $msgType, $postObj->CreateTime, sprintf("X=%s Y=%s Scale=%s Label=%s",$postObj->Location_X,$postObj->Location_Y,$postObj->Scale,$postObj->Label));
					break;
				case "link":
					$this->doUserSentMsg($fromUsername, $toUsername, $postObj->MsgId, $msgType, $postObj->CreateTime, sprintf("Title=%s Description=%s Url=%s",$postObj->Title,$postObj->Description,$postObj->Url));
					break;
				default:
					echo "";
					break;
			}
		}
	}
	
	
	private function doSubscribe($fromUserName,$toUserName)
	{
		//增加关注者
		$fAppData=new FAppData();
		$fAppData->getPhoneNoByOpenId_InsertIfNotExist($fromUserName,true,"1");
		
		//回复关注消息
		$replyCfg=$fAppData->getSystemConfigItem("tencent.mq.subscribe","",true);
		if(isset($replyCfg) && count($replyCfg)>0 && count($replyCfg)<=10){
			$itemXmlFragment="";
			foreach ($replyCfg as $itemReply){
				$itemXmlFragment.=sprintf(WxMsgProtocol::$_REPLY_MSG_TYPE_NEWS_SUBITEM,
						$itemReply["Title"],$itemReply["Description"],$itemReply["PicUrl"],$itemReply["Url"]);
			}
			echo sprintf(WxMsgProtocol::$_REPLY_MSG_TYPE_NEWS,$fromUserName,$toUserName,time(),count($replyCfg),$itemXmlFragment);
		}else{
			echo "";
		}
	}
		
	private function doUnsubscribe($fromUserName,$toUserName)
	{
		$fAppData=new FAppData();
		$fAppData->updateFocusStatus($fromUserName,"2");
		//do nothing
		echo "";	
	}
	
	private function doUserSentMsg($fromUserName,$toUserName,$msgId,$msgType,$sendTime,$msgContent)
	{
		//记录用户发送的消息
		$sendTimeStr=date("Y-m-d H:i:s",$sendTime);
		$fAppData=new FAppData();
		$fAppData->insertQ_MsgHistory($fromUserName, $msgId, $msgType, $sendTime, $msgContent);
		
		//自动回复消息
		$atuoReplyTxtMsg=$fAppData->getSystemConfigItem("tencent.mq.msg.autoreply","");
		if(empty($atuoReplyTxtMsg)==false){
			echo sprintf(WxMsgProtocol::$_REPLY_MSG_TYPE_TEXT,$fromUserName,$toUserName,time(),$atuoReplyTxtMsg);
		}else{
			echo "";
		}		
	}
}