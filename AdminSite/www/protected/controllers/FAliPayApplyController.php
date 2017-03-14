<?php
require_once(dirname(__FILE__) . '/../config/ConfAlipay.php');
require_once(dirname(__FILE__) . '/../modules/ModuleTrans.php');

class FAliPayApplyController extends TableMagtController {
	private $_tableName = "ViewPromoterAlipayApply";
	private $_searchName = "State";
	private $_next_url = "/fAliPayApply/index";
	private $_columns = array('ApplyId', 'PromoterId', 'Amount', 'CreateDt', 'State', 'ApprovalDt', 'SubmitDt', 'ReplyDt', 'ResultMemo');
	private $_title = "分红管理后台-支付宝提现审核";
	private $_primaryKey = "ApplyId";
	
	
	public function init() {
		$this->_PDO_NODE_NAME = "FHDatabase";
		$this->_MEMCACHE_NODE_NAME = "";
		$this->_memcacheKey = array("");
		
	}

	public function actionList() {
		header("location:/fAliPayApply/index?State=0");
	}
	
	public function actionIndex() {

		$state =  trim(Yii::app()->request->getParam('State',""));
		if (true === empty($state) && 0 != $state){
			$state = 0;
		}

		$state = intval($state);

		$this->renderData['state'] = $state;

		if($state == 0){
			$order = " CreateDt desc ";
		}else if($state == 1 || $state == 2 || $state == 3){
			$order = " ApprovalDt desc ";
		}else if($state == 4 || $state == 5){
			$order = " ReplyDt desc , ApplyId desc  ";
		}
		$this->_EXTRA_SEARCH_FIELDS=array(
				"PhoneNo" 	=> array("compartion_type" =>"like","field_name" =>"PhoneNo"),
				"CreateDt"	=> array("compartion_type" =>"between","field_name_start" =>"startDt","field_name_end" =>"endDt"),
		);
		$this->_APPEND_SERACH_FIELD_IF_NOT_SEARCH=true;
		$this->renderData["PhoneNo"]=trim(Yii::app()->request->getParam('PhoneNo',""));
		$this->renderData["startDt"]=trim(Yii::app()->request->getParam('startDt',""));
		$this->renderData["endDt"]=trim(Yii::app()->request->getParam('endDt',""));
		
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index" , $order );
	}
	
	public function actionBatch() {
		$this->_next_url=$this->_next_url."?State=0";
		$applyId  =  Yii::app()->request->getParam($this->_primaryKey,'');
		$state  =  Yii::app()->request->getParam('State','');
		if(empty($applyId)){
			$this->exitWithError("参数错误","/fAliPayApply/index?State=0");
		}

		$fAppData = new FAppData;
		$rowCount = $fAppData->updatePromoterAlipayApply($applyId, $state, '审核支付宝提现');
		if($rowCount>0){
			$this->exitWithSuccess(sprintf("修改%s成功",$this->_title),$this->_next_url);
		}else{
			$this->alert('error',sprintf("修改%s失败",$this->_title));
		}
	}
	public function actionModify() {
		$this->_next_url=$this->_next_url."?State=0";
		$applyId  =  Yii::app()->request->getParam($this->_primaryKey,'');
		$state  =  Yii::app()->request->getParam('state','');
		if(empty($applyId)){
			$this->exitWithError("参数错误","/fAliPayApply/index?State=0");
		}

		$fAppData = new FAppData;
		$rowCount = $fAppData->updatePromoterAlipayApply(array($applyId), $state, '审核支付宝提现');
		if($rowCount>0){
			if($state == ConfAlipay::ALIPAYAPPLYREFUSE){
				// $ret = ModuleTrans::returnPromoterAmount($applyId);
				$memo = "reject";
				$ret = ModuleTrans::promoterAlipayFailNotify($applyId,$state,$memo);
				if($ret){
	              $this->exitWithSuccess(sprintf("修改%s成功",$this->_title),$this->_next_url); 
	            } 
				// $applyInfo = $fAppData->getProAlipayApplyInfoByApplyid($applyId);
				// if($applyInfo){
				// 	$promoterId = $applyInfo[0]['PromoterId'];
				// 	$transAmount = $applyInfo[0]['Amount'];
				// 	$transFee = $applyInfo[0]['Fee'];
				// 	$transAmounts = bcadd($transAmount, $transFee,2);
				// 	$promoterInfo = $fAppData->getPromoterByPromoterId($promoterId);

				// 	if($promoterInfo){
				// 		$amount = $promoterInfo['Amount'];
				// 		$netAmount = $promoterInfo['NetAmount'];
				// 		$amount = bcadd($amount, $transAmounts,2);
				// 		$netAmount = bcadd($netAmount, $transAmounts,2);
				// 		$returnRowCount = $fAppData->returnPromoterAmount($promoterId , $amount , $netAmount);	
				// 		if($returnRowCount > 0 ){
				// 			$this->exitWithSuccess(sprintf("修改%s成功",$this->_title),$this->_next_url);	
				// 		}						
				// 	}
				// }
			}
			$this->exitWithSuccess(sprintf("修改%s成功",$this->_title),$this->_next_url); 
		}else{
			$this->alert('error',sprintf("修改%s失败",$this->_title));
		}
	}
}