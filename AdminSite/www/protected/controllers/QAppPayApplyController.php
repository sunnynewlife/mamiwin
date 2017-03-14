<?php

class QAppPayApplyController extends TableMagtController 
{
	private $_title="分红管理后台-微信红包审核";
	private $_next_url="/qAppPayApply/index";
	
	private $_tableName="Q_PlayerPayApply";
	private $_searchName = "State";
	private $_columns=array();
	private $_primaryKey="IDX";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
	}
	
	public function actionQuery()
	{
		$idx  	=  Yii::app()->request->getParam($this->_primaryKey,'');
		if(empty($idx)){
			$this->exitWithError("参数错误","/qAppPayApply/index");
		}
		$fAppData=new FAppData();
		$payApply=$fAppData->getPayApplyByIdx($idx);
		if(count($payApply)==0){
			$this->exitWithError("参数错误","/qAppPayApply/index");
		}
		if($payApply["State"]!="2"){
			$this->exitWithError("红包状态不对，不能查看领取状态。","/qAppPayApply/index");
		}
		if(empty($payApply["EnvelopeId"]) || empty($payApply["OurOrderId"])){
			$this->exitWithError("红包状态不对，不能查看领取状态。","/qAppPayApply/index");
		}
		$envelopInfo=WxHelper::queryRedEnvelope($payApply["OurOrderId"]);
		$data=array(
			"EnvelopeId"	=> 	$payApply["EnvelopeId"],
			"QueryResult"	=> "查询失败",
			"Total_amount"	=>	$payApply["Amount"],
			"Detail"		=>  array(),
		);
		
		if(is_array($envelopInfo) && isset($envelopInfo["return_code"]) && $envelopInfo["return_code"]=="SUCCESS" &&
			isset($envelopInfo["result_code"]) && $envelopInfo["result_code"]=="SUCCESS"){
			$data["QueryResult"]="查询成功";
			$data["Detail"] =array(
				"status"		=> 	$envelopInfo["status"],
				"Send_time"		=>	$envelopInfo["send_time"],
			);
		}
		$this->render("query",$data);		
	}

	public function actionIndex()
	{
		$this->_tableName="ViewQ_PlayerPayApply";
		$this->renderData['page']				= "";
		$this->renderData['page_no'] 			= "";
		$this->renderData["apply_list"]			=array();
		
		$state		=Yii::app()->request->getParam("State","0");
		$phone		=Yii::app()->request->getParam("PhoneNo","");
		$startDt	=Yii::app()->request->getParam("startDt","");
		$endDt		=Yii::app()->request->getParam("endDt","");
		$envelopeId	=Yii::app()->request->getParam("envelopeId","");
		
		
		$this->renderData["state"]=$state;
		$this->renderData["PhoneNo"]=$phone;
		$this->renderData["startDt"]=$startDt;
		$this->renderData["endDt"]=$endDt;
		$this->renderData["envelopeId"]=$envelopeId;
		
		$state = intval($state);
		if($state == 0){
			$order = " CreateDt desc ";
		}else {
			$order = " ApprovalDt desc ";
		}
		$this->_EXTRA_SEARCH_FIELDS=array(
				"PhoneNo" 	=> array("compartion_type" =>"like","field_name" =>"PhoneNo"),
				"CreateDt"	=> array("compartion_type" =>"between","field_name_start" =>"startDt","field_name_end" =>"endDt"),
				"envelopeId" 	=> array("compartion_type" =>"like","field_name" =>"EnvelopeId"),
		);
		$this->_FIELD_DEFAULT_VALUE=array(
			$this->_searchName => "0",
		);
		$this->_APPEND_SERACH_FIELD_IF_NOT_SEARCH=true;
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index" , $order );
	}
	
	public function actionModify()
	{
		$idx  	=  Yii::app()->request->getParam($this->_primaryKey,'');
		$state  =  Yii::app()->request->getParam('state','');
		if(empty($idx)){
			$this->exitWithError("参数错误","/qAppPayApply/index");
		}
		if($state!="2" &&  $state!="1"){
			$this->exitWithError("参数错误","/qAppPayApply/index");
		}
		$fAppData=new FAppData();
		$payApply=$fAppData->getPayApplyByIdx($idx);
		if(count($payApply)==0){
			$this->exitWithError("参数错误","/qAppPayApply/index");
		}
		if($payApply["State"]!="0"){
			$this->exitWithError("红包状态已处理过。","/qAppPayApply/index");
		}
		
		//审核拒绝
		if($state=="1"){
			if($fAppData->rollbackRedEnvelopeByIdx($idx)){
				$this->exitWithSuccess("审核拒绝成功",$this->_next_url);
			}else{
				$this->exitWithError("审核拒绝失败",$this->_next_url);
			}
		}else if($state==2){
			$envelopeId=$this->sendRedEnvelope($payApply["OpenId"], $payApply["Amount"]);
			if(empty($envelopeId)==false){
				if($fAppData->updateEnvelopeInfoByIdx($envelopeId,$idx,$this->_ORDER_BILLING_NO)){
					$this->exitWithSuccess("审核通过，已向用户成功发送红包",$this->_next_url);
				}else{
					$this->exitWithError("已向用户成功发送红包，数据更新状态失败，请勿再次审核通过，联系开发人员修改审核状态","/qAppPayApply/index",24*3600);
				}
			}else{
				$this->exitWithError("发送红包失败(".$this->_LAST_SEND_ENVELOP_ERR_MSG.")，请稍后重新审核。",$this->_next_url);
			}
		}
	}
	
	public function actionBatch()
	{
		$idxs  =  Yii::app()->request->getParam($this->_primaryKey,'');
		if(empty($idxs)){
			$this->exitWithError("参数错误","/qAppPayApply/index");
		}
		$fAppData=new FAppData();
		$errIdx=array();
		foreach ($idxs as $idx){
			$payApply=$fAppData->getPayApplyByIdx($idx);
			if(count($payApply)>0 && $payApply["State"]=="0"){
				$envelopeId=$this->sendRedEnvelope($payApply["OpenId"], $payApply["Amount"]);
				if(empty($envelopeId)==false){
					if($fAppData->updateEnvelopeInfoByIdx($envelopeId,$idx,$this->_ORDER_BILLING_NO)==0){
						$errIdx[]=$idx;
					}
				}
			}
		}
		if(count($errIdx)==0){
			$this->exitWithSuccess("批量审核成功",$this->_next_url);
		}else{
			$errorIdxs=implode(",",$errIdx);
			$this->exitWithError("这些记录 ：".$errorIdxs."  已向用户成功发送红包，数据更新状态失败，请勿再次审核通过，联系开发人员修改审核状态","/qAppPayApply/index",24*3600);
		}
	}
	
	public function actionRollback()
	{
		$fAppData=new FAppData();
		$this->renderData["show_result"]="";
		$this->renderData["file_count"]="";
		$this->renderData["import_count"]="";
		
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$this->renderData["show_result"]="1";
			if(isset($_FILES["EnvelopeIdFile"])){
				$files=$_FILES["EnvelopeIdFile"];
				if($files["name"]!=""){
					$fileCountents=file_get_contents($files["tmp_name"]);
					$fileCountents=str_replace(array("\n","\r","，","。",".",";","'","\"","`","~","#","%","*"), ",", $fileCountents);
					$envelopIds=explode(",",$fileCountents);
						
					$fileCounts=0;
					$insertCounts=0;
					foreach ($envelopIds as $envelopId){
						if(empty($envelopId)==false){
							$fileCounts++;
							$insertCounts+=$fAppData->rollbackRedEnvelopeByEnvelopId($envelopId);
						}
					}
					$this->renderData["file_count"]		=$fileCounts;
					$this->renderData["import_count"]	=$insertCounts;
				}
			}
		}
		$this->render("rollback",$this->renderData);
	}

	//发送红包，成功返回订单 envelopeId
	private $_ORDER_BILLING_NO="";
	private $_LAST_SEND_ENVELOP_ERR_MSG="";
	private function sendRedEnvelope($openId,$amount)
	{
		$this->_ORDER_BILLING_NO=WxHelper::generateOurOrderId();
		$envelopeWxOrderId=WxHelper::sendRedEnvelope($openId, $amount, $this->_ORDER_BILLING_NO);
		if(empty($envelopeWxOrderId)){
			$this->_LAST_SEND_ENVELOP_ERR_MSG=WxHelper::$_LAST_SEND_RED_ENVELOPE_ERROR_CODE_DES;
		}
		return $envelopeWxOrderId;
	}
}
?>