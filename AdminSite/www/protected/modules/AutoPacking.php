<?php

class AutoPacking
{
	private $_AppData;
	private $_AppConfig;
	private $_AppDepend;
	
	private $_NUM_OPEN_MIN=2;
	private $_NUM_TEST_MIN=2;
	
	private $_START_TIME;
	private $_LOG_NAME="Auto-Packing Check Task";
	private $_AppVersionTaskMsg="";
	
	private $_VERIFY_LOG="";
	
	private function logInfo($logMsg,$verifing=true)
	{
		if($verifing){
			$this->_VERIFY_LOG.= $logMsg.PHP_EOL;
		}else{
			LunaLogger::getInstance()->info($logMsg);
		}
	}
	
	private function packingNow($verifing=true)
	{
		date_default_timezone_set("Asia/Shanghai");
		$_SERVER['REMOTE_ADDR']="127.0.0.1";
		$this->_START_TIME=date_create();
		$this->logInfo(sprintf("%s running at %s.",$this->_LOG_NAME,date_format($this->_START_TIME, "y-m-d H:i:s")),$verifing);
		$this->_AppConfig=LunaConfigMagt::getInstance()->getAppConfig();
		$this->_AppData=new FAppData();
		$this->_AppDepend=new FAppDepend();
		 
		$packageList=$this->_AppData->getPackagesNum();
		if(count($packageList)>0){
			$OpenMin=$this->_AppData->getSystemConfigItem("package.prebuilding.min",$this->_NUM_OPEN_MIN);
			$TestMin=$this->_AppData->getSystemConfigItem("package.testing.min",$this->_NUM_TEST_MIN);
			$this->logInfo(sprintf("Default Publish stock min value:%s, testing stock min value:%s",$OpenMin,$TestMin),$verifing);
			$this->logInfo(sprintf("Found version list count:%s.",count($packageList)),$verifing);
			$AppList=$this->_AppData->getApp();
			foreach ($packageList as $row){
				if($row["AppStatus"]==0 || $row["AppStatus"]==1){  //测试或者上架游戏
					if($row["IsPublishVersion"]==1 || ($row["IsPublishVersion"]==0 && $row["State"]==0 )){ //推广包，或者非推广的测试包
						$PacakgeNum=0;
						if(isset($row["PackageNum"]) && empty($row["PackageNum"])==false){
							$PacakgeNum=$row["PackageNum"];
						}
						$theAppPackingMinSize=$OpenMin;
						foreach ($AppList as $itemRow){
							if($itemRow["AppId"]== $row["AppId"]){
								if(isset($itemRow["MinPackingPoolSize"]) && empty($itemRow["MinPackingPoolSize"])==false && $itemRow["MinPackingPoolSize"]>0){
									$theAppPackingMinSize=$itemRow["MinPackingPoolSize"];
								}
								break;
							}
						}
						$this->logInfo(sprintf("Version id:%s MinPackingPooSize=%s Current Stock Num=%s ",$row["AppVersionId"],$theAppPackingMinSize,$PacakgeNum),$verifing);
						$needNum=($row["State"]==0?$TestMin:$theAppPackingMinSize)-$PacakgeNum;
						if($needNum>0){
							$succ=false;
							if($verifing==false){
								$succ=$this->sendCreatePromoterTask($row["AppId"],$row["AppVersionId"],$needNum);
							}
							$logMsg=sprintf("Send PromoterTask AppId=%s AppVersionId=%s  PackNum=%s Result=%s",
									$row["AppId"],$row["AppVersionId"],$needNum,($succ==true?"success":$this->_AppVersionTaskMsg));
							$this->logInfo($logMsg,$verifing);
						}
					}
				}
			}
		}else{
			$this->logInfo("Not found version list.",$verifing);
		}
		$logMsg=sprintf("%s End at %s.",$this->_LOG_NAME,date("y-m-d H:i:s"));
		$this->logInfo($logMsg,$verifing);
		 
	}
	
	public function verify()
	{
		$this->_VERIFY_LOG="";
		$this->packingNow(true);
		return $this->_VERIFY_LOG;
	}
	
	public function run()
	{
		$this->packingNow(false);
	}
	
	private function sendCreatePromoterTask($appId,$appVersionId,$packageNum)
	{
		if(function_exists("sem_get")){
			$SemaphoreKey="20141111";
			$MaxProcessCount=1;
			$Permissions = 0666;
			$AutoRelease =1;
			 
			$semaphore = sem_get($SemaphoreKey, $MaxProcessCount, $Permissions, $AutoRelease);
			if(!$semaphore){
				LunaLogger::getInstance()->info("Semaphore Get Failure.");
				return false;
			}
			 
			if(sem_acquire($semaphore)==FALSE){
				LunaLogger::getInstance()->info("Semaphore Get Failure.");
				return false;
			}
			$sendSucc=$this->sendCreatePromoterTaskInner($appId, $appVersionId, $packageNum);
			sem_release($semaphore);
			return $sendSucc;
		}else {
			return $this->sendCreatePromoterTaskInner($appId, $appVersionId, $packageNum);
		}
	}
	
	private function sendCreatePromoterTaskInner($appId,$appVersionId,$packageNum)
	{
		$sendSucc=false;
		$startId=$this->_AppData->logPackingRequest($appVersionId, $packageNum);
		if($startId){
	
			if($this->_AppDepend->BuildPromoterPackage($appId, $appVersionId, $startId, $packageNum,$this->_AppConfig["FApp_game_packing_channel_id"],$this->_AppConfig["FApp_game_packing_callback"])){
				$sendSucc=true;
			}else{
				$this->_AppVersionTaskMsg  =$this->_AppDepend->_LastErrorMessage;
			}
		}else{
			$this->_AppVersionTaskMsg="访问预打包操作记录表错误";
		}
		return $sendSucc;
	}
	
}