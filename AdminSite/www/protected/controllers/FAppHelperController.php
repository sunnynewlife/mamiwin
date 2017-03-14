<?php

class FAppHelperController extends TableMagtController 
{
	private $_title="分红管理后台-辅助更新推广包地址";
	private $_next_url="/fAppHelper/index";
	
	private $_tableName="";
	private $_searchName="";
	private $_columns=array();
	private $_primaryKey="";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
		$this->_SERACH_FIELD_COMPARE_TYPE=array();
	}
	public function actionIndex()
	{
		$this->render("index");
	}
	public function actionCheckPackingTask()
	{
		$packingTask=new AutoPacking();
		$data=array();
		$data["packingLog"]=$packingTask->verify();
		$this->render("verifyPacking",$data);
	}
	
	public function actionUpdatePromoterTask()
	{
		$lvAppId=trim(Yii::app()->request->getParam("AppId",""));
		$lvAppVersionId=trim(Yii::app()->request->getParam("AppVersionId",""));
		if(empty($lvAppId) || empty($lvAppVersionId)) {
			echo json_encode(array("return_code" =>-1,"return_msg" =>"参数 错误"));
			return;
		}
		$lvTestUrl=trim(Yii::app()->request->getParam("TestUrl",""));
		$AppConf=LunaConfigMagt::getInstance()->getAppConfig();
		$fAppDepend=new FAppDepend();
		$prompterTask=$fAppDepend->QueryPromoterTask($lvAppId, $lvAppVersionId, $AppConf["FApp_game_packing_channel_id"]);
		if($prompterTask && is_array($prompterTask) && isset($prompterTask["data"])){
			$fAppData=new FAppData();
			foreach ($prompterTask["data"] as $itemRow){
				$PromotNo=$itemRow["promoter"];
				$NewUrl=$itemRow["url"];
				if(stristr($NewUrl,".apk")){
					$iPos=strrpos($NewUrl,"/");
					$OldUrl=substr($NewUrl,0,$iPos);
					//if(empty($lvTestUrl)==false  && $lvTestUrl==$OldUrl){
						$fAppData->updatePromoterTask($PromotNo, $OldUrl, $NewUrl);
					//}	
				}
			}
			echo json_encode(array("return_code" =>0,"return_msg" =>"更新 完成 "));
			
		}else{
			echo json_encode(array("return_code" =>-2,"return_msg" =>print_r($prompterTask,true)));
			return;
		}
	}
}
?>