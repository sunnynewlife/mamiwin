<?php

class FAppPackageController extends TableMagtController 
{
	private $_title="分红管理后台-游戏安装包";
	private $_next_url="/fAppPackage/index";
	
	private $_tableName="";
	private $_searchName="";
	private $_columns=array();
	private $_primaryKey="";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
		$this->_SERACH_FIELD_COMPARE_TYPE=array("AppName" =>"like");
		
	}
	
	public function actionIndex()
	{
		$appProfits=array();
		$submit = trim(Yii::app()->request->getParam('search',0));
		if ($submit){
			$appId	=trim(Yii::app()->request->getParam('AppId',""));		
			$appName=trim(Yii::app()->request->getParam('AppName',""));
			
			$conditionColumn="";
			$conditionValue="";
			if(empty($appId)==false){
				$conditionColumn="AppId";
				$conditionValue=$appId;
			}
			if(empty($appName)==false){
				$conditionColumn="AppName";
				$conditionValue=$appName;
			}
			$fAppData=new FAppData();
			$appViews=$fAppData->getViewApps($conditionColumn, $conditionValue);
			foreach ($appViews as $itemRow){
				$appProfitRow=array("AppId" => $itemRow["AppId"],"AppName" => $itemRow["AppName"]);
				$appProfitRow["ProfitApply"] =$fAppData->getAppApply($itemRow["AppId"]);
				$appProfitRow["ProfitDownload"]=$fAppData->getAppDownloadCount($itemRow["AppId"]);
				$appProfitRow["ProfitLoginCount"]=$fAppData->getAppLoginCount($itemRow["AppId"]);
				$appProfitRow["ProfitPayCount"]=$fAppData->getAppPayCount($itemRow["AppId"]);
				$appProfitRow["ProfitDeposit"]=$itemRow["Deposit"];
				$appProfits[]=$appProfitRow;
			}
		}
		$this->renderData["AppProfit"]=$appProfits;
		$this->render("index",$this->renderData);		
	} 
	
	private function export($fAppData,$AppId,$phone)
	{
		$RowItem=array();
		$PhoneVersionList=$fAppData->getPhoneVersionReportData($AppId,$phone,-1,999);
		if(count($PhoneVersionList)>0){
			foreach ($PhoneVersionList as $row){
				if(isset($RowItem[$row["PhoneNo"]])==false){
					$item=array(
						"CreateDt" 		=> $row["UpdateDt"],
						"LoginCount"	=>	0,
						"VersionList"	=> array(),
					);
					$RowItem[$row["PhoneNo"]]=$item;
				}
				$item=$RowItem[$row["PhoneNo"]];
				if(empty($row["LoginCount"])==false){
						$item["LoginCount"]+=$row["LoginCount"];
				}
				$item["VersionList"][]=array(
						"VersionName"	=> $row["VersionName"],
						"DowloadCount"	=> $row["DowloadCount"],
						"PayCount"		=> $row["PayCount"],
						"GameAmount"	=> $row["GameAmount"],
						"PromoterAmount"=> $row["PromoterAmount"],
				);
				$RowItem[$row["PhoneNo"]]=$item;
			}
		}
		//BOM Magic Bytes
		$txtStr="\xEF\xBB\xBF推广员账号,申请时间 ,累计登录人数,游戏版本,累计下载次数,累计充值人数,累计充值金额,累计获得返利". PHP_EOL;
		foreach ($RowItem as $phone => $row){
			foreach ($row["VersionList"] as $itemRow){
				$txtStr.=sprintf("%s,%s,%s,%s,%s,%s,%s,%s%s",
						$phone,
						$row["CreateDt"],
						number_format($row["LoginCount"],0,".",""),
						$itemRow["VersionName"],
						number_format($itemRow["DowloadCount"],0,".",""),
						number_format($itemRow["PayCount"],0,".",""),
						number_format($itemRow["GameAmount"],2,".",""),
						number_format($itemRow["PromoterAmount"],2,".",""),
						PHP_EOL);				
			}
		}
		$currentTime=time();
		$fileName=sprintf("%s_%s.csv",date("Y-m-d",$currentTime),$AppId);
		header("Content-Type:text/html;charset=utf-8");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".strlen($txtStr));
		Header("Content-Disposition: attachment; filename=" . $fileName);
		echo $txtStr;
	}
	
	public function actionDetail()
	{
		$AppId=trim(Yii::app()->request->getParam('AppId',""));	
		if(empty($AppId)){
			header("Location:"."/fAppPackage/index");
			return;
		}
		$this->renderData["Phone"]="";
		$this->renderData["DetailList"]=array();
		$this->renderData["page"]="";
		$fAppData=new FAppData();
		$appInfo=$fAppData->getAppByAppId($AppId);
		$phone= trim(Yii::app()->request->getParam('Phone',""));
		if(count($appInfo)>0){
			$this->renderData["AppName"]=$appInfo["AppName"];
			$this->renderData["PromoterCount"]=number_format($fAppData->getAppPackageCount($AppId),0,".",",");
			$search= trim(Yii::app()->request->getParam('search',""));
			if($search){
				$export= trim(Yii::app()->request->getParam('export',""));
				if($export){
					return $this->export($fAppData,$AppId,$phone);
				}				
				$this->renderData["Phone"]=$phone;
				$RowItem=array();
				$row_count = $fAppData->getPhoneVersionReportDataCount($AppId,$phone);
				if($row_count >0){
					$page_size = 50;
					$page_no  = Yii::app()->request->getParam('page_no',1);

					$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
					$total_page=$total_page<1?1:$total_page;
					$page_no=$page_no>($total_page)?($total_page):$page_no;
					$start = ($page_no - 1) * $page_size;
						
					
					$PhoneVersionList=$fAppData->getPhoneVersionReportData($AppId,$phone,$start,$page_size);
					if(count($PhoneVersionList)>0){
						foreach ($PhoneVersionList as $row){
							if(isset($RowItem[$row["PhoneNo"]])==false){
								$item=array(
									"CreateDt" 		=> $row["UpdateDt"],
									"LoginCount"	=>	0,
									"VersionList"	=> array(),	
								);
								$RowItem[$row["PhoneNo"]]=$item;
							}
							$item=$RowItem[$row["PhoneNo"]];
							if(empty($row["LoginCount"])==false){
								$item["LoginCount"]+=$row["LoginCount"];
							}
							$item["VersionList"][]=array(
								"VersionName"	=> $row["VersionName"],
								"DowloadCount"	=> $row["DowloadCount"],
								"PayCount"		=> $row["PayCount"],
								"GameAmount"	=> $row["GameAmount"],
								"PromoterAmount"=> $row["PromoterAmount"],
							);
							$RowItem[$row["PhoneNo"]]=$item;
						}
						$PageShowUrl=sprintf("/fAppPackage/detail?search=1&AppId=%s",$AppId);
						if(empty($phone)==false){
							$PageShowUrl.="&Phone=".$phone;
						}
						$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
						$this->renderData["page"]=$page_str;
					}
				}
				
				$this->renderData["DetailList"]=$RowItem;
			}
		}else{
			$this->renderData["AppName"]="";
			$this->renderData["PromoterCount"]="";
		}
		$this->renderData["AppId"] =$AppId;
		$this->render("detail",$this->renderData);		
	}
}
?>