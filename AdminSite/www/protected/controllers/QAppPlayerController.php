<?php

class QAppPlayerController extends TableMagtController 
{
	private $_title="分红管理后台-微信关注用户信息查询";
	private $_next_url="/qAppPlayer/index";
	
	private $_tableName="Q_MicroPlayer";
	private $_searchName="PhoneNo";
	private $_columns=array();
	private $_primaryKey="Idx";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
	}

	public function actionIndex()
	{
		$this->_SERACH_FIELD_COMPARE_TYPE=array("PhoneNo" => "like");
		$this->_EXTRA_SEARCH_FIELDS=array(
				"CreateDt"	=> array("compartion_type" =>"between","field_name_start" =>"sCreateDt","field_name_end" =>"eCreateDt"),
				"BindDt"	=> array("compartion_type" =>"between","field_name_start" =>"sBindDt","field_name_end" =>"eBindDt"),
				"OpenId" 	=> array("compartion_type" =>"like","field_name" =>"OpenId"),
		);
		$export= trim(Yii::app()->request->getParam('export',""));
		if($export){
			$PhoneNo=trim(Yii::app()->request->getParam('PhoneNo',""));
			$OpenId=trim(Yii::app()->request->getParam('OpenId',""));
			$sCreateDt=trim(Yii::app()->request->getParam('sCreateDt',""));
			$eCreateDt=trim(Yii::app()->request->getParam('eCreateDt',""));
			$sBindDt=trim(Yii::app()->request->getParam('sBindDt',""));
			$eBindDt=trim(Yii::app()->request->getParam('eBindDt',""));
			
			return $this->export($PhoneNo,$OpenId,$sCreateDt,$eCreateDt,$sBindDt,$eBindDt);
		}
		$this->renderData["PhoneNo"]=trim(Yii::app()->request->getParam('PhoneNo',""));
		$this->renderData["OpenId"]=trim(Yii::app()->request->getParam('OpenId',""));
		$this->renderData["sCreateDt"]=trim(Yii::app()->request->getParam('sCreateDt',""));
		$this->renderData["eCreateDt"]=trim(Yii::app()->request->getParam('eCreateDt',""));
		$this->renderData["sBindDt"]=trim(Yii::app()->request->getParam('sBindDt',""));
		$this->renderData["eBindDt"]=trim(Yii::app()->request->getParam('eBindDt',""));
		
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index","CreateDt desc");
	}
	private function export($PhoneNo,$OpenId,$sCreateDt,$eCreateDt,$sBindDt,$eBindDt)
	{
		$fAppData=new FAppData();
		$playList=$fAppData->getQ_MicroPlayer($PhoneNo,$OpenId,$sCreateDt,$eCreateDt,$sBindDt,$eBindDt);
		
		//BOM Magic Bytes
		$txtStr="\xEF\xBB\xBFOpenId,手机号码,累计礼金 ,礼金余额,是否关注,关注时间,绑定时间". PHP_EOL;
		foreach ($playList as $rowItem){
			$txtStr.=sprintf("%s,%s,%s,%s,%s,%s,%s%s",
					$rowItem["OpenId"],$rowItem["PhoneNo"],
					number_format($rowItem["IncomeSummary"],2,".",","),
					number_format($rowItem["NetAmount"],2,".",","),
					$rowItem["FocusStatus"]=="1"?"是":"否",
					$rowItem["FocusDt"],$rowItem["BindDt"],
					PHP_EOL);
		}
		$currentTime=time();
		$fileName=sprintf("%s_%s.csv",date("Y-m-d",$currentTime),"微信用户");
		header("Content-Type:text/html;charset=utf-8");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".strlen($txtStr));
		Header("Content-Disposition: attachment; filename=" . $fileName);
		echo $txtStr;		
	}
	public function actionHot()
	{
		$evnList=array();
		$phone=Yii::app()->request->getParam("PhoneNo",'');
		if(empty($phone)==false){
			$fAppData=new FAppData();
			$evnList=$fAppData->getEnvListByPhoneNo($phone);
		}
		$this->renderData["PhoneNo"]=$phone;
		$this->renderData["evn_list"]=$evnList;
		$this->render("hot",$this->renderData);
	}
	
	public function actionGift()
	{
		$evnList=array();
		$phone=Yii::app()->request->getParam("PhoneNo",'');
		if(empty($phone)==false){
			$fAppData=new FAppData();
			$evnList=$fAppData->getGiftByPhoneNo($phone);
		}
		$this->renderData["PhoneNo"]=$phone;
		$this->renderData["gift_list"]=$evnList;
		$this->render("gift",$this->renderData);		
	}
	
	public function actionPayState()
	{
		$idx=Yii::app()->request->getParam("idx",'');
		$state=Yii::app()->request->getParam("state",'0');
		if(empty($idx)==false){
			$fAppData=new FAppData();
			if($fAppData->updateMicroPayState($state, $idx)){
				$this->exitWithSuccess("修改微信账户状态成功","/qAppPlayer/index");
			}
		}
		$this->exitWithError("参数值错误","/qAppPlayer/index");
	}
}
?>