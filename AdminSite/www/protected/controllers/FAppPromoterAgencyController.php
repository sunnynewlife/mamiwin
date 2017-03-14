<?php

class FAppPromoterAgencyController extends TableMagtController 
{
	private $_tableName="PromoterAgency";
	private $_searchName="Name";
	private $_next_url="/fAppPromoterAgency/index";
	private $_columns=array("Name","LoginName","Telphone","Code","BeginDt","EndDt");
	private $_title="分红管理后台-大商户用户管理";
	private $_primaryKey="AgencyId";
	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
	}
	
	private function export($fAppData,$agencyName,$agencyCode,$telphone,$channelType,$isFrozen,$orderField)
	{
		$PayList=$fAppData->getAgencyPromoter($agencyName,$agencyCode,$telphone,$channelType,$isFrozen,$orderField,-1,999);
		//BOM Magic Bytes
		$txtStr="\xEF\xBB\xBF商户名称,商户编号,渠道类型,渠道数量,累计充值,累计返利,联系电话". PHP_EOL;
		foreach ($PayList as $rowItem){
			$GameAmount= isset($rowItem["GameAmount"])?$rowItem["GameAmount"]:0;
			$Amount= isset($rowItem["Amount"])?$rowItem["Amount"]:0;
			$txtStr.=sprintf("%s,%s,%s,%s,%s,%s,%s%s",
					$rowItem["Name"],
					$rowItem["Code"],
					($rowItem["ChannelType"]==1?"批次号":($rowItem["ChannelType"]==2?"手机号":"")),
					$rowItem["MaxPromoterNum"],
					$GameAmount,
					$Amount,
					$rowItem["Telphone"],
					PHP_EOL);
		}
		$currentTime=time();
		$fileName=sprintf("%s_%s.csv",date("Y-m-d",$currentTime),"大商户列表");
		header("Content-Type:text/html;charset=utf-8");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".strlen($txtStr));
		Header("Content-Disposition: attachment; filename=" . $fileName);
		echo $txtStr;		
	}
	
	public function actionIndex()
	{
		$fAppData=new FAppData();
		$agencyName	= Yii::app()->request->getParam("AgencyName",'');
		$page_no  	= Yii::app()->request->getParam('page_no',1);
		$search   	= Yii::app()->request->getParam('search','');
		$agencyCode	= Yii::app()->request->getParam("AgencyCode",'');
		$telphone	= Yii::app()->request->getParam("Telphone",'');
		$channelType= Yii::app()->request->getParam("ChannelType",'');
		$isFrozen	= Yii::app()->request->getParam("IsFrozen",'');
		$orderField	= Yii::app()->request->getParam("OrderField",'a.CreateDt');

		$this->renderData["AgencyName"]	=$agencyName;
		$this->renderData["AgencyCode"]	=$agencyCode;
		$this->renderData["Telphone"]	=$telphone;
		$this->renderData["ChannelType"]=$channelType;
		$this->renderData["IsFrozen"]	=$isFrozen;
		$this->renderData["OrderField"]	=$orderField;
		
		$export= trim(Yii::app()->request->getParam('export',""));
		if($export){
			return $this->export($fAppData,$agencyName,$agencyCode,$telphone,$channelType,$isFrozen,$orderField);
		}
		
		$page_size = 30;
		$page_no=$page_no<1?1:$page_no;
		$info =$fAppData->getAgencyPromoterNum($agencyName,$agencyCode,$telphone,$channelType,$isFrozen); 
		
		$row_count = $info['num'];
		$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
		$total_page=$total_page<1?1:$total_page;
		$page_no=$page_no>($total_page)?($total_page):$page_no;
		$start = ($page_no - 1) * $page_size;
		
		$pageUrl=$this->_next_url;
		$PageShowUrl=$pageUrl;
		if($search==1){
			if(stripos($pageUrl,"?")==false){
				$PageShowUrl=sprintf("%s?AgencyName=%s&search=1&AgencyCode=%s&Telphone=%s&ChannelType=%s&IsFrozen=%s&OrderField=%s",$pageUrl,$agencyName,$agencyCode,$telphone,$channelType,$isFrozen,$orderField);
			}else{
				$PageShowUrl=sprintf("%sAgencyName=%s&search=1&AgencyCode=%s&Telphone=%s&ChannelType=%s&IsFrozen=%s&OrderField=%s",$pageUrl,$agencyName,$agencyCode,$telphone,$channelType,$isFrozen,$orderField);
			}
		}
		$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
		$dataList = array();
		if($row_count>0){
			$dataList=$fAppData->getAgencyPromoter($agencyName,$agencyCode,$telphone,$channelType,$isFrozen,$orderField,$start,$page_size);
		}
		$this->renderData["agency"]				= $dataList;
		$this->renderData['page']				= $page_str;
		$this->renderData['page_no'] 			= $page_no;
		$this->render("index",$this->renderData);
	}
	public function actionAdd()
	{
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if ($submit){
			$agencyName		=	trim(Yii::app()->request->getParam('Name',""));
			$agencyCode		=	trim(Yii::app()->request->getParam('Code',""));			
			$agencyLoginId	=	trim(Yii::app()->request->getParam('LoginName',""));
			$agencyLoginPwd	=	trim(Yii::app()->request->getParam('LoginPwd',""));
			$agencyStartDt	=	trim(Yii::app()->request->getParam('BeginDt',""));
			$agencyEndDt	=	trim(Yii::app()->request->getParam('EndDt',""));
			$agencyPrefix	=	trim(Yii::app()->request->getParam('PromoterPrefixName',""));
			$agencyMaxNum	=	trim(Yii::app()->request->getParam('MaxPromoterNum',""));
			$agencyTelphone	=	trim(Yii::app()->request->getParam('Telphone',""));
			$agencyChannelType	=	trim(Yii::app()->request->getParam('ChannelType',""));
			$agentcyPhoneList	=	trim(Yii::app()->request->getParam('PhoneList',""));		
			
			if($agencyChannelType==2){
				$agencyPrefix="";
				$agencyMaxNum=0;
			}

			$agencyLoginPwd	=	md5($agencyLoginPwd);
			$fAppData=new FAppData();
			if($fAppData->insertAgencyPromoter($agencyName, $agencyCode, $agencyLoginId, $agencyLoginPwd, $agencyStartDt, $agencyEndDt, $agencyPrefix, $agencyMaxNum, $agencyTelphone,$agencyChannelType)>0){
				$agencyId=$fAppData->getAgencyIdByLoginName($agencyLoginId);
				if(empty($agencyId)){
					$this->alert('error',"增加大商户失败");
				}else{
					if($agencyChannelType==2 && empty($agentcyPhoneList)==false){
						$phoneList=str_replace(array("\n","\r","，","。",".",";","'","\"","`","~","#","%","*"), ",", $agentcyPhoneList);
						if(empty($phoneList)==false){
							$phones=explode(",",$phoneList);
							$phoneConditions=implode("','",$phones);
							$promoterList=$fAppData->getPromoterListByPhoneList("'".$phoneConditions."'");
							foreach ($promoterList as $row){
								$fAppData->insertAgencyPrompterPlay($agencyId, $row["PromoterId"]);
							}
							$fAppData->updateAgencyPromoterCount(count($fAppData->getAgencyPromoterPhoneInfoById($agencyId)), $agencyId);
						}
					}
					$this->exitWithSuccess("增加大商户成功",$this->_next_url);
				}
			}else{
				$this->alert('error',"增加大商户失败");
			}
		}
		$this->render("add",$this->renderData);
	}
	public function actionModify()
	{
		$agencyId	=	trim(Yii::app()->request->getParam('AgencyId',""));
		if(empty($agencyId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$fAppData=new FAppData();
		$agencyInfo=$fAppData->getAgencyPromoterById($agencyId);
		if(count($agencyInfo)<1){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$this->renderData["Promoter"]=$fAppData->getAgencyPromoterPhoneInfoById($agencyId);
		
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if ($submit){
			$agencyName		=	trim(Yii::app()->request->getParam('Name',""));
			$agencyCode		=	trim(Yii::app()->request->getParam('Code',""));
			$agencyLoginId	=	trim(Yii::app()->request->getParam('LoginName',""));
			$agencyStartDt	=	trim(Yii::app()->request->getParam('BeginDt',""));
			$agencyEndDt	=	trim(Yii::app()->request->getParam('EndDt',""));
			$agencyPrefix	=	trim(Yii::app()->request->getParam('PromoterPrefixName',""));
			$agencyMaxNum	=	trim(Yii::app()->request->getParam('MaxPromoterNum',""));
			$agencyTelphone	=	trim(Yii::app()->request->getParam('Telphone',""));
			$agencyChannelType	=	trim(Yii::app()->request->getParam('ChannelType',""));
			
			$agentcyPhoneList	=	trim(Yii::app()->request->getParam('PhoneList',""));

			if($agencyChannelType==2){
				$agencyPrefix="";
				$agencyMaxNum=0;
			}
			if($fAppData->updateAgencyPromoter($agencyName, $agencyCode, $agencyLoginId, "", $agencyStartDt, $agencyEndDt, $agencyPrefix, $agencyMaxNum, $agencyTelphone, $agencyId)>0){
				if($agencyChannelType==2){
					$fAppData->deleteAgencyPrompterPlay($agencyId);
					if(empty($agentcyPhoneList)==false){
						$phoneList=str_replace(array("\n","\r","，","。",".",";","'","\"","`","~","#","%","*"), ",", $agentcyPhoneList);
						if(empty($phoneList)==false){
							$phones=explode(",",$phoneList);
							$phoneConditions=implode("','",$phones);
							$promoterList=$fAppData->getPromoterListByPhoneList("'".$phoneConditions."'");
							foreach ($promoterList as $row){
								$fAppData->insertAgencyPrompterPlay($agencyId, $row["PromoterId"]);
							}
						}
					}
					$fAppData->updateAgencyPromoterCount(count($fAppData->getAgencyPromoterPhoneInfoById($agencyId)), $agencyId);
				}
				$this->exitWithSuccess("修改大商户",$this->_next_url);
			}else{
				$this->alert('error',"修改大商户失败");
			}
		}
		$this->renderData["Agency"]=$agencyInfo;
		$this->render("modify",$this->renderData);
	}
	public function actionMdyPwd()
	{
		$agencyId	=	trim(Yii::app()->request->getParam('AgencyId',""));
		if(empty($agencyId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$fAppData=new FAppData();
		$agencyInfo=$fAppData->getAgencyPromoterById($agencyId);
		if(count($agencyInfo)<1){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if ($submit){
			$agencyLoginPwd	=	trim(Yii::app()->request->getParam('LoginPwd',""));
			$agencyLoginPwd	=	md5($agencyLoginPwd);
			if($fAppData->updateAgencyPwd($agencyLoginPwd, $agencyId)){
				$this->exitWithSuccess("修改大商户密码成功",$this->_next_url);
			}else{
				$this->alert('error',"修改大商户密码失败");
			}
		}
		$this->renderData["Agency"]=$agencyInfo;
		$this->render("mdyPwd",$this->renderData);		
	}
	public function actionMdyLoginState()
	{
		$agencyId	=	trim(Yii::app()->request->getParam('AgencyId',""));
		if(empty($agencyId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$fAppData=new FAppData();
		$agencyInfo=$fAppData->getAgencyPromoterById($agencyId);
		if(count($agencyInfo)<1){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$IsFrozen = trim(Yii::app()->request->getParam('IsFrozen',""));
		if($IsFrozen==0 || $IsFrozen==1){
			if($fAppData->updateAgencyLoginState($IsFrozen, $agencyId)){
				$this->exitWithSuccess("修改大商户账号登录状态成功",$this->_next_url);
			}else{
				$this->exitWithError("修改大商户账号登录状态失败",$this->_next_url);
			}				
		}else{
			$this->exitWithError("参数错误",$this->_next_url);
		}
	}
	
	private function exportAmount($fAppData,$startDt, $endDt,$phoneNo,$promoterPhone,$AppId,$queryType)
	{
		if($queryType=="Pay"){
			$this->exportPay($fAppData,$startDt, $endDt,$phoneNo,$promoterPhone,$AppId);
		}else{
			$this->exportLogin($fAppData, $startDt, $endDt, $phoneNo, $promoterPhone, $AppId);
		}
	}
	private function exportLogin($fAppData,$startDt, $endDt,$phoneNo,$promoterPhone,$AppId)
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
	private function exportPay($fAppData,$startDt,$endDt,$phoneNo,$promoterPhone,$AppId)
	{
		$PayList=$fAppData->getPlayPayHistory($startDt, $endDt,$phoneNo,$promoterPhone,$AppId,-1,999);
		//BOM Magic Bytes
		$txtStr="\xEF\xBB\xBF分红用户账号,充值时间,充值金额,充值游戏,返利比例,返利金额,返利归属". PHP_EOL;
		foreach ($PayList as $rowItem){
			$Amount="0.0";
			if(empty($rowItem["Amount"])==false){
				$Amount=bcadd($Amount, $rowItem["Amount"],2);
			}
			if(empty($rowItem["TaxFee"])==false){
				$Amount=bcadd($Amount, $rowItem["TaxFee"],2);
			}
			$txtStr.=sprintf("%s,%s,%s,%s,%s,%s,%s%s",
					str_replace("+86-", "", $rowItem["PlayPhone"]),
					$rowItem["TransactDt"],
					empty($rowItem["GameAmount"])?"0.00":number_format($rowItem["GameAmount"],2,".",""),
					$rowItem["AppName"],
					number_format(bcmul($rowItem["PromoterProrate"], "100"),0,"","")."%",
					$Amount,
					$rowItem["PhoneNo"],
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
	
	public function actionAmount()
	{
		$this->renderData["PayList"]=array();
		$this->renderData["startDt"]="";
		$this->renderData["endDt"]="";
		$this->renderData["Phone"]="";
		$this->renderData["PromoterPhone"]="";
		$this->renderData["GameAmount"]="0";
		$this->renderData["PromoterAmount"]="0";
		$this->renderData["AppId"]=trim(Yii::app()->request->getParam('AppId',""));
		$this->renderData["page"]="";
		$this->renderData["queryType"]="Pay";
		$agencyId	=	trim(Yii::app()->request->getParam('AgencyId',""));
		if(empty($agencyId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$this->renderData["AgencyId"]=$agencyId;
		$fAppData=new FAppData();
		$agencyInfo=$fAppData->getAgencyPromoterById($agencyId);
		if(count($agencyInfo)<1){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$this->renderData["Agency"]=$agencyInfo;
		$this->renderData["AgencyAmount"]=$fAppData->getAgencyAmount($agencyId);
		$this->renderData["GameList"]=$fAppData->getApp();
		$this->renderData["PromoterList"]=$fAppData->getAgencyPromoterPhoneInfoById($agencyId);
		$this->renderData["ShowTableHead"]=false;
		$submit = trim(Yii::app()->request->getParam('search',0));
		if($submit){
			$this->renderData["ShowTableHead"]=true;
			$currentTime=time();
			$defaultStartDt		=	date("Y-m-d 00:00:00",$currentTime-(7*24*60*60));
			$defaultEndDt		=	date("Y-m-d 23:59:59",$currentTime);
			$startDt			=	trim(Yii::app()->request->getParam('startDt',$defaultStartDt));
			$endDt				=	trim(Yii::app()->request->getParam('endDt',$defaultEndDt));
			$phoneNo			=	trim(Yii::app()->request->getParam('Phone',""));
			$promoterPhone		=	trim(Yii::app()->request->getParam('PromoterPhone',""));
			$AppId				=	trim(Yii::app()->request->getParam('AppId',""));
			$queryType			=	trim(Yii::app()->request->getParam('queryType',"Pay"));	
			
			$orginPromoterPhone	=	$promoterPhone;
			
			$this->renderData["Phone"]=$phoneNo;
			$this->renderData["PromoterPhone"]=$promoterPhone;
			$this->renderData["AppId"]=$AppId;
			$this->renderData["startDt"]=$startDt;
			$this->renderData["endDt"]=$endDt;
			$this->renderData["queryType"]=$queryType;
	
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
			if(empty($promoterPhone)){
				$agencyPhones=$this->renderData["PromoterList"];
				$promoterPhone=array();
				foreach ($agencyPhones as $row ){
					$promoterPhone[]=$row["PhoneNo"];
				}
			}
			$export= trim(Yii::app()->request->getParam('export',""));
			if($export){
				return $this->exportAmount($fAppData,$startDt, $endDt,$phoneNo,$promoterPhone,$AppId,$queryType);
			}
			if($queryType=="Pay"){
				$row_count = $fAppData->getPlayPayHistoryCount($startDt, $endDt,$phoneNo,$promoterPhone,$AppId);
			}else{
				$row_count = $fAppData->getPlayPayLoginHistoryCount($startDt, $endDt,$phoneNo,$promoterPhone,$AppId);
			}
				
			if($row_count>=0){
					
				$page_size = 30;
				$page_no  = Yii::app()->request->getParam('page_no',1);
	
				$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
				$total_page=$total_page<1?1:$total_page;
				$page_no=$page_no>($total_page)?($total_page):$page_no;
				$start = ($page_no - 1) * $page_size;
	
				if($queryType=="Pay"){
					$PayList=$fAppData->getPlayPayHistory($startDt, $endDt,$phoneNo,$promoterPhone,$AppId,$start,$page_size);
				}else {
					$PayList=$fAppData->getPlayPayLoginHistory($startDt, $endDt,$phoneNo,$promoterPhone,$start,$page_size,$AppId);
				}
				$this->renderData["PayList"]=$PayList;
	
				$PageShowUrl="/fAppPromoterAgency/amount?search=1&AgencyId=".$agencyId."&queryType=".$queryType;
				if(empty($AppId)==false){
					$PageShowUrl.="&AppId=".$AppId;
				}
				if(empty($phoneNo)==false){
					$PageShowUrl.="&Phone=".$phoneNo;
				}
				if(empty($orginPromoterPhone)==false){
					$PageShowUrl.="&PromoterPhone=".$orginPromoterPhone;
				}
				if(empty($startDt)==false){
					$PageShowUrl.="&startDt=".substr($startDt,0,10);
				}
				if(empty($endDt)==false){
					$PageShowUrl.="&endDt=".substr($endDt,0,10);
				}
				$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
				$this->renderData["page"]=$page_str;
			}
		}else {
			$currentTime=time();
			$this->renderData["startDt"]=date("Y-m-d",$currentTime-(7*24*60*60));
			$this->renderData["endDt"]=date("Y-m-d",$currentTime);
		}
		$this->render("amount",$this->renderData);
	}

	public function actionDocIndex()
	{
		$fAppData=new FAppData();
		$docInfos=$fAppData->getSystemConfigItem("group.app.document","",true);
		$Apps=$fAppData->getApp();
		$appNames=array();
		foreach ($Apps as $rowItem){
			$appNames[$rowItem["AppId"]]=$rowItem["AppName"];
		}
		$this->renderData["AppDocuments"] =$docInfos;
		$this->renderData["AppNames"] =$appNames;
		$this->render("doc_index",$this->renderData);
	}

	public function actionDocAdd()
	{
		$this->_next_url="/fAppPromoterAgency/docIndex";
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$AppId			=Yii::app()->request->getParam("AppId","");
			$DownloadUrl	=Yii::app()->request->getParam("DownloadUrl","");
			if(empty($AppId)==false && empty($DownloadUrl)==false){
				$fAppData=new FAppData();
				$docInfos=$fAppData->getSystemConfigItem("group.app.document","",true);
				$docInfos[$AppId]=$DownloadUrl;
				if($fAppData->updateSystemConfigItem("group.app.document", json_encode($docInfos,true))>0){
					$this->exitWithSuccess(sprintf("增加%s成功","大商户游戏资料信息"),$this->_next_url);
				}else{
					$this->alert('error',sprintf("增加%s失败","大商户游戏资料信息"));
				}
			}
		}
		$this->render("doc_add");		
	}
	
	public function actionDocDel()
	{
		$this->_next_url="/fAppPromoterAgency/docIndex";
		$AppId = Yii::app()->request->getParam("appId",'');
		if(empty($AppId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$fAppData=new FAppData();
		$docInfos=$fAppData->getSystemConfigItem("group.app.document","",true);
		unset($docInfos[$AppId]);
		if($fAppData->updateSystemConfigItem("group.app.document", json_encode($docInfos,true))>0){
			$this->exitWithSuccess(sprintf("删除%s成功","大商户游戏资料信息"),$this->_next_url);
		}else{
			$this->alert('error',sprintf("删除%s失败","大商户游戏资料信息"));
		}
	}
	
	public function actionDocModify()
	{
		$this->renderData["AppName"]="";
		$this->renderData["DownloadUrl"]="";
		
		
		$appIdKey = trim(Yii::app()->request->getParam('appId',""));
		
		$this->_next_url="/fAppPromoterAgency/docIndex";
		$this->renderData["AppId"]=$appIdKey;
		
		$submit = trim(Yii::app()->request->getParam('submit',0));
		
		$fAppData=new FAppData();
		$docInfos=$fAppData->getSystemConfigItem("group.app.document","",true);
		
		if($submit){
			$NewAppId			=Yii::app()->request->getParam("NewAppId","");
			$DownloadUrl	=Yii::app()->request->getParam("DownloadUrl","");
			
			if(empty($NewAppId)==false && empty($DownloadUrl)==false){
				unset($docInfos[$appIdKey]);
				$docInfos[$NewAppId]=$DownloadUrl;
				
				if($fAppData->updateSystemConfigItem("group.app.document", json_encode($docInfos,true))>0){
					$this->exitWithSuccess(sprintf("修改%s成功","大商户游戏资料信息"),$this->_next_url);
				}else{
					$this->alert('error',sprintf("修改%s失败","大商户游戏资料信息"));
				}
			}
		}else{
			if(isset($docInfos[$appIdKey])){
				
				$this->renderData["DownloadUrl"]=$docInfos[$appIdKey];
				$Apps=$fAppData->getApp();
				$appNames=array();
				foreach ($Apps as $rowItem){
					if($rowItem["AppId"]==$appIdKey){
						$this->renderData["AppName"]=$rowItem["AppName"];
						break;
					}
				}
			}
		}
		$this->render("doc_modify",$this->renderData);		
	}
	
}
?>