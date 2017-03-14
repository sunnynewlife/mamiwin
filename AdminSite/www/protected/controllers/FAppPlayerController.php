<?php

LunaLoader::import("luna_lib.util.CGuidManager");

class FAppPlayerController extends TableMagtController 
{
	private $_title="分红管理后台-分红用户基础信息";
	private $_next_url="/fAppPlayer/index";
	
	private $_tableName="";
	private $_searchName="";
	private $_columns=array();
	private $_primaryKey="";
	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
	}
	private function export($fAppData,$startDt,$endDt,$phoneNo,$promoterPhone,$AppId,$OrderId="")
	{
		$PayList=$fAppData->getPlayPayHistory($startDt, $endDt,$phoneNo,$promoterPhone,$AppId,-1,999,$OrderId);
		$PayReturnList=$fAppData->getPayReturnHistory($startDt, $endDt,$phoneNo,$promoterPhone,$AppId);
		//BOM Magic Bytes
		$txtStr="\xEF\xBB\xBF分红用户账号,充值时间,充值金额,充值游戏,返利比例,返利金额,返利归属,订单号,状态". PHP_EOL;
		foreach ($PayList as $rowItem){
			$Amount="0.0";
			if(empty($rowItem["Amount"])==false){
				$Amount=bcadd($Amount, $rowItem["Amount"],2);
			}
			if(empty($rowItem["TaxFee"])==false){
				$Amount=bcadd($Amount, $rowItem["TaxFee"],2);
			}
			$CheckingId=$rowItem["CheckingID"];
			$PayReturnItem=$this->getPayReturnRecByCheckingId($CheckingId, $PayReturnList);

			$payStatus="";
			if(isset($PayReturnItem)){
				if($PayReturnItem["Status"]==1){
					$payStatus="冻结中";
				}else if($PayReturnItem["Status"]==2){
					$payStatus="已作废";
				}
			}
			$txtStr.=sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s%s",
				str_replace("+86-", "", $rowItem["PlayPhone"]),
				$rowItem["TransactDt"],
				empty($rowItem["GameAmount"])?"0.00":number_format($rowItem["GameAmount"],2,".",""),
				$rowItem["AppName"],
				number_format(bcmul($rowItem["PromoterProrate"], "100"),0,"","")."%",
				number_format($Amount,2,".",""),
				$rowItem["PhoneNo"],
				$rowItem["CheckingID"],
				$payStatus,
				PHP_EOL);
		}
		$currentTime=time();
		$fileName=sprintf("%s_%s.csv",date("Y-m-d",$currentTime),"充值");
		header("Content-Type:text/html;charset=utf-8");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".strlen($txtStr));
		Header("Content-Disposition: attachment; filename=" . $fileName);
		echo $txtStr;		
	}
	private function exportPlayLogin($fAppData,$startDt, $endDt,$phoneNo,$promoterPhone,$AppId)
	{
		$loginList=$fAppData->getPlayPayLoginHistory($startDt, $endDt,$phoneNo,$promoterPhone,-1,999,$AppId);
		//BOM Magic Bytes
		$txtStr="\xEF\xBB\xBF分红用户账号,所属推广员,登录游戏,登录时间". PHP_EOL;
		foreach ($loginList as $rowItem){
			$txtStr.=sprintf("%s,%s,%s,%s%s",
					str_replace("+86-", "", $rowItem["PlayerPhoneNo"]),
					$rowItem["PhoneNo"],
					$rowItem["AppName"],
					$rowItem["loginDt"],
					PHP_EOL);
		}
		$currentTime=time();
		$fileName=sprintf("%s_%s.csv",date("Y-m-d",$currentTime),"登录");
		header("Content-Type:text/html;charset=utf-8");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".strlen($txtStr));
		Header("Content-Disposition: attachment; filename=" . $fileName);
		echo $txtStr;		
	}
	
	private function getPayReturnRecByCheckingId($CheckingId,$PayReturnList)
	{
		foreach ($PayReturnList as $rowItem){
			if($rowItem["CheckingID"]==$CheckingId){
				return $rowItem;
			}
		}
	}
	
	private function frozen($checkingId)
	{
		$fAppData=new FAppData();
		$payReturnInfo=$fAppData->GetPayReturnByCheckingId($checkingId);
		if(count($payReturnInfo)>0){
			if($payReturnInfo["Status"]==0){
				$fAppData->FonzenByCheckingIdExist($checkingId);
			}
		}else{
			$fAppData->FonzenByCheckingIdFirst($checkingId);
		}		
	}
	private function unFrozen($checkingId)
	{
		$fAppData=new FAppData();
		$payReturnInfo=$fAppData->GetPayReturnByCheckingId($checkingId);
		if(count($payReturnInfo)>0 && isset($payReturnInfo["Status"]) && $payReturnInfo["Status"]==1){
			$fAppData->UnFronzeByCheckingId($checkingId);
		}		
	}
	private function payReturn($checkingId)
	{
		$fAppData=new FAppData();
		$payReturnInfo=$fAppData->GetPayReturnByCheckingId($checkingId);
		if(count($payReturnInfo)>0){
			if($fAppData->PayReturnByCheckingIdExist($checkingId)==1){
				$msgFields=$fAppData->getNotifyMessageFields($checkingId);
				if(count($msgFields)>0){
					$PlayDisplayPhone=$msgFields["PhoneNo"];
					$PlayDisplayPhone=str_replace("+86-", "", $PlayDisplayPhone);
					$PlayDisplayPhone=substr($PlayDisplayPhone, 0,3)."***".substr($PlayDisplayPhone,-3);
					$this->sendNotifyMsg($msgFields["PromoterId"], $PlayDisplayPhone, $msgFields["TransactDt"], $msgFields["AppName"], $msgFields["GameAmount"], $msgFields["Amount"],$msgFields["PromoterPhone"],$msgFields["ClientType"]);
				}
			}
		}else {
			if($fAppData->PayReturnByCheckingIdFirst($checkingId)==1){
				$msgFields=$fAppData->getNotifyMessageFields($checkingId);
				if(count($msgFields)>0){
					$PlayDisplayPhone=$msgFields["PhoneNo"];
					$PlayDisplayPhone=str_replace("+86-", "", $PlayDisplayPhone);
					$PlayDisplayPhone=substr($PlayDisplayPhone, 0,3)."***".substr($PlayDisplayPhone,-3);
					$this->sendNotifyMsg($msgFields["PromoterId"], $PlayDisplayPhone, $msgFields["TransactDt"], $msgFields["AppName"], $msgFields["GameAmount"], $msgFields["Amount"],$msgFields["PromoterPhone"],$msgFields["ClientType"]);
				}
			}	
		}		
	}
	private function sendNotifyMsg($PromoterId,$PlayDisplayPhone,$PayDate,$AppName,$GameAmount,$Amount,$PromoterPhone,$ClientType)
	{
		$fAppMsgDepend=new FAppMsgDepend();
		$fAppData=new FAppData();
		$cGuidManager = new CGuidManager();
		$sessionId = $cGuidManager->GetGuid();

		$channelId			=13;
		$channelType	 	=2;
		$msgTypeId			=4;
		$appId 				=10000;
		
		$appCfg=LunaConfigMagt::getInstance()->getAppConfig();
		if(isset($appCfg["FApp_msg_AppID"])){
			$appId=$appCfg["FApp_msg_AppID"];
		}
				
		$para = array(
				'MsgType'			=>$msgTypeId,
				'PromoterId'		=>$PromoterId,
				'MsgBody'			=>'',
		);
		$msgId = $fAppData->insertPromoterAmountMsg($para);
		if($msgId > 0){
			
			$msg = "收入作废提醒:".$Amount;
			$content = json_encode(array(
					'id'			=> $msgId,
					'text' 			=> $msg,
					'channelId' 	=> $channelId,
					'channelType' 	=> $channelType,
					'type' 			=> 2,
			));
			
			$msgBody = array(
					'title'			=>	"收入作废提醒",
					'notifyTitle'	=>	"收入作废提醒",
					'image'			=>	"",
					'sessionId'		=>	$sessionId,
					'scheduleTime'	=>	"",
					'typeId'		=>	$msgTypeId,
					'content'		=>	$content,
					'AppName'		=>	$AppName,
					'GameAmount'	=>	$GameAmount,
					'Amount'		=>	$Amount,
					'PayDate'		=>	$PayDate,
					'PhoneDisplay'	=>	$PlayDisplayPhone,
					'content'		=>	$content,
					'transactDt'	=>	date("Y-m-d H:i:s"),
			);
			
			$paras = array(
					'MsgType'		=>	$msgTypeId,
					'PromoterId'	=>	$PromoterId,
					'MsgBody'		=>	json_encode($msgBody),
					'MsgId'			=>	$msgId,
			);
			if($fAppData->updatePromoterAmountMsg($paras)==1){
				if(ModuleTrans::isWebLogin($ClientType)){
					$hps = new HttpInterface("SmsSubmit","FHPayReturnMsg");
					$param = array(
							'seq'		=>	$cGuidManager->GetGuid(),
							'phone'		=>	$PromoterPhone,
							'msg'		=>	$Amount,
							'timeStamp'	=>	time(),
					) ;
					$data=$hps->submit($param);
				}else{
					$fAppMsgDepend->sendMsg($sessionId,$msgBody["title"],"",$msgBody["title"],$content,"",$channelId,$msgTypeId,$msgBody["notifyTitle"],$PromoterPhone,"");
				}
			}			
		}
	}
	
	public function actionIndex()
	{
		$this->renderData["PayList"]=array();
		$this->renderData["startDt"]="";
		$this->renderData["endDt"]="";
		$this->renderData["Phone"]="";
		$this->renderData["PromoterPhone"]="";
		$this->renderData["GameAmount"]="0";
		$this->renderData["PromoterAmount"]="0";
		$this->renderData["AppId"]="";
		$this->renderData["page"]="";
		$this->renderData["OrderId"]="";
		
		
		$payOperate=trim(Yii::app()->request->getParam('PayOperate',''));
		$checkingId=trim(Yii::app()->request->getParam('CheckingID',''));
		
		if(empty($payOperate)==false && empty($checkingId)==false){
			if($payOperate=="frozen"){
				$this->frozen($checkingId);
			}else if($payOperate=="unfrozen"){
				$this->unFrozen($checkingId);
			}else if($payOperate=="payreturn"){
				$this->payReturn($checkingId);
			}
		}		
		
		$submit = trim(Yii::app()->request->getParam('search',0));
		if($submit){
			$currentTime=time();
			$defaultStartDt		=	date("Y-m-d 00:00:00",$currentTime-(7*24*60*60));
			$defaultEndDt		=	date("Y-m-d 23:59:59",$currentTime);
			$startDt			=	trim(Yii::app()->request->getParam('startDt',$defaultStartDt));
			$endDt				=	trim(Yii::app()->request->getParam('endDt',$defaultEndDt));
			$phoneNo			=	trim(Yii::app()->request->getParam('Phone',""));
			$promoterPhone		=	trim(Yii::app()->request->getParam('PromoterPhone',""));
			$AppId				=	trim(Yii::app()->request->getParam('AppId',""));
			$OrderId			=	trim(Yii::app()->request->getParam('orderId',""));
			
			$this->renderData["Phone"]=$phoneNo;
			$this->renderData["PromoterPhone"]=$promoterPhone;
			$this->renderData["AppId"]=$AppId;
			$this->renderData["startDt"]=$startDt;
			$this->renderData["endDt"]=$endDt;
			$this->renderData["OrderId"]=$OrderId;
				
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
			$fAppData=new FAppData();
			$export= trim(Yii::app()->request->getParam('export',""));
			if($export){
				return $this->export($fAppData,$startDt, $endDt,$phoneNo,$promoterPhone,$AppId,$OrderId);
			}
				
			$row_count = $fAppData->getPlayPayHistoryCount($startDt, $endDt,$phoneNo,$promoterPhone,$AppId,$OrderId);
			
			if($row_count>0){
			
				$page_size = 50;
				$page_no  = Yii::app()->request->getParam('page_no',1);

				$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
				$total_page=$total_page<1?1:$total_page;
				$page_no=$page_no>($total_page)?($total_page):$page_no;
				$start = ($page_no - 1) * $page_size;
				
				$PayList=$fAppData->getPlayPayHistory($startDt, $endDt,$phoneNo,$promoterPhone,$AppId,$start,$page_size,$OrderId);
				if(count($PayList)>0){
					//充值流水 可操作状态 设置
					
					//缺省可退款天数
					$defaultPayReturnDays=$fAppData->getDefaultPayPeriod();
					//退款流水数据
					$PayReturnList=$fAppData->getPayReturnHistory($startDt, $endDt,$phoneNo,$promoterPhone,$AppId);

					//退款充值启动时间，冻结的充值金额应当在这时间之后
					$PayReturnStartDt=date_create($fAppData->getSystemConfigItem("promoter.tixian.min.start"));
					foreach ($PayList as & $rowItem){
						$CheckingId=$rowItem["CheckingID"];
						$TransactDt=$rowItem["TransactDt"];
						$PayReturnItem=$this->getPayReturnRecByCheckingId($CheckingId, $PayReturnList);
						//可退款的天数
						$userPayReturnDays= (isset($rowItem["MinPayReturn"])?$rowItem["MinPayReturn"]:$defaultPayReturnDays);
						
						$PayReturnDt	=date_create(date("Y-m-d 00:00:00",$currentTime-($userPayReturnDays*24*60*60)));
						if($PayReturnStartDt>$PayReturnDt){
							$PayReturnDt=$PayReturnStartDt;
						}
						
						$TransactDtObj	=date_create($TransactDt);
						$opStatus=array(
							"canFrozen"		=>	0,
							"canReturn"		=>	0,
							"showReturn"	=>	0,
							"canUnFrozen"	=>	0,
						);
						if(isset($PayReturnItem)){
							if($PayReturnItem["Status"]==1){
								$opStatus["canUnFrozen"]=1;
								$opStatus["canReturn"]=1;
							}else if($PayReturnItem["Status"]==0){
								if($TransactDtObj>=$PayReturnDt){
									$opStatus["canFrozen"]=1;
									$opStatus["canReturn"]=1;
								}
							}else if($PayReturnItem["Status"]==2){
								$opStatus["showReturn"]=1;
							}
							
						}else if($TransactDtObj>=$PayReturnDt) {  //没有退款流水记录 且，在退款期内
							$opStatus["canFrozen"]=1;
							$opStatus["canReturn"]=1;
						}
						$rowItem["opStatus"]=$opStatus;
					}
				}
				
				$this->renderData["PayList"]=$PayList;
				
				$PageShowUrl="/fAppPlayer/index?search=1";
				if(empty($AppId)==false){
					$PageShowUrl.="&AppId=".$AppId;
				}
				if(empty($phoneNo)==false){
					$PageShowUrl.="&Phone=".$phoneNo;
				}
				if(empty($promoterPhone)==false){
					$PageShowUrl.="&PromoterPhone=".$promoterPhone;
				}
				if(empty($startDt)==false){
					$PageShowUrl.="&startDt=".substr($startDt,0,10);
				}
				if(empty($endDt)==false){
					$PageShowUrl.="&endDt=".substr($endDt,0,10);
				}
				$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
				$this->renderData["page"]=$page_str;
				
				if(count($PayList)>0){
					$GameAmount=0;
					$PromoterAmount=0;
					foreach ($PayList as $theRowItem){
						$opStatus=$theRowItem["opStatus"];
						if(empty($theRowItem["GameAmount"])==false && $opStatus["showReturn"]==0){
							$GameAmount=bcadd($GameAmount, $theRowItem["GameAmount"],2);
							$PromoterAmount=bcadd($PromoterAmount,bcmul($theRowItem["GameAmount"],$theRowItem["PromoterProrate"],2),2);
						}
					}
					$this->renderData["GameAmount"]=number_format($GameAmount,2,".",",");
					$this->renderData["PromoterAmount"]=number_format($PromoterAmount,2,".",",");
				}
			}
		}else {
			$currentTime=time();
			$this->renderData["startDt"]=date("Y-m-d",$currentTime-(7*24*60*60));
			$this->renderData["endDt"]=date("Y-m-d",$currentTime);
		}
		$this->render("index",$this->renderData);		
	} 
	
	public function actionLoginIndex()
	{
		$this->renderData["LoginList"]=array();
		$this->renderData["startDt"]="";
		$this->renderData["endDt"]="";
		$this->renderData["Phone"]="";
		$this->renderData["PromoterPhone"]="";
		$this->renderData["page"]="";
		$this->renderData["AppId"]="";
		
		$fAppData=new FAppData();
		$this->renderData["GameList"]=$fAppData->getApp();
				
		$submit = trim(Yii::app()->request->getParam('search',0));
		if($submit){
			$currentTime=time();
			$defaultStartDt		=	date("Y-m-d 00:00:00",$currentTime-(7*24*60*60));
			$defaultEndDt		=	date("Y-m-d 23:59:59",$currentTime);
			$startDt			=	trim(Yii::app()->request->getParam('startDt',$defaultStartDt));
			$endDt				=	trim(Yii::app()->request->getParam('endDt',$defaultEndDt));
			$phoneNo			=	trim(Yii::app()->request->getParam('Phone',""));
			$promoterPhone		=	trim(Yii::app()->request->getParam('PromoterPhone',""));
			$AppId				=	trim(Yii::app()->request->getParam('AppId',""));
			
			$this->renderData["Phone"]=$phoneNo;
			$this->renderData["PromoterPhone"]=$promoterPhone;
			$this->renderData["startDt"]=$startDt;
			$this->renderData["endDt"]=$endDt;
			$this->renderData["AppId"]=$AppId;
			
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
			
			$export= trim(Yii::app()->request->getParam('export',""));
			if($export){
				return $this->exportPlayLogin($fAppData,$startDt, $endDt,$phoneNo,$promoterPhone,$AppId);
			}
			
			$row_count = $fAppData->getPlayPayLoginHistoryCount($startDt, $endDt,$phoneNo,$promoterPhone,$AppId);
				
			if($row_count>0){
					
				$page_size = 50;
				$page_no  = Yii::app()->request->getParam('page_no',1);
			
				$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
				$total_page=$total_page<1?1:$total_page;
				$page_no=$page_no>($total_page)?($total_page):$page_no;
				$start = ($page_no - 1) * $page_size;
			
				$loginList=$fAppData->getPlayPayLoginHistory($startDt, $endDt,$phoneNo,$promoterPhone,$start,$page_size,$AppId);
				$this->renderData["LoginList"]=$loginList;
			
				$PageShowUrl="/fAppPlayer/loginIndex?search=1";
				if(empty($phoneNo)==false){
					$PageShowUrl.="&Phone=".$phoneNo;
				}
				if(empty($promoterPhone)==false){
					$PageShowUrl.="&PromoterPhone=".$promoterPhone;
				}
				if(empty($startDt)==false){
					$PageShowUrl.="&startDt=".substr($startDt,0,10);
				}
				if(empty($endDt)==false){
					$PageShowUrl.="&endDt=".substr($endDt,0,10);
				}
				if(empty($AppId)==false){
					$PageShowUrl.="&AppId=".$AppId;
				}
				$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
				$this->renderData["page"]=$page_str;
			}						
		}else{
			$currentTime=time();
			$this->renderData["startDt"]=date("Y-m-d",$currentTime-(7*24*60*60));
			$this->renderData["endDt"]=date("Y-m-d",$currentTime);				
		}
		$this->render("loginIndex",$this->renderData);
	}
}
?>