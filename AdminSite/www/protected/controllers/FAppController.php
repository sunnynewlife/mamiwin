<?php

class FAppController extends TableMagtController 
{
	private $_title="分红管理后台-游戏信息";
	private $_next_url="/fApp/index";
	
	private $_tableName="ViewApp";
	private $_searchName="AppName";
	private $_columns=array();
	private $_primaryKey="AppId";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
		$this->_SERACH_FIELD_COMPARE_TYPE=array("AppName" =>"like");
		
	}
	
	private function getAppTypeName($type,$typeDef)
	{
		foreach ($typeDef as $item){
			if($item["code"]==$type){
				return $item["name"];
			}
		}
		return $type;
	}
	private function getGameTagLables()
	{
		$fAppData=new FAppData();
		$labels=$fAppData->getSystemConfigItem("game.tag.lables");
		if(isset($labels)){
			return explode(",", $labels);
		}
		return array();
	}
	private function getGameDataIndex()
	{
		return array(
			"5"	=>"5",
			"4"	=>"4",
			"3"	=>"3",
			"2"	=>"2",
			"1"	=>"1",
		);	
	}
	protected function getPageRowsExtentData($data)
	{
		$fAppData=new FAppData();
		$gameTypeDef=$fAppData->getGameTypeDefinition();
		if(isset($data)  && is_array($data) ){
			$dataCount=count($data);
			for($index=0;$index<$dataCount;$index++){
				$data[$index]["AppType_Name"]=$this->getAppTypeName($data[$index]["AppType"], $gameTypeDef);
				$prorateHistory=$fAppData->getCurrentProrate($data[$index]["AppId"]);
				$prorateValue="";
				if(count($prorateHistory)>0){
					$prorateValue=$prorateHistory["PromoterProrate"];
				}
				$data[$index]["AppPromoterProrate"]=$prorateValue;
			}			
		}
		return $data;
	}
	private function getGameData($startDt,$endDt)
	{
		$appId=trim(Yii::app()->request->getParam('AppId',""));
		if(empty($appId)==false){
			$fAppData=new FAppData();
			$viewApp=$fAppData->getViewApp($appId);
			if(count($viewApp)>0){
				$gameData["AppName"]	=$viewApp["AppName"];
				$gameData["Deposit"]	=$viewApp["Deposit"];
				$gameData["AppPackageCounts"]	=$fAppData->getAppPackageCount($appId);
				$gameData["AppDepositPlayCount"] =$fAppData->getAppPlayDistinctCount($appId);

				$prorateHistory=$fAppData->getProrateHistoryByAppId($appId);
				
				$condStartDt=date_create($startDt." 00:00:00");
				$condEndDt=date_create($endDt." 23:59:59");
				$currentDt=date_create();
				
				$AppProfit=array();
				foreach ($prorateHistory as $itemRow){
					$prorateStart=date_create($itemRow["StartDt"]);
					$prorateEnd=date_create($itemRow["EndDt"]);
					if($condStartDt > $prorateEnd ||  $condEndDt< $prorateStart){
						continue;
					}	
					$maxStartDt=max($prorateStart,$condStartDt);
					$minEndDt=min($prorateEnd,$condEndDt);
					
					$properRow=array("startDt" => $maxStartDt,"endDt"=>$minEndDt,"PromoterProrate" => $itemRow["PromoterProrate"]);
					$properRow["status"]= ($currentDt>=$maxStartDt && $currentDt<=$minEndDt)?"线上":"过期";
					$properRow["profit"] =$fAppData->getProfit(date_format($maxStartDt,"Y-m-d H:i:s"),date_format($minEndDt,"Y-m-d H:i:s") , $appId, $itemRow["PromoterProrate"]);
					$AppProfit[]=$properRow;
				}
				$gameData["AppProfitDetail"]=$AppProfit;
				return $gameData;
			}
		}
		return array("AppName" => "","Deposit"=>0,"AppPackageCounts" => 0, "AppDepositPlayCount"=> 0);
	}
	
	public function actionIndex()
	{
		$fAppData=new FAppData();
		$this->renderData["GlobalPackSize"]=$fAppData->getSystemConfigItem("package.prebuilding.min","10");
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index"," SortIndex asc,CreateDt desc");
	}
	
	public function actionShowGameData()
	{
		$startDt=trim(Yii::app()->request->getParam('startDt',"2000-01-01"));
		$endDt=trim(Yii::app()->request->getParam('endDt',"2050-12-31"));
		if(empty($startDt)){
			$startDt="2000-01-01";
		}
		if(empty($endDt)){
			$endDt="2050-12-31";
		}
		$this->renderData["GameSummary"]=$this->getGameData($startDt,$endDt);
		$this->render("showGameData",$this->renderData);
	}
	
	public function actionModify()
	{
		$fAppData=new FAppData();
		$this->renderData["game_type"]=$fAppData->getGameTypeDefinition();
		$this->renderData["game_tag"]=$this->getGameTagLables();
		$this->renderData["game_data_index"]=$this->getGameDataIndex();
		
		$AppId = Yii::app()->request->getParam($this->_primaryKey,'');
		if(empty($AppId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$evnData=$this->getOneRowByFieldName("App", $this->_primaryKey, $AppId);
		if($evnData==false || is_array($evnData)==false || count($evnData)==0){
			$this->exitWithError("参数值错误",$this->_next_url);
		}
		$this->renderData["App"]=$evnData;
		$appVersion=$fAppData->getLatestAppVersion($AppId);
		if(count($appVersion)==0){
			$appVersion["AppVersionId"]="";
			$appVersion["AppId"]=$AppId;
			$appVersion["VersionName"]="";
			$appVersion["GamePics"]="";
			$appVersion["GameSize"]="";
		}
		$this->renderData["AppVersion"]=$appVersion;
		
		$prorateHistory=array();
		if(empty($appVersion["AppVersionId"])==false){
			$prorateHistory=$fAppData->getProrateHistory($AppId);
		}
		$this->renderData["ProrateHistory"]=$prorateHistory;
	
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if ($submit){
			$appName		=Yii::app()->request->getParam("AppName","");
			$appId			=Yii::app()->request->getParam("AppId","");
			$appLogo		=Yii::app()->request->getParam("FileId",0);
			$appType		=Yii::app()->request->getParam("AppType","");
			$developer		=Yii::app()->request->getParam("Developer","");
			$publisher		=Yii::app()->request->getParam("Publisher","");
			$appIntro		=Yii::app()->request->getParam("AppIntroduct","");
			$appDetail		=Yii::app()->request->getParam("AppDetail","");
			$appStatus		=Yii::app()->request->getParam("AppStatus",0);
			$testPhone		=Yii::app()->request->getParam("TestPhone","");
			$promptTitle	=Yii::app()->request->getParam("PromptTitle","");
			$weixinTitle	=Yii::app()->request->getParam("WeixinTitle","");
			$weixinPic		=Yii::app()->request->getParam("WeixinPic","");
			$weixinContent	=Yii::app()->request->getParam("WeixinContent","");
			$weiBoContent	=Yii::app()->request->getParam("WeiBoContent","");
			$weiBoPic		=Yii::app()->request->getParam("WeiBoPic","");
			$phoneMsg		=Yii::app()->request->getParam("PhoneMsg","");
			$profitUrl		=Yii::app()->request->getParam("ProfitUrl","");
			$developerProrate=Yii::app()->request->getParam("DeveloperProrate","0");
			$prefixName		=Yii::app()->request->getParam("PackagePrefixName","");
			$appNameEn		=Yii::app()->request->getParam("AppNameEn","");
			
			$costFeePercent	=Yii::app()->request->getParam("CostFeePercent","");
			$sortIndex		=Yii::app()->request->getParam("SortIndex","");
			$minPackingPoolSize	=Yii::app()->request->getParam("MinPackingPoolSize","");
			
			$EventUrl		=Yii::app()->request->getParam("EventUrl","");
			
			$RecommendIndex	=Yii::app()->request->getParam("RecommendIndex","");
			$PopularIndex	=Yii::app()->request->getParam("PopularIndex","");
			$RemainIndex	=Yii::app()->request->getParam("RemainIndex","");
			$PayIndex		=Yii::app()->request->getParam("PayIndex","");
			$BeginnerLevel	=Yii::app()->request->getParam("BeginnerLevel","");
			
			$LabelTag1	=Yii::app()->request->getParam("LabelTag1","");
			$LabelTag2	=Yii::app()->request->getParam("LabelTag2","");
			$LabelTag3	=Yii::app()->request->getParam("LabelTag3","");
			$LabelTag4	=Yii::app()->request->getParam("LabelTag4","");
			$LabelTag5	=Yii::app()->request->getParam("LabelTag5","");
	
			
			$fAppData->updateApp($appId, $appLogo, $appName, $appType, $developer, $publisher, $appIntro, $appDetail, $appStatus, $testPhone, $promptTitle, $weixinTitle, $weixinPic, $weixinContent, $weiBoContent, $weiBoPic, $phoneMsg,$profitUrl,bcdiv($developerProrate,"100",2),$prefixName,$appNameEn,$costFeePercent,$sortIndex,$minPackingPoolSize,$EventUrl,$RecommendIndex,$PopularIndex,$RemainIndex,$PayIndex,$BeginnerLevel,$LabelTag1,$LabelTag2,$LabelTag3,$LabelTag4,$LabelTag5);
			
			$appVersionId	=Yii::app()->request->getParam("AppVersionId","");
			$versionName	=Yii::app()->request->getParam("VersionName","");
			$gamePics		=Yii::app()->request->getParam("GamePics","");
			$gameSize		=Yii::app()->request->getParam("GameSize","");
			if(empty($appVersionId) || $appVersionId<1){
				if($fAppData->insertAppVersion($appId, $versionName, $gamePics, $gameSize,$testPhone)>0){
					$appVersionId=$fAppData->getLastInsertAppVersionIdByAppId($appId);
				}	
			}else {
				$fAppData->updateAppVersion($appId, $versionName, $gamePics, $gameSize, $appVersionId,$testPhone);				
			}

			if(empty($appVersionId)==false){
				$prorateRateChange	=Yii::app()->request->getParam("prorateRateChange","");
				if($prorateRateChange==1){
					$fAppData->updateProrateHistoryStatusAsDelete($appId);
					$this->insertNewProrateRate($appId,$fAppData);
				}
			}
			
			$this->exitWithSuccess(sprintf("修改%s成功",$this->_title),$this->_next_url);
		}
		$this->render("modify",$this->renderData);
	}
	private function insertNewProrateRate($appId,$fAppData)
	{
		$prorateRateValue	=Yii::app()->request->getParam("prorateRateValue","");
		if(empty($prorateRateValue)==false){
			$rows=explode(",",$prorateRateValue);
			foreach ($rows as $item){
				list($startDt,$endDt,$iRate)=explode("#",$item);
				$fAppData->insertProrateHistory($appId,  bcdiv($iRate,"100",2), $startDt." 00:00:00",$endDt." 23:59:59");
			}			
		}
	}
	
	public function actionAdd()
	{
		$fAppData=new FAppData();
		$this->renderData["game_type"]=$fAppData->getGameTypeDefinition();
		$this->renderData["game_tag"]=$this->getGameTagLables();
		$this->renderData["game_data_index"]=$this->getGameDataIndex();
		
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if ($submit){
			$appName		=Yii::app()->request->getParam("AppName","");
			$appId			=Yii::app()->request->getParam("AppId","");
			$appLogo		=Yii::app()->request->getParam("FileId",0);
			$appType		=Yii::app()->request->getParam("AppType","");
			$developer		=Yii::app()->request->getParam("Developer","");
			$publisher		=Yii::app()->request->getParam("Publisher","");
			$appIntro		=Yii::app()->request->getParam("AppIntroduct","");
			$appDetail		=Yii::app()->request->getParam("AppDetail","");
			$appStatus		=Yii::app()->request->getParam("AppStatus",0);
			$testPhone		=Yii::app()->request->getParam("TestPhone","");
			$promptTitle	=Yii::app()->request->getParam("PromptTitle","");
			$weixinTitle	=Yii::app()->request->getParam("WeixinTitle","");
			$weixinPic		=Yii::app()->request->getParam("WeixinPic","");
			$weixinContent	=Yii::app()->request->getParam("WeixinContent","");
			$weiBoContent	=Yii::app()->request->getParam("WeiBoContent","");
			$weiBoPic		=Yii::app()->request->getParam("WeiBoPic","");
			$phoneMsg		=Yii::app()->request->getParam("PhoneMsg","");
			$profitUrl		=Yii::app()->request->getParam("ProfitUrl","");
			$developerProrate=Yii::app()->request->getParam("DeveloperProrate","0");
			$prefixName		=Yii::app()->request->getParam("PackagePrefixName","");
			$appNameEn		=Yii::app()->request->getParam("AppNameEn","");
			
			$costFeePercent	=Yii::app()->request->getParam("CostFeePercent","");
			$sortIndex		=Yii::app()->request->getParam("SortIndex","");
			$minPackingPoolSize	=Yii::app()->request->getParam("MinPackingPoolSize","");
			
			$EventUrl		=Yii::app()->request->getParam("EventUrl","");
				
			$RecommendIndex	=Yii::app()->request->getParam("RecommendIndex","");
			$PopularIndex	=Yii::app()->request->getParam("PopularIndex","");
			$RemainIndex	=Yii::app()->request->getParam("RemainIndex","");
			$PayIndex		=Yii::app()->request->getParam("PayIndex","");
			$BeginnerLevel	=Yii::app()->request->getParam("BeginnerLevel","");
				
			$LabelTag1	=Yii::app()->request->getParam("LabelTag1","");
			$LabelTag2	=Yii::app()->request->getParam("LabelTag2","");
			$LabelTag3	=Yii::app()->request->getParam("LabelTag3","");
			$LabelTag4	=Yii::app()->request->getParam("LabelTag4","");
			$LabelTag5	=Yii::app()->request->getParam("LabelTag5","");
				
			
			$rowCount=$fAppData->insertApp($appId, $appLogo, $appName, $appType, $developer, $publisher, $appIntro, $appDetail, $appStatus, $testPhone, $promptTitle, $weixinTitle, $weixinPic, $weixinContent, $weiBoContent, $weiBoPic, $phoneMsg,$profitUrl,bcdiv($developerProrate,"100",2),$prefixName,$appNameEn,$costFeePercent,$sortIndex,$minPackingPoolSize,$EventUrl,$RecommendIndex,$PopularIndex,$RemainIndex,$PayIndex,$BeginnerLevel,$LabelTag1,$LabelTag2,$LabelTag3,$LabelTag4,$LabelTag5);
			if($rowCount>0){
				$versionName	=Yii::app()->request->getParam("VersionName","");
				$gamePics		=Yii::app()->request->getParam("GamePics","");
				$gameSize		=Yii::app()->request->getParam("GameSize","");
				$rowCount=$fAppData->insertAppVersion($appId, $versionName, $gamePics, $gameSize,$testPhone);

				if($rowCount>0){
					$appVersionId=$fAppData->getLastInsertAppVersionIdByAppId($appId);
					if(empty($appVersionId)==false){
						$this->insertNewProrateRate($appId, $fAppData);
					}
				}
				
				$this->exitWithSuccess(sprintf("增加%s成功",$this->_title),$this->_next_url);
			}else{
				$this->alert('error',sprintf("增加%s失败",$this->_title));
			}
		}
		$this->render("add",$this->renderData);		
	}
	
	public function actionRegister()
	{
		$AppId = Yii::app()->request->getParam($this->_primaryKey,'');
		if(empty($AppId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$fAppData=new FAppData();
		$appInfo=$fAppData->getViewApp($AppId);
		$this->renderData["App"]=$appInfo;
		if(count($appInfo)==0){
			$this->exitWithError("AppId参数错误",$this->_next_url);
		}
		if($appInfo["RegistState"]==1){
			$this->exitWithError("游戏已注册到打包平台了，不能再次注册。",$this->_next_url);				
		}
		if(empty($appInfo["PackagePrefixName"])){
			$this->exitWithError("请先更新、保存好游戏打包时生成包的文件名前缀。",$this->_next_url);
		}		
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$fAppDepend=new FAppDepend();
			if($fAppDepend->RegisterGame($appInfo["AppId"], $appInfo["AppNameEn"], $appInfo["PackagePrefixName"])){
				$fAppData->updateAppRegisterState($AppId, 1);
				$this->exitWithSuccess("游戏注册到打包平台成功。",$this->_next_url);
			}else{
				$this->alert('error',sprintf("游戏注册到打包平台失败: %s",$fAppDepend->_LastErrorMessage));
			}
		}
		$this->render("register",$this->renderData);
	}
	
	public function actionInstallPackageIndex()
	{
		$fAppData=new FAppData();
		$installPackage = $fAppData->getSystemConfigItem("game.install.package.name","",true);
		$Apps=$fAppData->getApp();
		$appNames=array();
		foreach ($Apps as $rowItem){
			$appNames[$rowItem["AppId"]]=$rowItem["AppName"];
		}
		$this->renderData["InstallPacakge"] =$installPackage;
		$this->renderData["AppNames"] =$appNames;
		$this->render("install_package_index",$this->renderData);
	}	
	
	public function actionInstallPackageAdd()
	{
		$this->_next_url="/fApp/installPackageIndex";
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$AppId			=Yii::app()->request->getParam("AppId","");
			$InstallPackageName	=Yii::app()->request->getParam("InstallPackageName","");
			if(empty($AppId)==false && empty($InstallPackageName)==false){
				$fAppData=new FAppData();
				$docInfos=$fAppData->getSystemConfigItem("game.install.package.name","",true);
				$docInfos[$AppId]=$InstallPackageName;
				if($fAppData->updateSystemConfigItem("game.install.package.name", json_encode($docInfos,true))>0){
					$this->exitWithSuccess(sprintf("增加%s成功","游戏安装包名信息"),$this->_next_url);
				}else{
					$this->alert('error',sprintf("增加%s失败","游戏安装包名信息"));
				}
			}
		}
		$this->render("install_package_add");
	}
	
	public function actionInstallPackageDel()
	{
		$this->_next_url="/fApp/installPackageIndex";
		$AppId = Yii::app()->request->getParam("appId",'');
		if(empty($AppId)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$fAppData=new FAppData();
		$docInfos=$fAppData->getSystemConfigItem("game.install.package.name","",true);
		unset($docInfos[$AppId]);
		if($fAppData->updateSystemConfigItem("game.install.package.name", json_encode($docInfos,true))>0){
			$this->exitWithSuccess(sprintf("删除%s成功","游戏安装包名信息"),$this->_next_url);
		}else{
			$this->alert('error',sprintf("删除%s失败","游戏安装包名信息"));
		}
	}
	
	public function actionInstallPackageModify()
	{
		$this->renderData["AppName"]="";
		$this->renderData["InstallPackageName"]="";
	
		$appIdKey = trim(Yii::app()->request->getParam('appId',""));
	
		$this->_next_url="/fApp/installPackageIndex";
		$this->renderData["AppId"]=$appIdKey;
	
		$submit = trim(Yii::app()->request->getParam('submit',0));
	
		$fAppData=new FAppData();
		$docInfos=$fAppData->getSystemConfigItem("game.install.package.name","",true);
	
		if($submit){
			$NewAppId			=Yii::app()->request->getParam("NewAppId","");
			$InstallPackageName	=Yii::app()->request->getParam("InstallPackageName","");
				
			if(empty($NewAppId)==false && empty($InstallPackageName)==false){
				unset($docInfos[$appIdKey]);
				$docInfos[$NewAppId]=$InstallPackageName;
	
				if($fAppData->updateSystemConfigItem("game.install.package.name", json_encode($docInfos,true))>0){
					$this->exitWithSuccess(sprintf("修改%s成功","游戏安装包名信息"),$this->_next_url);
				}else{
					$this->alert('error',sprintf("修改%s失败","游戏安装包名信息"));
				}
			}
		}else{
			if(isset($docInfos[$appIdKey])){
	
				$this->renderData["InstallPackageName"]=$docInfos[$appIdKey];
				$Apps=$fAppData->getApp();
				$appNames=array();
				foreach ($Apps as $rowItem){
					if($rowItem["AppId"]==$appIdKey){
						$this->renderData["AppName"]=$rowItem["AppName"];
						break;
					}
				}
			}
		}
		$this->render("install_package_modify",$this->renderData);
	}
		
}
?>