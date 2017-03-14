<?php

class QAppGiftController extends TableMagtController 
{
	private $_title="分红管理后台-微信礼包配置";
	private $_next_url="/qAppGift/index";
	
	private $_tableName="Q_AppPromotionGift";
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
		$this->renderData['page']				= "";
		$this->renderData['page_no'] 			= "";
		$this->renderData["gift_list"]			=array();
		
		$this->renderData["AppName"]		="";
		$this->renderData["Status"]			="";
		$this->renderData["GiftIdx"]		="";
		$this->renderData["GiftName"]		="";
		
		$appName	=Yii::app()->request->getParam("AppName");
		$status		=Yii::app()->request->getParam("Status");
		$giftIdx	=Yii::app()->request->getParam("GiftIdx");
		$giftName	=Yii::app()->request->getParam("GiftName");

		$this->renderData["AppName"]		=$appName;
		$this->renderData["Status"]			=$status;
		$this->renderData["GiftIdx"]		=$giftIdx;
		$this->renderData["GiftName"]		=$giftName;
			
		$fAppData=new FAppData();
		$row_count=$fAppData->getQ_AppPromotionGiftListCount($appName,$status,$giftIdx,$giftName);
		
		if($row_count>0){
			$page_no  = Yii::app()->request->getParam('page_no',1);
			$page_size=50;
			
			$page_no=$page_no<1?1:$page_no;
				
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
				
			$PageShowUrl="/qAppGift/index?search=1";
			if(empty($AppName)==false){
				$PageShowUrl.="&AppName=".$AppName;
			}
			if(empty($status)==false){
				$PageShowUrl.="&Status=".$status;
			}
			if(empty($giftIdx)==false){
				$PageShowUrl.="&GiftIdx=".$giftIdx;
			}
			if(empty($giftName)==false){
				$PageShowUrl.="&GiftName=".$giftName;
			}
			$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
			$this->renderData["page"]=$page_str;
			$this->renderData["gift_list"]=$fAppData->getQ_AppPromotionGiftList($appName,$status,$giftIdx,$giftName, $start, $page_size);
		}
		$this->render("index",$this->renderData);		
	}
	
	public function actionAdd()
	{
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$appId		=	Yii::app()->request->getParam("AppId");
			$picId		=	Yii::app()->request->getParam("FileId");
			$giftStatus	=	Yii::app()->request->getParam("Status");
			$openDt		=	Yii::app()->request->getParam("OpenDt");
			$aclList	=	Yii::app()->request->getParam("AclList","");
			$Category	=	Yii::app()->request->getParam("Category","1");
			$giftOrder	=	Yii::app()->request->getParam("GiftOrder");
			$TagType1	=	Yii::app()->request->getParam("TagType1","0");
			$TagType2	=	Yii::app()->request->getParam("TagType2","0");
			$TagType3	=	Yii::app()->request->getParam("TagType3","0");
			$giftName	=	Yii::app()->request->getParam("Name");
			$TotalCount	=	Yii::app()->request->getParam("TotalCount","null");
			$RestCount	=	Yii::app()->request->getParam("RestCount","null");
			$giftStart	=	Yii::app()->request->getParam("startDt");
			$giftEnd	=	Yii::app()->request->getParam("endDt");
			$giftContent=	Yii::app()->request->getParam("Content");
			$giftGuide	=	Yii::app()->request->getParam("Guide");
			
			if(empty($TotalCount)){
				$TotalCount="null";
			}
			if(empty($RestCount)){
				$RestCount="null";
			}
				
			$fAppData=new FAppData();
			if($fAppData->insertQ_AppPromotionGift($appId,$picId,$giftStatus,$openDt,$aclList,$Category,$giftOrder,$TagType1,$TagType2,$TagType3,$giftName,
					$TotalCount,$RestCount,$giftStart,$giftEnd,$giftContent,$giftGuide)){
				$this->exitWithSuccess("增加微信礼包配置","/qAppGift/index");
			}else{
				$this->alert('error',"增加微信礼包配置失败");
			}
		}
		$this->render("add",$this->renderData);
	}
	
	public function actionModify()
	{
		$idx=Yii::app()->request->getParam('idx',"");
		$submit = trim(Yii::app()->request->getParam('submit',0));
		$fAppData=new FAppData();
		$giftItem=$fAppData->getQ_AppPromotionGiftByIdx($idx);
		if($giftItem==false || is_array($giftItem)==false || count($giftItem)==0){
			$this->exitWithError("参数值错误","/qAppGift/index");
		}
		$this->renderData["QGiftItem"]=$giftItem;
		if($submit){
		
			$appId		=	Yii::app()->request->getParam("AppId");
			$picId		=	Yii::app()->request->getParam("FileId");
			$giftStatus	=	Yii::app()->request->getParam("Status");
			$openDt		=	Yii::app()->request->getParam("OpenDt");
			$aclList	=	Yii::app()->request->getParam("AclList","");
			$Category	=	Yii::app()->request->getParam("Category","1");
			$giftOrder	=	Yii::app()->request->getParam("GiftOrder");
			$TagType1	=	Yii::app()->request->getParam("TagType1","0");
			$TagType2	=	Yii::app()->request->getParam("TagType2","0");
			$TagType3	=	Yii::app()->request->getParam("TagType3","0");
			$giftName	=	Yii::app()->request->getParam("Name");
			$TotalCount	=	Yii::app()->request->getParam("TotalCount","null");
			$RestCount	=	Yii::app()->request->getParam("RestCount","null");
			$giftStart	=	Yii::app()->request->getParam("startDt");
			$giftEnd	=	Yii::app()->request->getParam("endDt");
			$giftContent=	Yii::app()->request->getParam("Content");
			$giftGuide	=	Yii::app()->request->getParam("Guide");
			
			if(empty($TotalCount)){
				$TotalCount="null";
			}
			if(empty($RestCount)){
				$RestCount="null";
			}
				
			$fAppData=new FAppData();
			if($fAppData->updateQ_AppPromotionGift($appId,$picId,$giftStatus,$openDt,$aclList,$Category,$giftOrder,$TagType1,$TagType2,$TagType3,$giftName,
					$TotalCount,$RestCount,$giftStart,$giftEnd,$giftContent,$giftGuide,$idx)){
				$this->exitWithSuccess("修改微信礼包配置","/qAppGift/index");
			}else{
				$this->alert('error',"修改微信礼包配置失败");
			}			
		}
		$this->render("modify",$this->renderData);
	}
	
	public function actionUpload()
	{
		$idx=Yii::app()->request->getParam('idx',"");
		$fAppData=new FAppData();
		$giftItem=$fAppData->getQ_AppPromotionGiftByIdx($idx);
		if($giftItem==false || is_array($giftItem)==false || count($giftItem)==0){
			$this->exitWithError("参数值错误","/qAppGift/index");
		}
		$this->renderData["QGiftItem"]=$giftItem;
		$this->renderData["show_result"]="";
		$this->renderData["file_count"]="";
		$this->renderData["import_count"]="";
		$this->renderData["all_count"]="";
		$this->renderData["rest_count"]="";
		
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$this->renderData["show_result"]="1";
			if(isset($_FILES["GiftCodeFile"])){
				$files=$_FILES["GiftCodeFile"];
				if($files["name"]!=""){
					$fileCountents=file_get_contents($files["tmp_name"]);
					$fileCountents=str_replace(array("\n","\r","，","。",".",";","'","\"","`","~","#","%","*"), ",", $fileCountents);
					$giftCodes=explode(",",$fileCountents);
					
					$fileCounts=0;
					$insertCounts=0;
					foreach ($giftCodes as $giftCode){
						if(empty($giftCode)==false){
							$fileCounts++;
							$insertCounts+=$fAppData->insertQ_AppGiftCode($idx,$giftCode);
						}	
					}
					$this->renderData["file_count"]		=$fileCounts;
					$this->renderData["import_count"]	=$insertCounts;
					$this->renderData["all_count"]		=$fAppData->getGiftTotalCountByGiftIdx($idx);
					$this->renderData["rest_count"]		=$fAppData->getGiftRestCountByGiftIdx($idx);
				}
			}
		}
		$this->render("upload",$this->renderData);
	}
	
	public function actionDownload()
	{
		$idx=Yii::app()->request->getParam('idx',"");
		$fAppData=new FAppData();
		$giftItem=$fAppData->getQ_AppPromotionGiftByIdx($idx);
		if($giftItem==false || is_array($giftItem)==false || count($giftItem)==0){
			$this->exitWithError("参数值错误","/qAppGift/index");
		}
		$giftCodeList=$fAppData->getGiftCodeListByGiftIdx($idx);
		//BOM Magic Bytes
		$txtStr="\xEF\xBB\xBF礼包码,领取情况,导入时间". PHP_EOL;
		foreach ($giftCodeList as $rowItem){
			$txtStr.=sprintf("%s,%s,%s%s",
					$rowItem["Code"],
					empty($rowItem["PhoneNo"])?"未领取":"已领取",
					$rowItem["CreateDt"],
					PHP_EOL);
		}
		$currentTime=time();
		$fileName=sprintf("%s_%s_%s.csv",date("Y-m-d",$currentTime),$idx,"礼包表");
		header("Content-Type:text/html;charset=utf-8");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".strlen($txtStr));
		Header("Content-Disposition: attachment; filename=" . $fileName);
		echo $txtStr;
	}
	
}
?>