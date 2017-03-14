<?php

class FAppPromoterTaxController extends TableMagtController 
{
	private $_tableName="PromoterTax";
	private $_searchName="";
	private $_next_url="/fAppPromoterTax/index";
	private $_columns=array("");
	private $_title="分红管理后台-返税计算";
	private $_primaryKey="RecId";
	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
	}
	
	public  function actionCalc()
	{
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$Period				=	trim(Yii::app()->request->getParam('Period',""));
			$PhoneNo			=	trim(Yii::app()->request->getParam('PhoneNo',""));
			if(empty($Period)){
				$this->exitWithError("请输入记税周期", "/fAppPromoterTax/cacl");	
			}
			$PromoterId="";
			if(empty($PhoneNo)==false){
				$fAppData=new FAppData();
				$PromoterInfo=$fAppData->getPromoterByPhone($PhoneNo);
				if(count($PromoterInfo)<=0){
					$this->exitWithError("手机号码错误", "/fAppPromoterTax/cacl");	
				}
				$PromoterId=$PromoterInfo["PromoterId"];
			}			
			list($Year,$Month)=explode("-", $Period);
			FAppPromoterTax::getInstance()->Calculator($Year, $Month);
			$this->exitWithSuccess("返税计算完成.", "/fAppPromoterTax/index");
		}
		$this->render("calc");
	}
	
	private function export($fAppData,$PhoneNo,$Period)
	{
		$taxList=$fAppData->getPromoterTax($PhoneNo, $Period,-1,999);
		//BOM Magic Bytes
		$txtStr="\xEF\xBB\xBF推广员账号,记税周期,不记税收入,记税收入,应执行税率,应纳税总额,预交税总额,应返还金额". PHP_EOL;
		foreach ($taxList as $rowItem){
			$txtStr.=sprintf("%s,%s,%s,%s,%s,%s,%s,%s%s",
					$rowItem["PhoneNo"],
					$rowItem["TaxPeriod"],
					$rowItem["AmountNoTax"],
					$rowItem["AmountTax"],
					$rowItem["TaxPercent"],
					$rowItem["TaxFee"],
					$rowItem["PreTaxFee"],
					$rowItem["Refund"],
					PHP_EOL);
		}
		$currentTime=time();
		$fileName=sprintf("%s_%s.csv",date("Y-m-d",$currentTime),"月底返税计算");
		header("Content-Type:text/html;charset=utf-8");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".strlen($txtStr));
		Header("Content-Disposition: attachment; filename=" . $fileName);
		echo $txtStr;
	}
	
	public function actionIndex()
	{
		$this->renderData["TaxList"]=array();
		$this->renderData["PhoneNo"]="";
		$this->renderData["Period"]="";
		$this->renderData["page"]="";
		
		$submit = trim(Yii::app()->request->getParam('search',0));
		if($submit){
			$PhoneNo			=	trim(Yii::app()->request->getParam('PhoneNo',""));
			$Period				=	trim(Yii::app()->request->getParam('Period',""));
			$this->renderData["PhoneNo"]=$PhoneNo;
			$this->renderData["Period"]=$Period;
			
			$fAppData=new FAppData();
			$export= trim(Yii::app()->request->getParam('export',""));
			if($export){
				return $this->export($fAppData,$PhoneNo, $Period);
			}
			
			$row_count = $fAppData->getPromoterTaxCount($PhoneNo, $Period);
			if($row_count>0){
				$page_size = 50;
				$page_no  = Yii::app()->request->getParam('page_no',1);
				
				$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
				$total_page=$total_page<1?1:$total_page;
				$page_no=$page_no>($total_page)?($total_page):$page_no;
				$start = ($page_no - 1) * $page_size;
				
				$taxList=$fAppData->getPromoterTax($PhoneNo, $Period,$start,$page_size);
				$this->renderData["TaxList"]=$taxList;
				
				$PageShowUrl="/fAppPromoterTax/index?search=1";
				if(empty($PhoneNo)==false){
					$PageShowUrl.="&PhoneNo=".$PhoneNo;
				}
				if(empty($Period)==false){
					$PageShowUrl.="&Period=".$Period;
				}
				$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
				$this->renderData["page"]=$page_str;
			}
		}
		$this->render("index",$this->renderData);
	}
}
?>