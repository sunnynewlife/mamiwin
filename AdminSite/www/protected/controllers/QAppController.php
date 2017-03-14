<?php

class QAppController extends TableMagtController 
{
	private $_title="分红管理后台-微信游戏配置";
	private $_next_url="/qApp/index";
	
	private $_tableName="Q_App";
	private $_searchName="";
	private $_columns=array();
	private $_primaryKey="IDX";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
	}
	
	public function actionIndex()
	{
		$fAppData=new FAppData();
		$qAppInfo=$fAppData->getQ_App();
		$qAppProfit=$fAppData->getQ_AppProfitSummary();
		$agencyId="";
		$WxPromoterInfo=$fAppData->getSystemConfigItem("tencent.mq.cfg.promoter","",true);
		if(count($WxPromoterInfo)>0){
			$agencyId=$fAppData->getAgencyIdByPromoterId($WxPromoterInfo["PromoterId"]); 
		}
		foreach ($qAppInfo as &$qAppItem){
			$appGameAmount="0";
			$appAmount="0";
			$qAppProfitItem=$this->getAppProfitByAppId($qAppProfit, $qAppItem["AppId"]);
			if(count($qAppProfitItem)>0){
				$appGameAmount=number_format($qAppProfitItem["GameAmount"],2,".",",");
				$appAmount=number_format($qAppProfitItem["Amount"],2,".",",");
			}
			$qAppItem["GameAmount"]=$appGameAmount;
			$qAppItem["Amount"]=$appAmount;
			$currentAppGift=$fAppData->getCurrentAppGiftList($qAppItem["AppId"]);
			$evnStatus=(empty($qAppItem["EvnUrl1"])==false || empty($qAppItem["EvnUrl2"])==false || empty($qAppItem["EvnUrl3"])==false || empty($qAppItem["EvnUrl4"])==false || empty($qAppItem["EvnUrl5"])==false)?"1":"0";
			$qAppItem["HavingEvn"]=$evnStatus;
			$qAppItem["HavingGift"]=$this->caclGiftStatus($currentAppGift,$fAppData);
			$qAppItem["AgencyId"]=$agencyId;
		}
		$this->renderData["qApps"]=$qAppInfo;
		$this->render("index",$this->renderData);
	}
	private function caclGiftStatus($appGiftList,$fAppData)
	{
		if(count($appGiftList)==0){
			return "0";
		}
		$currentDt=time();
		foreach ($appGiftList as $giftItem){
			if($giftItem["ActRestCount"]>0){
				$openDt=$giftItem["OpenDt"];
				$endDt=$giftItem["EndDt"];
				
				if(isset($endDt) && empty($endDt)==false){
					$t=date_create(substr($endDt, 0,10)." 23:59:59");
					if($currentDt>$t->getTimestamp()){
						break;
					}
				}
				if(isset($openDt) && empty($openDt)==false){
					$t=date_create(substr($openDt, 0,10)." 00:00:00");
					if($currentDt<$t->getTimestamp()){
						break;
					}
				}				
				return "1";
			}
		}
		return "0";
	}
	
	private function getAppProfitByAppId($qAppProfit,$AppId)
	{
		foreach ($qAppProfit as $qProfitItem){
			if($qProfitItem["AppId"]==$AppId){
				return $qProfitItem;
			}
		}
		return array();
	}
	
	
	public  function actionAdd()
	{
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$AppId				=	Yii::app()->request->getParam("AppId");
			$BaseProrate		=	Yii::app()->request->getParam("BaseProrate");
			$IsFirstPublish		=	Yii::app()->request->getParam("IsFirstPublish");
			$AppOrder			=	Yii::app()->request->getParam("AppOrder");
			$Status				=	Yii::app()->request->getParam("Status");
			$AclList			=	Yii::app()->request->getParam("AclList");
			$IsSinglePublish	=	Yii::app()->request->getParam("IsSinglePublish");
			$IsOtherPublish		=	Yii::app()->request->getParam("IsOtherPublish");
			
			$EvnUrl1			=	Yii::app()->request->getParam("EvnUrl1");
			$EvnUrl2			=	Yii::app()->request->getParam("EvnUrl2");
			$EvnUrl3			=	Yii::app()->request->getParam("EvnUrl3");
			$EvnUrl4			=	Yii::app()->request->getParam("EvnUrl4");
			$EvnUrl5			=	Yii::app()->request->getParam("EvnUrl5");
			
			$EvnTitle1			=	Yii::app()->request->getParam("EvnTitle1");
			$EvnTitle2			=	Yii::app()->request->getParam("EvnTitle2");
			$EvnTitle3			=	Yii::app()->request->getParam("EvnTitle3");
			$EvnTitle4			=	Yii::app()->request->getParam("EvnTitle4");
			$EvnTitle5			=	Yii::app()->request->getParam("EvnTitle5");
			
			$PromoteDt			=	Yii::app()->request->getParam("PromoteDt");
			$PromoteProrate		=	Yii::app()->request->getParam("PromoteProrate");
			$PromoteStartDt		=	Yii::app()->request->getParam("PromoteStartDt");
			
			if(empty($PromoteProrate)){
				$PromoteDt="";
				$PromoteStartDt="";
			}else{
				$PromoteProrate=bcdiv($PromoteProrate, "100",2);
			}
			$BaseProrate=bcdiv($BaseProrate, "100",2);
			
			$fAppData=new FAppData();
			if($fAppData->insertQ_App($AppId, $Status, $AclList, $AppOrder, $IsFirstPublish, $BaseProrate, $PromoteProrate, $PromoteDt,$IsSinglePublish,$IsOtherPublish,$EvnUrl1,$EvnUrl2,$EvnUrl3,$EvnUrl4,$EvnUrl5,$EvnTitle1,$EvnTitle2,$EvnTitle3,$EvnTitle4,$EvnTitle5,$PromoteStartDt)>0){
				$this->exitWithSuccess("增加微信游戏","/qApp/index");
			}else{
				$this->alert('error',"增加微信游戏失败");
			}			
		}
		$this->render("add",$this->renderData);
	}
	
	public function actionDel()
	{
		return $this->_actionDel($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url);
	}
	
	public function actionModify()
	{
		$idx	=	Yii::app()->request->getParam("IDX");
		if(empty($idx)){
			$this->exitWithError("参数值错误","/qApp/index");
		}
		$fAppData=new FAppData();
		$qAppInfo=$fAppData->getQ_AppByIdx($idx);
		if($qAppInfo==false || is_array($qAppInfo)==false || count($qAppInfo)==0){
			$this->exitWithError("参数值错误","/qApp/index");
		}
		$this->renderData["qApp"]=$qAppInfo;
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$BaseProrate		=	Yii::app()->request->getParam("BaseProrate");
			$IsFirstPublish		=	Yii::app()->request->getParam("IsFirstPublish");
			$AppOrder			=	Yii::app()->request->getParam("AppOrder");
			$Status				=	Yii::app()->request->getParam("Status");
			$AclList			=	Yii::app()->request->getParam("AclList");
			$IsSinglePublish	=	Yii::app()->request->getParam("IsSinglePublish");
			$IsOtherPublish		=	Yii::app()->request->getParam("IsOtherPublish");
				
			$EvnUrl1			=	Yii::app()->request->getParam("EvnUrl1");
			$EvnUrl2			=	Yii::app()->request->getParam("EvnUrl2");
			$EvnUrl3			=	Yii::app()->request->getParam("EvnUrl3");
			$EvnUrl4			=	Yii::app()->request->getParam("EvnUrl4");
			$EvnUrl5			=	Yii::app()->request->getParam("EvnUrl5");
				
			$EvnTitle1			=	Yii::app()->request->getParam("EvnTitle1");
			$EvnTitle2			=	Yii::app()->request->getParam("EvnTitle2");
			$EvnTitle3			=	Yii::app()->request->getParam("EvnTitle3");
			$EvnTitle4			=	Yii::app()->request->getParam("EvnTitle4");
			$EvnTitle5			=	Yii::app()->request->getParam("EvnTitle5");
				
			$PromoteDt			=	Yii::app()->request->getParam("PromoteDt");
			$PromoteProrate		=	Yii::app()->request->getParam("PromoteProrate");
			$PromoteStartDt		=	Yii::app()->request->getParam("PromoteStartDt");
				
			if(empty($PromoteProrate)){
				$PromoteDt="";
				$PromoteStartDt="";
			}else{
				$PromoteProrate=bcdiv($PromoteProrate, "100",2);
			}
			$BaseProrate=bcdiv($BaseProrate, "100",2);
			$fAppData=new FAppData();
			if($fAppData->modifyQ_App($Status, $AclList, $AppOrder, $IsFirstPublish, $BaseProrate, $PromoteProrate, $PromoteDt,$idx,$IsSinglePublish,$IsOtherPublish,$EvnUrl1,$EvnUrl2,$EvnUrl3,$EvnUrl4,$EvnUrl5,$EvnTitle1,$EvnTitle2,$EvnTitle3,$EvnTitle4,$EvnTitle5,$PromoteStartDt)>0){
				$this->exitWithSuccess("修改微信游戏","/qApp/index");
			}else{
				$this->alert('error',"修改微信游戏失败");
			}			
		}
		$this->render("modify",$this->renderData);
	}
	
}