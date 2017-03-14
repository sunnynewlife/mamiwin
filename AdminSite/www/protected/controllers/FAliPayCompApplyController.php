<?php
require_once(dirname(__FILE__) . '/../config/ConfAlipay.php');

class FAliPayCompApplyController extends TableMagtController {
	private $_tableName = "CompAlipayApply";
	private $_searchName = "State";
	private $_orderName = "CreateDt desc";
	private $_next_url = "/fAliPayCompApply/index";
	private $_columns = array('ApplyId', 'OutAccount', 'InAccount', 'Amount', 'BatchNo', 'CreateDt', 'State', 'ReplyDt', 'ResultMemo');
	private $_title = "分红管理后台-支付宝分账";
	private $_primaryKey = "ApplyId";
	
	
	public function init() {
		$this->_PDO_NODE_NAME = "FHDatabase";
		$this->_MEMCACHE_NODE_NAME = "";
		$this->_memcacheKey = array("");
		
	}

	public function actionList() {
		header("location:/fAliPayCompApply/index?State=4");
		exit;
	}
	
	public function actionIndex() {

		$state =  trim(Yii::app()->request->getParam('State',""));
		if (true === empty($state) && 3 != $state){
			$state = 3;
		}

		$state = intval($state);

		$this->renderData['state'] = $state;

		if($state == 3){
			$order = " CreateDt desc ";
		}else if($state == 4 || $state == 5){
			$order = " ReplyDt desc ";
		}

		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index",$order);
	}
    
	public function actionTestComTrans(){
	   $queryDate =  trim(Yii::app()->request->getParam('d',""));
       if ($queryDate == ""){
			$queryDate = 1;
        }
        $transInfo = ModuleTrans::comCompTrans($queryDate,0);
        $this->renderData["transInfo"]			= $transInfo;
        $this->renderData["queryDate"]			= $queryDate;
		$this->render("testComTrans",$this->renderData);
        
	}
}