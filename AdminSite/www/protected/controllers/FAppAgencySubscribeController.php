<?php

LunaLoader::import("luna_lib.Kafka.KafkaQueue");
LunaLoader::import("luna_lib.util.LunaKafka");

class FAppAgencySubscribeController extends TableMagtController 
{
	private $_tableName="PublishCfg";
	private $_searchName="Name";
	private $_next_url="/fAppAgencySubscribe/index";
	private $_columns=array("CfgKey","CfgValue");
	private $_title="分红管理后台-大商户数据订阅配置";
	private $_primaryKey="RecId";
	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
	}

	public function actionIndex()
	{
		$ServerCfg=array(
				"sleep_seconds"	=>	3,
				"try_count"		=>	3,
				"report_period"	=>	300,
		);
		$AgencyCfg=array();
		$fAppData=new FAppData();
		$cfg=$fAppData->QueryPublishCfg("ServerCfg");
		if(isset($cfg) && is_array($cfg)){
			$cfg=@json_decode($cfg["CfgValue"],true);
		}
		if(isset($cfg) && is_array($cfg)){
			if(isset($cfg["sleep_seconds"])){
				$ServerCfg["sleep_seconds"]=$cfg["sleep_seconds"];
			}
			if(isset($cfg["try_count"])){
				$ServerCfg["try_count"]=$cfg["try_count"];
			}
			if(isset($cfg["report_period"])){
				$ServerCfg["report_period"]=$cfg["report_period"];
			}
			if(isset($cfg["subscriber_cfg"])){
				$AgencyCfg=$cfg["subscriber_cfg"];
			}
		}
		$search   = Yii::app()->request->getParam('search','');
		if($search==1){
			$SleepTime	= trim(Yii::app()->request->getParam('SleepTime',''));
			$TryCount	= trim(Yii::app()->request->getParam('TryCount',''));
			$ReportPeriod= trim(Yii::app()->request->getParam('ReportPeriod',''));
			
			$ServerCfg["sleep_seconds"]	=$SleepTime;
			$ServerCfg["try_count"]		=$TryCount;
			$ServerCfg["report_period"]	=$ReportPeriod;
			if(isset($cfg) && isset($cfg["subscriber_cfg"])){
				$ServerCfg["subscriber_cfg"]=$cfg["subscriber_cfg"];
			}else{
				$ServerCfg["subscriber_cfg"]=array();
			}
			$cfgValue=json_encode($ServerCfg,true);
			if($fAppData->UpdatePublishCfg("ServerCfg",$cfgValue )==0){
				$fAppData->AddPublishCfg("ServerCfg", $cfgValue);
			}		
		}
		$this->renderData["ServerStat"]=array();
		$ServerLastReport=$fAppData->GetPublishiServerStatLastReport();
		if(count($ServerLastReport)>0){
			$ServerLastReportInfo=@json_decode($ServerLastReport["ServerState"],true);
			if(is_array($ServerLastReportInfo)){
				$this->renderData["ServerStat"]=$ServerLastReportInfo;
			}
		}
		$this->renderData["ServerCfg"]=$ServerCfg;
		$this->renderData["AgencyCfg"]=$AgencyCfg;
		$this->render("index",$this->renderData);
	}
	
	private function getAgencyNameByLoginName($loginName,$agencys)
	{
		foreach ($agencys as $row){
			if($row["LoginName"]==$loginName){
				return $row["Name"];
			}
		}
		return "";	
	}
	
	public function actionAdd()
	{
		$fAppData=new FAppData();
		$subscribers=$fAppData->getAgencyPromoter("","","","","","a.CreateDt",0,999999);
		$submit   = Yii::app()->request->getParam('submit','');
		$subscribeInfo=array(
			"LoginName" =>"",
			"System"	=>"",
			"Interface" =>"",		
		);
		if($submit==1){
			$loginId= trim(Yii::app()->request->getParam('SubscriberId',''));
			$SysName= trim(Yii::app()->request->getParam('SystemName',''));
			$InterfaceName= trim(Yii::app()->request->getParam('InterfaceName',''));
			
			$ServerCfg=array(
					"sleep_seconds"	=>	3,
					"try_count"		=>	3,
					"report_period"	=>	300,
			);
			$AgencyCfg=array();
			$fAppData=new FAppData();
			$cfg=$fAppData->QueryPublishCfg("ServerCfg");
			if(isset($cfg) && is_array($cfg)){
				$cfg=@json_decode($cfg["CfgValue"],true);
			}
			if(isset($cfg) && is_array($cfg)){
				$ServerCfg=$cfg;
				if(isset($cfg["subscriber_cfg"])){
					$AgencyCfg=$cfg["subscriber_cfg"];
				}
			}
			$AgencyCfg[$loginId]=array("System" => $SysName,"Interface" => $InterfaceName,"name" => $this->getAgencyNameByLoginName($loginId, $subscribers),);
			$ServerCfg["subscriber_cfg"]=$AgencyCfg;
			$cfgValue=json_encode($ServerCfg,true);
			if($fAppData->UpdatePublishCfg("ServerCfg",$cfgValue )==0){
				$fAppData->AddPublishCfg("ServerCfg", $cfgValue);
			}
			$this->exitWithSuccess("修改成功", $this->_next_url);				
		}else{
			$agencyId	=Yii::app()->request->getParam('agencyId','');
			if(empty($agencyId)==false){
				$fAppData=new FAppData();
				$cfg=$fAppData->QueryPublishCfg("ServerCfg");
				if(isset($cfg) && is_array($cfg)){
					$cfg=@json_decode($cfg["CfgValue"],true);
				}
				if(isset($cfg) && is_array($cfg)){
					if(isset($cfg["subscriber_cfg"]) && is_array($cfg["subscriber_cfg"])){
						foreach ($cfg["subscriber_cfg"] as $key => $row){
							if($key==$agencyId){
								$subscribeInfo["LoginName"]	=$key;
								$subscribeInfo["System"]	=$row["System"];
								$subscribeInfo["Interface"]	=$row["Interface"];
								break;
							}
						}
					}
				}
			}
		}
		$this->renderData["subscribeInfo"]=$subscribeInfo;	
		$this->renderData["subscribers"]=$subscribers;
		$this->render("add",$this->renderData);
	}
	
	public function actionDel()
	{
		$agencyId	=Yii::app()->request->getParam('agencyId','');
		if(empty($agencyId)){
			$this->exitWithError("缺少参数", $this->_next_url);
		}
		$fAppData=new FAppData();
		$cfg=$fAppData->QueryPublishCfg("ServerCfg");
		if(isset($cfg) && is_array($cfg)){
			$cfg=@json_decode($cfg["CfgValue"],true);
		}
		if(isset($cfg) && is_array($cfg)){
			if(isset($cfg["subscriber_cfg"]) && is_array($cfg["subscriber_cfg"])){
				if(isset($cfg["subscriber_cfg"][$agencyId])){
					unset($cfg["subscriber_cfg"][$agencyId]);
					$cfgValue=json_encode($cfg,true);
					if($fAppData->UpdatePublishCfg("ServerCfg",$cfgValue )==0){
						$fAppData->AddPublishCfg("ServerCfg", $cfgValue);
					}
				}
			}
		}
		$this->exitWithSuccess("删除成功", $this->_next_url);
	}
	
	public function actionReload()
	{
		//$kafka=new KafkaQueue();
		$kafka=new LunaKafka();
		$ctlMsg=array(
			"type"	=>	"ctl",
			"data"	=>	"loadCfg",		
		);
		//$result=$kafka->sendMessageEx(json_encode($ctlMsg,true));
		$result=$kafka->produceMessage(json_encode($ctlMsg,true));
		if($result==-1){
			$this->exitWithError("发送服务重新加载业务配置命令消息失败！", $this->_next_url);
		}else{
			$this->exitWithSuccess("发送服务重新加载业务配置命令消息成功！", $this->_next_url);
		}
	}
}
?>