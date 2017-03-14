<?php

class QAppEvn1Controller extends TableMagtController 
{
	private $_title="分红管理后台-登录游戏送现金查询";
	private $_next_url="/qAppEvn1/index";
	
	private $_tableName="Q_Evn_1";
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
		$this->renderData["Evn1_list"]			=array();
		$fAppData=new FAppData();
		$row_count=$fAppData->getQ_AppEvn1PlayerListCount();
		if($row_count>0){
			$page_no  = Yii::app()->request->getParam('page_no',1);
			$page_size=50;
			
			$page_no=$page_no<1?1:$page_no;
				
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
				
			$PageShowUrl="/qAppEvn1/index?search=1";
			if(empty($AppName)==false){
				$PageShowUrl.="&AppName=".$AppName;
			}
			if(empty($status)==false){
				$PageShowUrl.="&Status=".$status;
			}
			if(empty($Evn1Idx)==false){
				$PageShowUrl.="&Evn1Idx=".$Evn1Idx;
			}
			if(empty($$IsExpired)==false){
				$PageShowUrl.="&$IsExpired=".$$IsExpired;
			}
			$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
			$data = $fAppData->getQ_AppEvn1PlayerList($start, $page_size);
			$this->renderData["page"]=$page_str;
			$this->renderData["Evn1_list"]=$data;
		}
		$this->render("index",$this->renderData);			
	}
}

