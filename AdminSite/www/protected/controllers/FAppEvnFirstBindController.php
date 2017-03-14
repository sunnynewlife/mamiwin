<?php

LunaLoader::import("luna_lib.util.CGuidManager");

class FAppEvnFirstBindController extends TableMagtController 
{
	private $_title="分红管理后台-下家游戏首次绑定查询";
	private $_next_url="/fAppEvnFirstBind/index";
	
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
	private function export($fAppData,$startDt, $endDt,$phoneNo,$promoterPhone,$AppId)
	{
		$loginList=$fAppData->getAppPromoterPlay($AppId,$startDt, $endDt,$phoneNo,$promoterPhone,-1,999);
		//BOM Magic Bytes
		$txtStr="\xEF\xBB\xBF分红用户账号,所属推广员,绑定时间,绑定游戏". PHP_EOL;
		foreach ($loginList as $rowItem){
			$txtStr.=sprintf("%s,%s,%s,%s%s",
					str_replace("+86-", "", $rowItem["PlayPhone"]),
					$rowItem["PromoterPhone"],
					$rowItem["CreateDt"],
					$rowItem["AppName"],
					PHP_EOL);
		}
		$currentTime=time();
		$fileName=sprintf("%s_%s_%s.csv",date("Y-m-d",$currentTime),$AppId,"首次绑定");
		header("Content-Type:text/html;charset=utf-8");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".strlen($txtStr));
		Header("Content-Disposition: attachment; filename=" . $fileName);
		echo $txtStr;		
	}
	
	public function actionIndex()
	{
		$this->renderData["PlayPromoterList"]=array();
		$this->renderData["startDt"]="";
		$this->renderData["endDt"]="";
		$this->renderData["Phone"]="";
		$this->renderData["PromoterPhone"]="";
		$this->renderData["AppId"]="";
		$this->renderData["page"]="";
		
		$fAppData=new FAppData();
		$this->renderData["GameList"]=$fAppData->getApp();
		
		$submit = trim(Yii::app()->request->getParam('search',0));
		if($submit){
			$currentTime=time();
			$startDt			=	trim(Yii::app()->request->getParam('startDt',""));
			$endDt				=	trim(Yii::app()->request->getParam('endDt',""));
			$phoneNo			=	trim(Yii::app()->request->getParam('Phone',""));
			$promoterPhone		=	trim(Yii::app()->request->getParam('PromoterPhone',""));
			$AppId				=	trim(Yii::app()->request->getParam('AppId',""));
			
			$this->renderData["Phone"]=$phoneNo;
			$this->renderData["PromoterPhone"]=$promoterPhone;
			$this->renderData["AppId"]=$AppId;
			$this->renderData["startDt"]=$startDt;
			$this->renderData["endDt"]=$endDt;
				
			$queryStart="";
			$queryEnd="";
			if(empty($startDt)==false){
				$queryStart=sprintf("%s 00:00:00",$startDt);
			}
			if(empty($endDt)==false){
				$queryEnd=sprintf("%s 23:59:59",$endDt);
			}
			
			$export= trim(Yii::app()->request->getParam('export',""));
			if($export){
				return $this->export($fAppData,$queryStart, $queryEnd,$phoneNo,$promoterPhone,$AppId);
			}
			$row_count = $fAppData->getAppPromoterPlayCount($AppId,$queryStart, $queryEnd,$phoneNo,$promoterPhone);
			if($row_count>0){
			
				$page_size = 50;
				$page_no  = Yii::app()->request->getParam('page_no',1);

				$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
				$total_page=$total_page<1?1:$total_page;
				$page_no=$page_no>($total_page)?($total_page):$page_no;
				$start = ($page_no - 1) * $page_size;
				
				$PayList=$fAppData->getAppPromoterPlay($AppId,$queryStart, $queryEnd,$phoneNo,$promoterPhone,$start,$page_size);
				$this->renderData["PlayPromoterList"]=$PayList;
				
				$PageShowUrl="/fAppEvnFirstBind/index?search=1";
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
					$PageShowUrl.="&startDt=".$startDt;
				}
				if(empty($endDt)==false){
					$PageShowUrl.="&endDt=".$endDt;
				}
				$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
				$this->renderData["page"]=$page_str;				
			}
		}
		$this->render("index",$this->renderData);		
	} 
}
?>