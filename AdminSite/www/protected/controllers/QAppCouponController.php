<?php

class QAppCouponController extends TableMagtController 
{
	private $_title="分红管理后台-微信礼券配置";
	private $_next_url="/qAppCoupon/index";
	
	private $_tableName="Q_AppPromotionCoupon";
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
		$this->renderData["Coupon_list"]			=array();
		
		$this->renderData["AppId"]		="";
		$this->renderData["AppName"]		="";
		$this->renderData["Type"]			="";
		$this->renderData["Status"]			="";
		$this->renderData["CouponIdx"]		="";
		$this->renderData["IsExpired"]		="";
		
		$appId	=Yii::app()->request->getParam("AppId");
		$appName	=Yii::app()->request->getParam("AppName");
		$type		=Yii::app()->request->getParam("Type");
		$status		=Yii::app()->request->getParam("Status");
		$CouponIdx	=Yii::app()->request->getParam("CouponIdx");
		$IsExpired	=Yii::app()->request->getParam("IsExpired");

		$this->renderData["AppId"]		=$appId;
		$this->renderData["AppName"]		=$appName;
		$this->renderData["Type"]			=$type;
		$this->renderData["Status"]			=$status;
		$this->renderData["CouponIdx"]		=$CouponIdx;
		$this->renderData["IsExpired"]		=$IsExpired;
		$fAppData=new FAppData();
		$row_count=$fAppData->getQ_AppPromotionCouponListCount($appId,$type,$status,$CouponIdx,$IsExpired);
		
		if($row_count>0){
			$page_no  = Yii::app()->request->getParam('page_no',1);
			$page_size=50;
			
			$page_no=$page_no<1?1:$page_no;
				
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
				
			$PageShowUrl="/qAppCoupon/index?search=1";
			if(empty($AppName)==false){
				$PageShowUrl.="&AppName=".$AppName;
			}
			if(empty($status)==false){
				$PageShowUrl.="&Status=".$status;
			}
			if(empty($CouponIdx)==false){
				$PageShowUrl.="&CouponIdx=".$CouponIdx;
			}
			if(empty($$IsExpired)==false){
				$PageShowUrl.="&$IsExpired=".$$IsExpired;
			}
			$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
			$data = $fAppData->getQ_AppPromotionCouponList($appId,$type,$status,$CouponIdx,$IsExpired,$start, $page_size);
			foreach ($data as $listkey => &$listvalue) {
				$AppNames = "";
				foreach ($listvalue as $coupon_key => $coupon_value) {
					if($coupon_key=="EndDt"){
						if(is_null($coupon_value) || empty($coupon_value) || substr($coupon_value, 0,10) == "0000-00-00" ){
							$IsExpired = "0";
						}else if(strtotime(substr($coupon_value, 0,10)) >= strtotime(date("Y-m-d"))){
							$IsExpired = "1";
						}else{
							$IsExpired = "2";
						}						
					}else if($coupon_key=="AppIds"){
						if(empty($coupon_value)==false){
							$AppNames = $this->getAppNamesByAppIds($coupon_value);
						}
					}
				}	
				$listvalue = array_merge($listvalue,array('AppNames'=>$AppNames));
				$listvalue = array_merge($listvalue,array('IsExpired'=>$IsExpired));			
			}
			$this->renderData["page"]=$page_str;
			$this->renderData["Coupon_list"]=$data;
		}
		$this->render("index",$this->renderData);		
	}
	


	public function actionQuery()
	{
		$this->renderData['page']				= "";
		$this->renderData['page_no'] 			= "";
		$this->renderData["Coupon_list"]			=array();
		
		$this->renderData["GrantStartDt"]		="";
		$this->renderData["GrantEndDt"]		="";
		$this->renderData["CouponIdx"]			="";
		$this->renderData["PhoneNo"]			="";
		
		$GrantStartDt	=Yii::app()->request->getParam("GrantStartDt");
		$GrantEndDt	=Yii::app()->request->getParam("GrantEndDt");
		if(empty($GrantEndDt) == false){
				$GrantEndDt = substr($GrantEndDt, 0,10) . " 23:59:59";
			}
		$PhoneNo		=Yii::app()->request->getParam("PhoneNo");
		$CouponIdx	=Yii::app()->request->getParam("CouponIdx");

		$this->renderData["GrantStartDt"]		=$GrantStartDt;
		$this->renderData["GrantEndDt"]		=$GrantEndDt;
		$this->renderData["PhoneNo"]			=$PhoneNo;
		$this->renderData["CouponIdx"]		=$CouponIdx;
			
		$fAppData=new FAppData();
		$row_count=$fAppData->getQ_AppCouponGrantListCount($GrantStartDt,$GrantEndDt,$PhoneNo,$CouponIdx);
		if($row_count>0){
			$page_no  = Yii::app()->request->getParam('page_no',1);
			$page_size=50;
			
			$page_no=$page_no<1?1:$page_no;
				
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
				
			$PageShowUrl="/qAppCoupon/query?search=1";
			if(empty($GrantStartDt)==false){
				$PageShowUrl.="&GrantStartDt=".$GrantStartDt;
			}
			if(empty($GrantEndDt)==false){
				$PageShowUrl.="&GrantEndDt=".$GrantEndDt;
			}
			if(empty($CouponIdx)==false){
				$PageShowUrl.="&CouponIdx=".$CouponIdx;
			}
			if(empty($$PhoneNo)==false){
				$PageShowUrl.="&$PhoneNo=".$PhoneNo;
			}
			$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
			$data = $fAppData->getQ_AppCouponGrantList($GrantStartDt,$GrantEndDt,$PhoneNo,$CouponIdx,$start, $page_size);
			foreach ($data as $listkey => &$listvalue) {
				foreach ($listvalue as $coupon_key => $coupon_value) {
					if($coupon_key=="AppName"){
						if(empty($coupon_value)==false){
							$isUsed = "是";
						}else{
							$isUsed = "否";
						}						
					}
				}	
				$listvalue = array_merge($listvalue,array('isUsed'=>$isUsed));		
			}
			$this->renderData["page"]=$page_str;
			$this->renderData["Coupon_list"]=$data;
		}
		$this->render("query",$this->renderData);		
	}

	public function actionAdd()
	{
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$appId		=	Yii::app()->request->getParam("AppName");
			$CouponStatus	=	Yii::app()->request->getParam("Status");
			$aclList	=	Yii::app()->request->getParam("AclList","");
			$CouponName	=	Yii::app()->request->getParam("CouponName");
			$Type	=	Yii::app()->request->getParam("Type");
			$RecharegAmount	=	Yii::app()->request->getParam("RecharegAmount","0");
			$ReturnAmount	=	Yii::app()->request->getParam("ReturnAmount","0");
			$PayStartDt	=	Yii::app()->request->getParam("PayStartDt");
			$PayEndDt	=	Yii::app()->request->getParam("PayEndDt");
			$Quantity=	Yii::app()->request->getParam("Quantity");
			$startDt	=	Yii::app()->request->getParam("startDt");
			$endDt	=	Yii::app()->request->getParam("endDt");
			$IsNewBind	=	Yii::app()->request->getParam("IsNewBind");
			$BindStartDt	=	Yii::app()->request->getParam("BindStartDt");
			$BindEndDt	=	Yii::app()->request->getParam("BindEndDt");
			if(empty($PayEndDt) == false){
				$PayEndDt = substr($PayEndDt, 0,10) . " 23:59:59";
			}
			if(empty($endDt) == false){
				$endDt = substr($endDt, 0,10) . " 23:59:59";
			}
			if(empty($BindEndDt) == false){
				$BindEndDt = substr($BindEndDt, 0,10) . " 23:59:59";
			}
			$msgFirst=	Yii::app()->request->getParam("MsgFirst");
			$msgRemark=	Yii::app()->request->getParam("MsgRemark");

			$fAppData=new FAppData();
			if($fAppData->insertQ_AppPromotionCoupon($appId,$CouponStatus,$aclList,$Type,$RecharegAmount,$ReturnAmount,$PayStartDt,$PayEndDt,$Quantity,
					$startDt,$endDt,$IsNewBind,$BindStartDt,$BindEndDt,$msgFirst,$msgRemark,$CouponName)){
				$this->exitWithSuccess("增加微信礼券配置","/qAppCoupon/index");
			}else{
				$this->alert('error',"增加微信礼券配置失败");
			}
		}
		$this->render("add",$this->renderData);
	}
	
	public function actionModify()
	{
		$idx=Yii::app()->request->getParam('idx',"");
		$submit = trim(Yii::app()->request->getParam('submit',0));
		$fAppData=new FAppData();
		$CouponItem=$fAppData->getQ_AppPromotionCouponByIdx($idx);
		if($CouponItem==false || is_array($CouponItem)==false || count($CouponItem)==0){
			$this->exitWithError("参数值错误","/qAppCoupon/index");
		}
		$this->renderData["QCouponItem"]=$CouponItem;
		if($submit){
		
			$appId		=	Yii::app()->request->getParam("AppName");
			$CouponStatus	=	Yii::app()->request->getParam("Status");
			$aclList	=	Yii::app()->request->getParam("AclList","");
			$CouponName	=	Yii::app()->request->getParam("CouponName");
			$Type	=	Yii::app()->request->getParam("Type");
			$RecharegAmount	=	Yii::app()->request->getParam("RecharegAmount","0");
			$ReturnAmount	=	Yii::app()->request->getParam("ReturnAmount","0");
			$PayStartDt	=	Yii::app()->request->getParam("PayStartDt");
			$PayEndDt	=	Yii::app()->request->getParam("PayEndDt");
			$Quantity=	Yii::app()->request->getParam("Quantity");
			$startDt	=	Yii::app()->request->getParam("startDt");
			$endDt	=	Yii::app()->request->getParam("endDt");
			$IsNewBind	=	Yii::app()->request->getParam("IsNewBind");
			$BindStartDt	=	Yii::app()->request->getParam("BindStartDt");
			$BindEndDt	=	Yii::app()->request->getParam("BindEndDt");
			if(empty($PayEndDt) == false){
				$PayEndDt = substr($PayEndDt, 0,10) . " 23:59:59";
			}
			if(empty($endDt) == false){
				$endDt = substr($endDt, 0,10) . " 23:59:59";
			}
			if(empty($BindEndDt) == false){
				$BindEndDt = substr($BindEndDt, 0,10) . " 23:59:59";
			}
			
			$msgFirst=	Yii::app()->request->getParam("MsgFirst");
			$msgRemark=	Yii::app()->request->getParam("MsgRemark");
				
			$fAppData=new FAppData();
			if($fAppData->updateQ_AppPromotionCoupon($appId,$CouponStatus,$aclList,$Type,$RecharegAmount,$ReturnAmount,$PayStartDt,$PayEndDt,$Quantity,
					$startDt,$endDt,$IsNewBind,$BindStartDt,$BindEndDt,$idx,$msgFirst,$msgRemark,$CouponName)){
				$this->exitWithSuccess("修改微信礼券配置","/qAppCoupon/index");
			}else{
				$this->alert('error',"修改微信礼券配置失败");
			}			
		}
		$this->render("modify",$this->renderData);
	}
	
	public function actionUpload()
	{
		$idx=Yii::app()->request->getParam('idx',"");
		$isNewBindPlayer=Yii::app()->request->getParam('isNewBindPlayer',"1");
		$BindStartDt=Yii::app()->request->getParam('BindStartDt',"");
		$BindEndDt=Yii::app()->request->getParam('BindEndDt',"");

		$fAppData=new FAppData();
		$CouponItem=$fAppData->getQ_AppPromotionCouponByIdx($idx);
		if($CouponItem==false || is_array($CouponItem)==false || count($CouponItem)==0){
			$this->exitWithError("参数值错误","/qAppCoupon/index");
		}
		$CouponQuantity = $CouponItem['Quantity'];
		$CouponGrantCount = $fAppData->getCouponGrantCount($idx);
		$canGrantCount = $CouponQuantity - $CouponGrantCount;

		if(!is_numeric($CouponQuantity) || !is_numeric($CouponGrantCount) || !is_numeric($canGrantCount)){
			$this->exitWithError("可发送礼券数有误","/qAppCoupon/index");
		}
		if(isset($CouponItem['AppIds']) && empty($CouponItem['AppIds'])==false){
			$AppNames = $this->getAppNamesByAppIds($CouponItem['AppIds']);
		}else{
			$AppNames="所有游戏";
		} 
		$CouponItem = array_merge($CouponItem,array("AppNames"=>$AppNames));
		
		$couponStatus = $CouponItem['Status'];
		$this->renderData["QCouponItem"]=$CouponItem;
		$this->renderData["idx"]=$idx;
		$this->renderData["show_result"]="";
		$this->renderData["file_count"]="";
		$this->renderData["import_count"]="";
		$this->renderData["all_count"]="";
		$this->renderData["rest_count"]="";
		$this->renderData["BindStartDt"] = $BindStartDt;
		$this->renderData["BindEndDt"] = $BindEndDt;		
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$insertCounts=0;
			if($isNewBindPlayer == "2"){
				//导入目标用户列表发送
				$this->renderData["show_result"]="1";
				if(isset($_FILES["CouponPhoneNo"])){
					$files=$_FILES["CouponPhoneNo"];
					if($files["name"]!=""){
						$fileCountents=file_get_contents($files["tmp_name"]);
						$fileCountents=str_replace(array("\n","\r","，","。",".",";","'","\"","`","~","#","%","*",",,"), ",", $fileCountents);
						$CouponPhoneNos=explode(",",$fileCountents);
						// var_dump($CouponPhoneNos);
						$fileCounts=count($CouponPhoneNos);
						$insertCounts=0;
						$microPlayerList = $fAppData->getMicroPlayerByPhoneNos($fileCountents);
						$microPlayerLists = array();
						if(is_array($microPlayerList)){
							foreach ($microPlayerList as $key => &$value) {
									foreach ($value as $m_key => $m_value) {
										$microPlayerLists[] = $m_value;
									}
								}	
						}
						$CouponPhoneNos = $microPlayerLists;
						if($couponStatus == 2 ){
							$couponAclList = $fAppData->getCouponAclListByPhoneNos($idx);
							if(is_array($couponAclList) and count($couponAclList) > 0){
								$couponAclLists = explode(",", $couponAclList[0]['AclList']);	
							}
							if(is_array($microPlayerLists) && is_array($couponAclLists)){
								$CouponPhoneNos = array_intersect($microPlayerLists,$couponAclLists);
							}
						}
						if(is_array($CouponPhoneNos)){
							$msgCouponDef=$fAppData->getSystemConfigItem("tencent.msg.coupon","",true);
							$wxCouponMsgHelper=new WxCouponMsgHelper();
							foreach ($CouponPhoneNos as $itemPhone)
							{
								if($canGrantCount>0){
									$itemInsertCount=$fAppData->insertQ_AppCouponPhoneNo($idx,$itemPhone);
									if($itemInsertCount>0){
										$insertCounts++;
										$canGrantCount--;
										$openId=$fAppData->getOpenIdByPhone($itemPhone);
										$wxCouponMsgHelper->sendNotifyMsg4Coupon($openId, $msgCouponDef,$idx);
									}
								}
							}
						}
						$this->renderData["BindStartDt"] = "";
						$this->renderData["BindEndDt"] = "";
						$this->renderData["file_count"]		=$fileCounts;
						$this->renderData["insertCounts"] = $insertCounts;
					}
				}
			}else if($isNewBindPlayer == "1" && empty($BindStartDt) == false && empty($BindEndDt) == false){
				$toPlayInfos=$fAppData->selectQ_AppCouponPhoneNoBatch($idx,$BindStartDt,$BindEndDt,$canGrantCount);
				$msgCouponDef=$fAppData->getSystemConfigItem("tencent.msg.coupon","",true);
				$wxCouponMsgHelper=new WxCouponMsgHelper();
				$couponAclLists=array();
				if($couponStatus == 2 ){
					$couponAclList = $fAppData->getCouponAclListByPhoneNos($idx);
					if(is_array($couponAclList) and count($couponAclList) > 0){
						$couponAclLists = explode(",", $couponAclList[0]['AclList']);
					}
				}
								
				foreach ($toPlayInfos as $playItem){
					if($canGrantCount>0){
						if($couponStatus == 2 ){
							if(in_array($playItem["PhoneNo"], $couponAclLists)==false){
								continue;
							}
						}
						$itemInsertCount=$fAppData->insertQ_AppCouponPhoneNo($idx,$playItem["PhoneNo"]);
						if($itemInsertCount>0){
							$insertCounts++;
							$canGrantCount--;
							$wxCouponMsgHelper->sendNotifyMsg4Coupon($playItem["OpenId"], $msgCouponDef,$idx);
						}
					}
				}
				
				$this->renderData["insertCounts"] = $insertCounts;
				$this->renderData["show_result"]="2";
			}
		}
		$this->renderData["isNewBindPlayer"] = $isNewBindPlayer;
		$this->render("upload",$this->renderData);
	}
	
	public function actionDownload()
	{
		$idx=Yii::app()->request->getParam('idx',"");
		$fAppData=new FAppData();
		$CouponItem=$fAppData->getQ_AppCouponDrawByIdx($idx);
		if($CouponItem==false || is_array($CouponItem)==false || count($CouponItem)==0){
			$this->exitWithError("参数值错误","/qAppCoupon/index");
		}
		//BOM Magic Bytes
		$txtStr="\xEF\xBB\xBF礼券ID,已发送用户手机号,是否已使用,使用游戏,发送日期,使用日期". PHP_EOL;
		foreach ($CouponItem as $rowItem){
			$txtStr.=sprintf("%s,%s,%s,%s,%s,%s%s",
					$rowItem["IDX"],
					empty($rowItem["PhoneNo"])?"":$rowItem["PhoneNo"],
					empty($rowItem["PayIdx"])?"否":"是",
					empty($rowItem["AppName"])?"":$rowItem["AppName"],
					$rowItem["CreateDt"],
					$rowItem["UpdateDt"],
					PHP_EOL);
		}
		$currentTime=time();
		$fileName=sprintf("%s_%s_%s.csv",date("Y-m-d",$currentTime),$idx,"礼券表");
		header("Content-Type:text/html;charset=utf-8");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".strlen($txtStr));
		Header("Content-Disposition: attachment; filename=" . $fileName);
		echo $txtStr;
	}
	

	public function actionDownloadCoupon()
	{
		$GrantStartDt	=Yii::app()->request->getParam("GrantStartDt");
		$GrantEndDt	=Yii::app()->request->getParam("GrantEndDt");
		if(empty($GrantEndDt) == false){
				$GrantEndDt = substr($GrantEndDt, 0,10) . " 23:59:59";
		}

		$PhoneNo		=Yii::app()->request->getParam("PhoneNo");
		$CouponIdx	=Yii::app()->request->getParam("CouponIdx");
		$start = 0 ;
		$page_size = 999999 ;
		$fAppData=new FAppData();
		$CouponItem = $fAppData->getQ_AppCouponGrantList($GrantStartDt,$GrantEndDt,$PhoneNo,$CouponIdx,$start, $page_size);
		foreach ($CouponItem as $listkey => &$listvalue) {
			foreach ($listvalue as $coupon_key => $coupon_value) {
				if($coupon_key=="AppName"){
					if(empty($coupon_value)==false){
						$isUsed = "是";
					}else{
						$isUsed = "否";
					}						
				}
			}	
			$listvalue = array_merge($listvalue,array('isUsed'=>$isUsed));		
		}
		if($CouponItem==false || is_array($CouponItem)==false || count($CouponItem)==0){
			$this->exitWithError("参数值错误","/qAppCoupon/query");
		}
		//BOM Magic Bytes
		$txtStr="\xEF\xBB\xBF用户手机,礼券ID,礼券发送时间,是否使用,使用游戏,礼券使用时间,充值金额,充值时间,充值订单号". PHP_EOL;
		foreach ($CouponItem as $rowItem){
			$txtStr.=sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s%s",
					$rowItem["PhoneNo"],
					$rowItem["CouponIdx"],
					$rowItem["CreateDt"],
					$rowItem["isUsed"],
					$rowItem["AppName"],
					empty($rowItem["AppName"])?"":$rowItem["UpdateDt"],
					empty($rowItem["GameAmount"])?"":$rowItem["GameAmount"],
					empty($rowItem["TransactDt"])?"":$rowItem["TransactDt"],
					empty($rowItem["CheckingID"])?"":$rowItem["CheckingID"],
					PHP_EOL);
		}
		$currentTime=time();
		$fileName=sprintf("%s_%s_%s.csv",date("Y-m-d",$currentTime),$CouponIdx,"礼券使用表");
		header("Content-Type:text/html;charset=utf-8");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".strlen($txtStr));
		Header("Content-Disposition: attachment; filename=" . $fileName);
		echo $txtStr;
	}


	protected function getAppNamesByAppIds($appIds){
		$AppNames = "";
		$fAppData=new FAppData();
		$appNameList = $fAppData->getAppByAppids($appIds);
		foreach ($appNameList as $app_key => $app_value) {
			foreach ($app_value as $appInfo_key => $appInfo_value) {
				if($appInfo_key == "AppName"){
					$AppNames .= $appInfo_value.",";
				}
			}
		}
		if(substr($AppNames, -1) == ","){
			$AppNames = substr($AppNames , 0 , (strlen($AppNames) -1 ));
		}		
		if(empty($AppNames)){
			return $appIds;
		}
		return $AppNames;
	}
}
?>