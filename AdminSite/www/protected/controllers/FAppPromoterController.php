<?php

LunaLoader::import("luna_lib.util.CGuidManager");

class FAppPromoterController extends TableMagtController 
{
	private $_title="分红管理后台-推广员基础信息查询";
	private $_next_url="/fAppPromoter/index";
	
	private $_tableName="";
	private $_searchName="";
	private $_columns=array();
	private $_primaryKey="";
	
	private $_FEEDBACK_KEY="promoter.feedback.reply";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
		$this->_SERACH_FIELD_COMPARE_TYPE=array("AppName" =>"like");
		
	}
	private function export($fAppData,$startDt, $endDt,$promoterPhone,$Channel)
	{
		$loginList=$fAppData->getPromoterInfo($startDt, $endDt,$promoterPhone,$Channel,-1,999);
		//BOM Magic Bytes
		$txtStr="\xEF\xBB\xBF推广员账号,注册时间,支付宝账号,支付宝账号姓名,注册来源". PHP_EOL;
		foreach ($loginList as $rowItem){
			$txtStr.=sprintf("%s,%s,%s,%s,%s%s",
					$rowItem["PhoneNo"],
					$rowItem["CreateDt"],
					$rowItem["AliPayNo"],
					$rowItem["AliPayName"],
					$rowItem["Channel"],
					PHP_EOL);
		}
		$currentTime=time();
		$fileName=sprintf("%s_%s.csv",date("Y-m-d",$currentTime),"Promoter");
		header("Content-Type:text/html;charset=utf-8");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".strlen($txtStr));
		Header("Content-Disposition: attachment; filename=" . $fileName);
		echo $txtStr;
	}
	public function actionQuery()
	{
		$this->renderData["PromoterList"]=array();
		$this->renderData["startDt"]="";
		$this->renderData["endDt"]="";
		$this->renderData["PromoterPhone"]="";
		$this->renderData["page"]="";
		$this->renderData["Channel"]="";
		
		$submit = trim(Yii::app()->request->getParam('search',0));
		if($submit){
			$startDt			=	trim(Yii::app()->request->getParam('startDt',""));
			$endDt				=	trim(Yii::app()->request->getParam('endDt',""));
			$promoterPhone		=	trim(Yii::app()->request->getParam('PromoterPhone',""));
			$Channel			=	trim(Yii::app()->request->getParam('Channel',""));
				
			$this->renderData["PromoterPhone"]=$promoterPhone;
			$this->renderData["startDt"]=$startDt;
			$this->renderData["endDt"]=$endDt;	
			$this->renderData["Channel"]=$Channel;
			
			$fAppData=new FAppData();
			$export= trim(Yii::app()->request->getParam('export',""));
			if($export){
				return $this->export($fAppData,$startDt, $endDt,$promoterPhone,$Channel);
			}
				
			$row_count = $fAppData->getPromoterInfoCount($startDt, $endDt,$promoterPhone,$Channel);
				
			if($row_count>0){
					
				$page_size = 50;
				$page_no  = Yii::app()->request->getParam('page_no',1);
			
				$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
				$total_page=$total_page<1?1:$total_page;
				$page_no=$page_no>($total_page)?($total_page):$page_no;
				$start = ($page_no - 1) * $page_size;
			
				$PromoterList=$fAppData->getPromoterInfo($startDt, $endDt,$promoterPhone,$Channel,$start,$page_size);
				$this->renderData["PromoterList"]=$PromoterList;
			
				$PageShowUrl="/fAppPromoter/query?search=1";
				if(empty($promoterPhone)==false){
					$PageShowUrl.="&PromoterPhone=".$promoterPhone;
				}
				if(empty($startDt)==false){
					$PageShowUrl.="&startDt=".$startDt;
				}
				if(empty($endDt)==false){
					$PageShowUrl.="&endDt=".$endDt;
				}
				if(empty($Channel)==false){
					$PageShowUrl.="&Channel=".$Channel;
				}				
				$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
				$this->renderData["page"]=$page_str;
			}			
		}
		$this->render("query",$this->renderData);
	}
	
	public function actionIndex()
	{
		$promoterData=array( "PhoneNo" => "","CreateDt" =>"","PhoneType" => "","LoginDt" => "","LoginIp" =>"","Deposit" =>0,
				"IncomeSummary" => 0,"TransferAmt" =>0, "Amount" => 0,"PayState" => "","NetAmount" =>0,
				"AliPaySummary" => 0,"GameAmountSummary" => 0, "GameList" =>array(),"AliPayList" =>array());
		$submit = trim(Yii::app()->request->getParam('search',0));
		if ($submit){
			$Phone=trim(Yii::app()->request->getParam("Phone",""));
			if(empty($Phone)==false){
				$fAppData=new FAppData();
				$Promoter=$fAppData->getPromoterByPhone($Phone);
				if(count($Promoter)>0){
					$promoterData["CreateDt"]		= $Promoter["CreateDt"];
					$promoterData["PhoneType"]		= $Promoter["PhoneType"];
					$promoterData["LoginDt"]		= $Promoter["LoginDt"];
					$promoterData["LoginIp"]		= $Promoter["LoginIp"];
					$promoterData["PayState"]		= $Promoter["PayState"];
					$promoterData["IncomeSummary"]	= $Promoter["IncomeSummary"];
					$promoterData["Amount"]			= $Promoter["Amount"];
					$promoterData["PhoneNo"]		= $Promoter["PhoneNo"];
					$promoterData["PromoterId"]		= $Promoter["PromoterId"];
					$promoterData["NetAmount"]		= $Promoter["NetAmount"];
					$promoterData["AliPaySummary"]	=$fAppData->getPromoterAliPaySummary($Promoter["PromoterId"]);
					$promoterData["GameAmountSummary"]=$fAppData->getPromoterGameAmountSummary($Promoter["PromoterId"]);
					
					$gameList	=	$fAppData->getPromoterSummaryByGame($Promoter["PromoterId"]);
					$gameProfit	=	$fAppData->getPromoterPayTransctionByGame($Promoter["PromoterId"]);
					
					$PrompterPhoneHistory=$fAppData->getPromoterPhoneHistoryByPhone($Phone);
					if(count($PrompterPhoneHistory)>0){
						$promoterData["PhoneType"]		= $PrompterPhoneHistory["PhoneType"];
						$promoterData["LoginDt"]		= $PrompterPhoneHistory["LoginDt"];
						$promoterData["LoginIp"]		= $PrompterPhoneHistory["LoginIp"];
					}
					
					$promoterGameList=array();
					$appIdAdded=array();
					foreach ($gameList as $row){
						$appId=$row["AppId"];
						if(isset($appIdAdded[$appId])){
							if(empty($row["DownloadCount"])==false &&  $row["DownloadCount"]>0){
								foreach ($promoterGameList as &$rowItem){
									if($rowItem["AppId"]==$appId){
										$rowItem["DownloadCount"]+=$row["DownloadCount"];
										break;
									}
								}		
							}							
						}else{
							$appIdAdded[$appId]="1";
							$item=array(
								"AppId"			=>$appId,
								"AppName"		=>$row["AppName"],
								"CreateDt"		=>$row["CreateDt"],
								"DownloadCount"	=>(empty($row["DownloadCount"])?0:$row["DownloadCount"]),
							);
							$promoterGameList[]=$item;
						}						
					}
					foreach ($gameProfit as $row){
						$appId=$row["AppId"];
						foreach ($promoterGameList as &$rowItem){
							if($rowItem["AppId"]==$appId){
								$rowItem["GameAmount"]= (empty($row["GameAmount"])?0:$row["GameAmount"]);
								$rowItem["TotalAmount"]=(empty($row["Amount"])?0:$row["Amount"])+(empty($row["TaxFee"])?0:$row["TaxFee"]);
								break;
							}
						}
					}
					$promoterData["GameList"]=$promoterGameList;
					$promoterData["AliPayList"]= $this->replaceAlipayNo($fAppData->getPromoterAliPayApply($Promoter["PromoterId"])); 
					LunaLogger::getInstance()->info(print_r($promoterData["AliPayList"],true));
				}
				
			}				
		}
		$this->renderData["Promoter"]=$promoterData;
		$this->render("index",$this->renderData);		
	} 
	
	private function replaceAlipayNo($AlipayList)
	{
		foreach ($AlipayList as & $row){
			if(strlen($row["AliPayNo"])>=7){
				$row["AliPayNo"]=substr($row["AliPayNo"], 0,3)."****".substr($row["AliPayNo"],strlen($row["AliPayNo"])-4);
			}else if(strlen($row["AliPayNo"])>=2){
				$row["AliPayNo"]=substr($row["AliPayNo"], 0,1)."****".substr($row["AliPayNo"],strlen($row["AliPayNo"])-1);
			}
		}
		return $AlipayList;
	}
	
	public function actionPayState()
	{
		$data=array(
				"return_code"	=>	-1,
				"return_msg"	=>	"unknown",
		);
		$PromoterId	= trim(Yii::app()->request->getParam('PromoterId',""));
		$PayState	=	Yii::app()->request->getParam('PayState',"-1");
		if(empty($PromoterId)==false && ($PayState==1 || $PayState==0)){
			$fAppData=new FAppData();
			if($fAppData->updatePromoterPayState($PromoterId, $PayState)){
				$data["return_code"]=0;
				$data["return_msg"]="success";
			}else{
				$data["return_code"]=-2;
				$data["return_msg"]="更新提现状态失败。";
			}			
		}
		echo json_encode($data,true);
	}
	
	
	public function actionProraterIndex()
	{
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if ($submit){
		
		}
		$this->render("proraterIndex",$this->renderData);		
	}

	public function actionFeedbackIndex()
	{
		$currentTime=time();
		$defaultStartDt		=	date("Y-m-d",$currentTime-(7*24*60*60));
		$defaultEndDt		=	date("Y-m-d",$currentTime);
		
		$feedback=array();
		$submit = trim(Yii::app()->request->getParam('search',0));
		$startDt	=trim(Yii::app()->request->getParam('startDt',$defaultStartDt));
		$endDt		=trim(Yii::app()->request->getParam('endDt',$defaultEndDt));
		$Phone		=trim(Yii::app()->request->getParam("Phone",""));
		$State		=trim(Yii::app()->request->getParam("State","0"));
		if(empty($startDt)){
			$startDt=$defaultStartDt;
		}
		if(empty($endDt)){
			$endDt=$defaultEndDt;
		}
		
		$this->renderData["startDt"]=$startDt;
		$this->renderData["endDt"]=$endDt;
		$this->renderData["Phone"]=$Phone;
		$this->renderData["State"]=$State;
		$this->renderData["Page"]="";
		
		$fAppData=new FAppData();
		$row_count =$fAppData->getFeedbackCount($startDt, $endDt, $Phone, $State);
		if($row_count>0){
			$page_size = 50;
			$page_no  = Yii::app()->request->getParam('page_no',1);
			
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
			
			$feedback=$fAppData->getFeedback($startDt, $endDt, $Phone, $State,$start,$page_size);
			
			$PageShowUrl="/fAppPromoter/feedbackIndex?search=1";
			$PageShowUrl.="&State=".$State;
			if(empty($Phone)==false){
				$PageShowUrl.="&Phone=".$Phone;
			}
			if(empty($startDt)==false){
				$PageShowUrl.="&startDt=".substr($startDt,0,10);
			}
			if(empty($endDt)==false){
				$PageShowUrl.="&endDt=".substr($endDt,0,10);
			}
			$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
			$this->renderData["Page"]=$page_str;
		}
		
		$this->renderData["Feedback"]=$feedback;
		$this->render("feedbackIndex",$this->renderData);		
	}
	public function actionFeedback()
	{
		$nextUrl="fAppPromoter/feedbackIndex";
		$feedbackId=trim(Yii::app()->request->getParam('feedbackId',""));
		if(empty($feedbackId)){
			$this->exitWithError("参数错误",$nextUrl);
		}
		$fAppData=new FAppData();
		$feedback=$fAppData->getFeedbackById($feedbackId);
		if($feedback==false){
			$this->exitWithError("参数错误",$nextUrl);
		}
		$this->renderData["Feedback"]=$feedback;
		
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$replyContent= trim(Yii::app()->request->getParam('ReplyContent',""));
			if(empty($replyContent)==false){
				if($fAppData->updateFeedbackReplyContent($replyContent, $feedbackId)>0){
					$msgChannels=$fAppData->getMsgChannels();
					$channelId="";
					$msgTypeId="";
					foreach ($msgChannels as $rowItem){
						if($rowItem["typeKey"]==$this->_FEEDBACK_KEY){
							$channelId=$rowItem["ChannelId"];
							$typeId=$rowItem["TypeId"];
							break;
						}
					}
					
					$ChannelId	=	1;
					$TypeId		=	1;
					
					$Channels=$fAppData->getMsgChannels();
					foreach ($Channels as $rowItem){
						if($rowItem["typeKey"]=="promoter.feedback.reply"){
							$ChannelId	=$rowItem["ChannelId"];
							$TypeId		=$rowItem["TypeId"];
						}
					}
					$txtConent	= 	$feedback["Content"];
					$valueLen	=	mb_strlen($txtConent,"utf-8");
					if( $valueLen > 15 ){
						$txtConent	=	mb_substr($txtConent,0,15,"utf-8")."……";
					}
					$Title		=	sprintf("您于%s提交的关于“%s”的意见反馈回复如下：",substr($feedback["CreateDt"], 0,10),$txtConent);
					$NotifyTitle=	$Title;
					$Abstract	=	$replyContent;
					$ImageUrl	=	"";
					$DetailUrl	=	"";
					$Content	=	$Abstract;
						
					$WeixinTitle	="";
					$WeixinPic		="";
					$WeixinContent	="";
					$WeiBoContent	="";
					$WeiBoPic		="";
					$PhoneMsg		="";
						
					$msgSessionId	=	CGuidManager::GetFullGuid();
					$sourceType		=	"System";		//系统自动消息
						
					if($fAppData->insertInformation($ChannelId, $TypeId, $Title, $NotifyTitle, $Abstract, $ImageUrl, $DetailUrl, $Content, $WeixinTitle, $WeixinPic, $WeixinContent, $WeiBoContent, $WeiBoPic, $PhoneMsg, $msgSessionId, $sourceType)>0){
						$InformationId=$fAppData->getLastInsertInformationId($Title);
						$fAppData->insertPromoterMsg($feedback["PromoterId"], $InformationId);
						$fMsgDepend=new FAppMsgDepend();
						$fMsgDepend->sendMsg($msgSessionId, $Title, $DetailUrl, $Abstract, $Content, $ImageUrl, $ChannelId, $TypeId, $NotifyTitle, $fAppData->getPromoterPhoneById($feedback["PromoterId"]), "",true,$InformationId);
					}
					$this->exitWithSuccess("处理反馈成功。","/fAppPromoter/feedbackIndex");
				}
			}
		}
		$this->render("feedbackDetail",$this->renderData);
	}
	
	
	private function exportAlipayLimit($phoneNo)
	{
		$fAppData=new FAppData();
		$alipayList=$fAppData->getPromoterInfo("", "",$phoneNo,"",-1,999);
		$maxLimit=$fAppData->getDefaultAlipayLimit();
		$minPayDays=$fAppData->getDefaultPayPeriod();
		//BOM Magic Bytes
		$txtStr="\xEF\xBB\xBF推广员账号,当前账户余额,当前提现额度,提现周期". PHP_EOL;
		foreach ($alipayList as $rowItem){
			$txtStr.=sprintf("%s,%s,%s,%s%s",
					$rowItem["PhoneNo"],
					$rowItem["Amount"],
					isset($rowItem["MaxAliPayAmount"])?$rowItem["MaxAliPayAmount"]:$maxLimit,
					isset($rowItem["MinPayReturn"])?$rowItem["MinPayReturn"]:$minPayDays,
					PHP_EOL);
		}
		$currentTime=time();
		$fileName=sprintf("%s_%s.csv",date("Y-m-d",$currentTime),"提现额度");
		header("Content-Type:text/html;charset=utf-8");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".strlen($txtStr));
		Header("Content-Disposition: attachment; filename=" . $fileName);
		echo $txtStr;
	}
	
	private function exportRegSrc($fAppData,$startDt, $endDt,$promoterPhone,$promoterSrc)
	{
		$PromoterList=$fAppData->getPromoterRegSrc($startDt, $endDt,$promoterPhone,$promoterSrc,-1,999);
		//BOM Magic Bytes
		$txtStr="\xEF\xBB\xBF推广手机号码,注册时间,注册来源,历史财富". PHP_EOL;
		foreach ($PromoterList as $rowItem){
			$txtStr.=sprintf("%s,%s,%s,%s%s",
					$rowItem["PhoneNo"],
					$rowItem["CreateDt"],
					$this->getChannelName($rowItem["Channel"],$this->renderData["RegSrcList"]),
					$rowItem["IncomeSummary"],
					PHP_EOL);
		}
		$currentTime=time();
		$fileName=sprintf("%s_%s.csv",date("Y-m-d",$currentTime),"注册来源");
		header("Content-Type:text/html;charset=utf-8");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".strlen($txtStr));
		Header("Content-Disposition: attachment; filename=" . $fileName);
		echo $txtStr;		
	}
	
	//提现额度查询
	public function actionAlipayLimitIndex()
	{
		$this->_tableName="Promoter";
		$this->_searchName="PhoneNo";
		$this->_SERACH_FIELD_COMPARE_TYPE=array("PhoneNo" =>"like");
		$this->_title="推广员提现额度";
		$this->_next_url="/fAppPromoter/alipayLimitIndex";
		
		$export=	trim(Yii::app()->request->getParam('export',0));
		if($export==1){
			$phoneNo=	trim(Yii::app()->request->getParam('PhoneNo',''));
			return $this->exportAlipayLimit($phoneNo);
		}else{
			$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"aplipayLimitIndex");
		}
	}
	protected function getPageRowsExtentData($data)
	{
		$fAppData=new FAppData();
		$maxLimit=$fAppData->getDefaultAlipayLimit();
		$minPayPeriodDays=$fAppData->getDefaultPayPeriod();
		foreach ($data as &$rowItem){
			if(isset($rowItem["MaxAliPayAmount"])==false){
				$rowItem["MaxAliPayAmount"]=$maxLimit;
			}
			if(isset($rowItem["MinPayReturn"])==false){
				$rowItem["MinPayReturn"]=$minPayPeriodDays;
			}
		}
		return $data;
	}

	public function actionAdjustAlipayLimit()
	{
		$this->_tableName="Promoter";
		$this->_primaryKey="PromoterId";
		$this->_next_url="/fAppPromoter/alipayLimitIndex";
		$this->_title="推广员提现额度";
		$this->_columns=array("MaxAliPayAmount","MinPayReturn");
		$this->_actionModify($this->_tableName,$this->_primaryKey,$this->_title,$this->_next_url,"Promoter",$this->_columns,"adjustAlipayLimit");
	}
	
	private function getChannelName($channelCode,$channelSrc)
	{
		if(isset($channelCode)==false || empty($channelCode)){
			$channelCode="";
		}
		foreach ($channelSrc as $channelRow){
			if($channelRow["code"]==$channelCode){
				return $channelRow["name"];
			}
		}
		return $channelCode;
	}
	
	public function actionRegSrcQuery()
	{
		$this->renderData["startDt"]="";
		$this->renderData["endDt"]="";
		$this->renderData["PromoterPhone"]="";
		$this->renderData["PromoterSrc"]="ALL";
		$this->renderData["page"]="";
		$this->renderData["PromoterList"]=array();
		
		$fAppData=new FAppData();
		$this->renderData["RegSrcList"]=$fAppData->getSystemConfigItem("promoter.reg.src","",true);
		
		$submit = trim(Yii::app()->request->getParam('search',0));
		if($submit){
			
			$startDt			=	trim(Yii::app()->request->getParam('startDt',""));
			$endDt				=	trim(Yii::app()->request->getParam('endDt',""));
			$promoterPhone		=	trim(Yii::app()->request->getParam('PromoterPhone',""));
			$promoterSrc		=	trim(Yii::app()->request->getParam('PromoterSrc',""));
			
			$this->renderData["PromoterPhone"]=$promoterPhone;
			$this->renderData["startDt"]=$startDt;
			$this->renderData["endDt"]=$endDt;
			$this->renderData["PromoterSrc"]=$promoterSrc;

			$export= trim(Yii::app()->request->getParam('export',""));
			if($export){
				return $this->exportRegSrc($fAppData,$startDt, $endDt,$promoterPhone,$promoterSrc);
			}
			
			$row_count = $fAppData->getPromoterRegSrcCount($startDt, $endDt,$promoterPhone,$promoterSrc);
			
			if($row_count>0){
				$page_size = 50;
				$page_no  = Yii::app()->request->getParam('page_no',1);
					
				$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
				$total_page=$total_page<1?1:$total_page;
				$page_no=$page_no>($total_page)?($total_page):$page_no;
				$start = ($page_no - 1) * $page_size;
					
				$PromoterList=$fAppData->getPromoterRegSrc($startDt, $endDt,$promoterPhone,$promoterSrc,$start,$page_size);
				foreach ($PromoterList as & $row){
					$row["ChannelName"]=$this->getChannelName($row["Channel"],$this->renderData["RegSrcList"]);
				}
				$this->renderData["PromoterList"]=$PromoterList;
					
				$PageShowUrl="/fAppPromoter/regSrcQuery?search=1";
				if(empty($promoterPhone)==false){
					$PageShowUrl.="&PromoterPhone=".$promoterPhone;
				}
				if(empty($startDt)==false){
					$PageShowUrl.="&startDt=".$startDt;
				}
				if(empty($endDt)==false){
					$PageShowUrl.="&endDt=".$endDt;
				}
				$PageShowUrl.="&PromoterSrc=".$promoterSrc;
				
				$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
				$this->renderData["page"]=$page_str;
			}				
		}
		$this->render("reg_src",$this->renderData);
	}
	
}
?>