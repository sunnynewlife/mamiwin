<?php

class QAppLotteryController extends TableMagtController 
{
	private $_title="分红管理后台-微信抽奖查询";
	private $_next_url="/qAppLottery/index";
	
	private $_tableName="Q_AppPromotionLottery";
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
		$this->renderData["Lottery_list"]			=array();
		
		$this->renderData["LotteryDt"]		="";
		
		$LotteryDt	=Yii::app()->request->getParam("LotteryDt");

		$this->renderData["LotteryDt"]		=$LotteryDt;
		$fAppData=new FAppData();
		$currentLotteryInfo = $fAppData->getSystemConfigItem("wx.lottery",array(),true);
		$eventId = $currentLotteryInfo['eventid'];
		$pageId = $currentLotteryInfo['pageid'];
		
		$row_count=$fAppData->getQ_AppLotteryPlayerListCount($eventId,$LotteryDt,$pageId);
		if($row_count>0){
			$page_no  = Yii::app()->request->getParam('page_no',1);
			$page_size=50;
			
			$page_no=$page_no<1?1:$page_no;
				
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
				
			$PageShowUrl="/qAppLottery/index?search=1";
			if(empty($AppName)==false){
				$PageShowUrl.="&AppName=".$AppName;
			}
			if(empty($status)==false){
				$PageShowUrl.="&Status=".$status;
			}
			if(empty($LotteryIdx)==false){
				$PageShowUrl.="&LotteryIdx=".$LotteryIdx;
			}
			if(empty($$IsExpired)==false){
				$PageShowUrl.="&$IsExpired=".$$IsExpired;
			}
			$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
			$data = $fAppData->getQ_AppLotteryPlayerList($eventId,$LotteryDt,$pageId,$start, $page_size);
			foreach ($data as $listkey => &$listvalue) {
				$AppNames = "";
				foreach ($listvalue as $Lottery_key => $Lottery_value) {
					if($Lottery_key=="EndDt"){
						if(is_null($Lottery_value) || empty($Lottery_value) || substr($Lottery_value, 0,10) == "0000-00-00" ){
							$IsExpired = "0";
						}else if(strtotime(substr($Lottery_value, 0,10)) >= strtotime(date("Y-m-d"))){
							$IsExpired = "1";
						}else{
							$IsExpired = "2";
						}						
					}else if($Lottery_key=="AppIds"){
						$AppNames = $this->getAppNamesByAppIds($Lottery_value);
					}
				}	
				$listvalue = array_merge($listvalue,array('AppNames'=>$AppNames));
				// $listvalue = array_merge($listvalue,array('IsExpired'=>$IsExpired));			
			}
			$this->renderData["page"]=$page_str;
			$this->renderData["Lottery_list"]=$data;
		}
		$this->render("index",$this->renderData);			
	}


	public function actionQuery(){
		$this->renderData["lottery_list"]			=array();
			
		$fAppData=new FAppData();
		$currentLotteryInfo = $fAppData->getSystemConfigItem("wx.lottery",array(),true);
		$eventId = $currentLotteryInfo['eventid'];
		$pageId = $currentLotteryInfo['pageid'];
		$data = $fAppData->getLotteryInfo($eventId);
		foreach ($data as $listkey => &$listvalue) {
			foreach ($listvalue as $lottery_key => $lottery_value) {
				if($lottery_key=="AppName"){
					if(empty($lottery_value)==false){
						$isUsed = "是";
					}else{
						$isUsed = "否";
					}						
				}
			}	
			$listvalue = array_merge($listvalue);		
		}
		$this->renderData["lottery_list"]=$data;
		$this->render("query",$this->renderData);				
	}

	public function actionQueryNewFocus(){
		$this->renderData['page']				= "";
		$this->renderData['page_no'] 			= "";

		$this->renderData["focus_list"]	=array();
			
		$fAppData=new FAppData();
		$currentLotteryInfo = $fAppData->getSystemConfigItem("wx.lottery",array(),true);
		$eventId = $currentLotteryInfo['eventid'];
		$pageId = $currentLotteryInfo['pageid'];

		$row_count=$fAppData->getFocusInfoCount($eventId);
		if($row_count>0){
			$page_no  = Yii::app()->request->getParam('page_no',1);
			$page_size=50;
			
			$page_no=$page_no<1?1:$page_no;
				
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
				
			$PageShowUrl="/qAppGift/index?search=1";
			$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
			$this->renderData["page"]=$page_str;


			$data = $fAppData->getFocusInfo($eventId,$pageId,$start, $page_size);
			$this->renderData["focus_list"]=$data;
		}
		$this->render("querynewfocus",$this->renderData);				
	}
}

