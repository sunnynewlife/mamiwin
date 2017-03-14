<?php

class QAppEvnController extends TableMagtController 
{
	private $_title="分红管理后台-微信活动配置";
	private $_next_url="/qAppEvn/index";
	
	private $_tableName="Q_AppPromotionEvn";
	private $_searchName="";
	private $_columns=array();
	private $_primaryKey="IDX";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
	}
	
	private function getMaxValue($arr,$name)
	{
		$v=-10000;
		foreach ($arr as $item){
			if($item[$name]>$v){
				$v=$item[$name];
			}
		}
		return $v;
	}
	private function calcEvnJoinStatus($current,$startDt,$endDt,$evnQty,$evnJoinQty)
	{
		list($y,$m,$d)=explode("-",$startDt);
		$startTime=mktime(0,0,0,$m,$d,$y);
		list($y,$m,$d)=explode("-",$endDt);
		$endTime=mktime(23,59,59,$m,$d,$y);
		if($current<$startTime){
			return "1";
		}
		if($current>$endTime){
			if($evnJoinQty>=$evnQty){
				return "5";
			}else{
				return "4";
			}
		}
		if($evnJoinQty>=$evnQty){
			return "3";
		}else{
			return "2";
		}
	}
	private function convertEvnJoinStatusStr($v)
	{
		switch ($v){
			case "1":
				return "未开始";
			case "2":
				return "已开始(未成团)";
			case "3":
				return "已开始(已成团)";
			case "4":
				return "已过期(未成团)";
			case "5":
				return "已过期(已成团)";
			default:
				return $v;
		}
	}
	//过滤&填充活动参与状态
	private function calcShownData($evnList,$envStatus)
	{
		$currentTime=time();
		$shownArray=array();
		foreach($evnList as $evnItem){
			$nItem=array(
				"idx"		=>		$evnItem["IDX"],
				"AppName"	=>		$evnItem["AppName"],
				"EvnStart"	=>		substr($evnItem["EvnStart"],0,10),
				"EvnEnd"	=>		substr($evnItem["EvnEnd"],0,10),
				"EvnName"	=>		$evnItem["EvnName"],
				"EvnOrder"	=>		$evnItem["EvnOrder"],
				"EvnType"	=>		$evnItem["EvnType"],
				"EvnQty"	=>		$evnItem["EvnQty"],
				"Prorate"	=>		$evnItem["Prorate"],
				"Status"	=>		$evnItem["Status"],
				"EvnJoinQty"	=>		$evnItem["EvnJoinQty"],
				"EvnJoinRandQty"	=>	$evnItem["EvnJoinRandQty"],
			);
			if($evnItem["EvnType"]==2){
				$dynProrateCfg		=	json_decode($evnItem["EvnAdvProrate"],true);
				$nItem["EvnQty"]	=	$this->getMaxValue($dynProrateCfg, "qty");
			}
			$nItem["EvnJoinStatusValue"]	=$this->calcEvnJoinStatus($currentTime, $nItem["EvnStart"], $nItem["EvnEnd"], $nItem["EvnQty"], $evnItem["EvnJoinRandQty"]);
			$nItem["EvnJoinStatus"]=$this->convertEvnJoinStatusStr($nItem["EvnJoinStatusValue"]);
			if(empty($envStatus) || $envStatus==$nItem["EvnJoinStatusValue"]){
				$shownArray[]=$nItem;
			}
		}	
		return $shownArray;	
	}
	
	public function actionIndex()
	{
		$this->renderData['page']				= "";
		$this->renderData['page_no'] 			= "";
		$this->renderData["evn_list"]			=array();
		
		$this->renderData["startDt"]	="";
		$this->renderData["endDt"]		="";
		$this->renderData["AppName"]	="";
		$this->renderData["EvnName"]	="";
		$this->renderData["EvnType"]	="";
		$this->renderData["EvnStatus"]	="";
		
		$startDt	=	Yii::app()->request->getParam('startDt','');
		$endDt		=	Yii::app()->request->getParam('endDt','');
		$appName	=	Yii::app()->request->getParam('AppName','');
		$evnName	=	Yii::app()->request->getParam('EvnName','');
		$evnType	=	Yii::app()->request->getParam('EvnType','');
		
		$evnStatus	=	Yii::app()->request->getParam('EvnStatus','');
		
		$this->renderData["startDt"]	=$startDt;
		$this->renderData["endDt"]		=$endDt;
		$this->renderData["AppName"]	=$appName;
		$this->renderData["EvnName"]	=$evnName;
		$this->renderData["EvnType"]	=$evnType;
		$this->renderData["EvnStatus"]	=$evnStatus;
			
		
		
		$fAppData=new FAppData();
		$evnList=$fAppData->getQ_AppPromotionEvnList($startDt,$endDt,$appName,$evnName,$evnType);
		$shownArray=$this->calcShownData($evnList, $evnStatus);
		
		$page_no  = Yii::app()->request->getParam('page_no',1);
		$page_size=50;
		
		$page_no=$page_no<1?1:$page_no;
		$row_count=count($shownArray);

		$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
		$total_page=$total_page<1?1:$total_page;
		$page_no=$page_no>($total_page)?($total_page):$page_no;
		$start = ($page_no - 1) * $page_size;
			
		$PageShowUrl="/qAppEvn/index?search=1";
		if(empty($startDt)==false){
			$PageShowUrl.="&startDt=".$startDt;
		}
		if(empty($endDt)==false){
			$PageShowUrl.="&endDt=".$endDt;
		}
		if(empty($appName)==false){
			$PageShowUrl.="&AppName=".$appName;
		}
		if(empty($evnName)==false){
			$PageShowUrl.="&EvnName=".$evnName;
		}
		if(empty($evnType)==false){
			$PageShowUrl.="&EvnType=".$evnType;
		}
		if(empty($evnStatus)==false){
			$PageShowUrl.="&EvnStatus=".$evnStatus;
		}
		$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
		$this->renderData["page"]=$page_str;
		$this->renderData["evn_list"]=array_splice($shownArray, $start,$page_size);
			
		$this->render("index",$this->renderData);		
	}
	
	public function actionAdd()
	{
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$appId		=	Yii::app()->request->getParam("AppId");
			$picId		=	Yii::app()->request->getParam("FileId");
			$evnName	=	Yii::app()->request->getParam("EvnName");
			$evnType	=	Yii::app()->request->getParam("EvnType");
			$evnStart	=	Yii::app()->request->getParam("startDt");
			$evnEnd		=	Yii::app()->request->getParam("endDt");
			$joinType	=	Yii::app()->request->getParam("EvnJoinType");
			$evnContent	=	Yii::app()->request->getParam("EvnContent");
			$evnRandMin	=	Yii::app()->request->getParam("EvnRandMin","0");
			$evnRandMax	=	Yii::app()->request->getParam("EvnRandMax","0");
			$evnStatus	=	Yii::app()->request->getParam("Status");
			$evnOrder	=	Yii::app()->request->getParam("EvnOrder");
			$aclList	=	Yii::app()->request->getParam("AclList","");
			$evnIntro	=	Yii::app()->request->getParam("EvnIntro");
			
			$evnQty		=	Yii::app()->request->getParam("EvnQty","0");
			$evnProrate	=	Yii::app()->request->getParam("Prorate","0");
			$dynProrate	=	Yii::app()->request->getParam("DynProrateCfg","");
			$bProrate	=	Yii::app()->request->getParam("BaseProrate","0");
			
			$bProrate	=bcdiv($bProrate,"100",2);
			
			if($evnType=="2"){
				$evnQty=0;
				$evnProrate=0.00;
				
				$dynCfgArray=array();
				$mulProrateCfg=explode("|",$dynProrate);
				foreach ($mulProrateCfg as $item){
					if(empty($item)==false){
						list($itemQty,$itemProrate)=explode(",",$item);
						$dynCfgArray[]=array(
							"qty"		=>	$itemQty,
							"prorate"	=>	bcdiv($itemProrate,"100",2),	
						);
						if($itemQty>$evnQty){
							$evnQty=$itemQty;
						}
						if($itemProrate>$evnProrate){
							$evnProrate=$itemProrate;
						}
					}
				}
				$dynProrate	=	json_encode($dynCfgArray);
				$evnProrate=bcdiv($evnProrate,"100",2);
			}else{
				$evnProrate=bcdiv($evnProrate,"100",2);
				$dynProrate="";
			}
			
			$fAppData=new FAppData();
			$timeConficted=false;  //检查时间是否冲突
			if($evnStatus=="1" || $evnStatus=="2"){
				$timeConficted=$fAppData->isConfictedNew($appId, $evnStart." 00:00:00",$evnEnd." 23:59:59");	
			}
			if($timeConficted){
				$this->alert('error',"增加微信活动时间有冲突！");
			}else{
				if($fAppData->insertQ_AppPromotionEvn($appId,$picId,$evnName,$evnType,$evnStart." 00:00:00",$evnEnd." 23:59:59",
						$joinType,$evnContent,$evnRandMin,$evnRandMax,$evnOrder,$evnQty,$evnProrate,$dynProrate,$evnStatus,$aclList,$bProrate,$evnIntro))
				{
					$this->exitWithSuccess("增加微信活动配置","/qAppEvn/index");
				}else{
					$this->alert('error',"增加微信活动配置失败");
				}
			}
		}
		$this->render("add",$this->renderData);
	}
	
	private function  convertAdvProrate($item)
	{
		if(empty($item) || count($item)==0){
			return array(
				"AppId" 	=>	"",
				"AppName" 	=>	"",
				"EvnStart"	=>	"",	
				"EvnEnd"	=>	"",
				"EvnName"	=>	"",
				"EvnOrder"	=>	"",
				"EvnType"	=>	"1",
				"EvnQty"	=>	"",
				"Prorate"	=>	"",
				"EvnAdvProrate"	=>	"",
				"EvnJoinQty"	=>"",
				"EvnJoinRandQty"	=>"",
				"Status"		=>	"",
				"AclList"		=>	"",
				"FileId"		=>	"",
				"EvnRandMin"	=>	"",
				"EvnRandMax"	=>	"",
				"EvnContent"	=>	"",
				"EvnJoinType"	=>	"",
				"AdvDynProrate"	=>	array(),
				"BaseProrate"	=>	"",
			);
		}
		$item["EvnStart"]	=substr($item["EvnStart"], 0,10);
		$item["EvnEnd"]		=substr($item["EvnEnd"], 0,10);
		$item["BaseProrate"]=bcmul($item["BaseProrate"],"100",0);
		
		if($item["EvnType"]=="2"){
			$item["AdvDynProrate"]	=json_decode($item["EvnAdvProrate"],true);
			$item["EvnQty"]		="";
			$item["Prorate"]	="";
		}else{
			$item["Prorate"]	=bcmul($item["Prorate"], "100",0);
			$item["AdvDynProrate"]	=array();	
		}
		return $item;
	}
	
	public function actionModify()
	{
		$idx=Yii::app()->request->getParam('idx',"");
		$submit = trim(Yii::app()->request->getParam('submit',0));
		$fAppData=new FAppData();
		$promotionEvn=$fAppData->getQ_AppPromotionEvnByIdx($idx);
		if($promotionEvn==false || is_array($promotionEvn)==false || count($promotionEvn)==0){
			$this->exitWithError("参数值错误","/qAppEvn/index");
		}
		$promotionEvn=$this->convertAdvProrate($promotionEvn);
		$this->renderData["QEvnItem"]=$promotionEvn;
		if($submit){
			
			$appId		=	Yii::app()->request->getParam("AppId");
			$picId		=	Yii::app()->request->getParam("FileId");
			$evnName	=	Yii::app()->request->getParam("EvnName");
			$evnType	=	Yii::app()->request->getParam("EvnType");
			$evnStart	=	Yii::app()->request->getParam("startDt");
			$evnEnd		=	Yii::app()->request->getParam("endDt");
			$joinType	=	Yii::app()->request->getParam("EvnJoinType");
			$evnContent	=	Yii::app()->request->getParam("EvnContent");
			$evnRandMin	=	Yii::app()->request->getParam("EvnRandMin","0");
			$evnRandMax	=	Yii::app()->request->getParam("EvnRandMax","0");
			$evnStatus	=	Yii::app()->request->getParam("Status");
			$evnOrder	=	Yii::app()->request->getParam("EvnOrder");
			$aclList	=	Yii::app()->request->getParam("AclList","");
			$evnIntro	=	Yii::app()->request->getParam("EvnIntro");
				
			$evnQty		=	Yii::app()->request->getParam("EvnQty","0");
			$evnProrate	=	Yii::app()->request->getParam("Prorate","0");
			$dynProrate	=	Yii::app()->request->getParam("DynProrateCfg","");
			
			$bProrate	=	Yii::app()->request->getParam("BaseProrate","0");
			$bProrate	=bcdiv($bProrate,"100",2);
				
			if($evnType=="2"){
				$evnQty=0;
				$evnProrate=0.00;
				
				$dynCfgArray=array();
				$mulProrateCfg=explode("|",$dynProrate);
				foreach ($mulProrateCfg as $item){
					if(empty($item)==false){
						list($itemQty,$itemProrate)=explode(",",$item);
						$dynCfgArray[]=array(
								"qty"		=>	$itemQty,
								"prorate"	=>	bcdiv($itemProrate,"100",2),
						);
						if($itemQty>$evnQty){
							$evnQty=$itemQty;
						}
						if($itemProrate>$evnProrate){
							$evnProrate=$itemProrate;
						}
					}
				}
				$evnProrate=bcdiv($evnProrate,"100",2);
				$dynProrate	=	json_encode($dynCfgArray);
				
			}else{
				$evnProrate=bcdiv($evnProrate,"100",2);
				$dynProrate="";
			}
				
			$fAppData=new FAppData();
			$timeConficted=false;  //检查时间是否冲突
			if($evnStatus=="1" || $evnStatus=="2"){
				$timeConficted=$fAppData->isConfictedNewExcludeSelf($appId, $evnStart." 00:00:00",$evnEnd." 23:59:59",$idx);
			}
			if($timeConficted){
				$this->alert('error',"修改微信活动时间有冲突！");
			}else{
				if($fAppData->updateQ_AppPromotionEvn($appId,$picId,$evnName,$evnType,$evnStart." 00:00:00",$evnEnd." 23:59:59",
						$joinType,$evnContent,$evnRandMin,$evnRandMax,$evnOrder,$evnQty,$evnProrate,$dynProrate,$evnStatus,$aclList,$idx,$bProrate,$evnIntro))
				{
					$this->exitWithSuccess("修改微信活动配置","/qAppEvn/index");
				}else{
					$this->alert('error',"修改微信活动配置失败");
				}
			}			
			
		}
		$this->render("modify",$this->renderData);
	}
	
	private function export($fAppData,$idx,$phoneNo)
	{
		$PayList=$fAppData->getQ_AppPromotionEvnJoiner($idx, $phoneNo, -1,999);
		//BOM Magic Bytes
		$txtStr="\xEF\xBB\xBF参与账号,参加时间,参与IP". PHP_EOL;
		foreach ($PayList as $rowItem){
			$txtStr.=sprintf("%s,%s,%s%s",
					str_replace("+86-", "", $rowItem["PhoneNo"]),
					$rowItem["CreateDt"],
					$rowItem["ClientIp"],
					PHP_EOL);
		}
		$currentTime=time();
		$fileName=sprintf("%s_%s.csv",date("Y-m-d",$currentTime),"活动参与列表");
		header("Content-Type:text/html;charset=utf-8");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".strlen($txtStr));
		Header("Content-Disposition: attachment; filename=" . $fileName);
		echo $txtStr;
	}	
	
	public function actionQuery()
	{
		$idx=Yii::app()->request->getParam('idx',"");
		$submit = trim(Yii::app()->request->getParam('submit',0));
		$fAppData=new FAppData();
		$promotionEvn=$fAppData->getQ_AppPromotionEvnByIdx($idx);
		if($promotionEvn==false || is_array($promotionEvn)==false || count($promotionEvn)==0){
			$this->exitWithError("参数值错误","/qAppEvn/index");
		}
		$this->renderData["EVN_IDX"]=$idx;
		$promotionEvn=$this->convertAdvProrate($promotionEvn);
		$this->renderData["QEvnItem"]=$promotionEvn;
		
		$phoneNo=Yii::app()->request->getParam("PhoneNo","");
		$this->renderData["PhoneNo"]=$phoneNo;
		$this->renderData["page"]="";
		$this->renderData["joiner"]=array();
		
		$fAppData=new FAppData();
		$export= trim(Yii::app()->request->getParam('export',""));
		if($export){
			return $this->export($fAppData,$idx,$phoneNo);
		}
		$row_count = $fAppData->getQ_AppPromotionEvnJoinerCount($idx, $phoneNo);
		if($row_count>0){
			$page_size = 50;
			$page_no  = Yii::app()->request->getParam('page_no',1);
			
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
			
			$joinerList=$fAppData->getQ_AppPromotionEvnJoiner($idx, $phoneNo, $start, $page_size);
			$this->renderData["joiner"]=$joinerList;
			
			$PageShowUrl="/qAppEvn/query?idx=".$idx."&PhoneNo=".$phoneNo;
			$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
			$this->renderData["page"]=$page_str;
		}
		$this->render("query",$this->renderData);
	}
}
?>