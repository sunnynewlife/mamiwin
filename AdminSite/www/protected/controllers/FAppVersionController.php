<?php

class FAppVersionController extends TableMagtController 
{
	private $_tableName="AppVersion";
	private $_searchName="VersionName";
	private $_next_url="/fAppVersion/index";
	private $_columns=array("VersionName");
	private $_title="分红管理后台-游戏版本管理";
	private $_primaryKey="AppVersionId";
	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
	}
	private function getCDNPackageNum($AppVersionId,$cdnPackagePoolInfo)
	{
		foreach ($cdnPackagePoolInfo as $rowItem){
			if($rowItem["AppVersionId"]==$AppVersionId){
				return $rowItem["PackageCount"];
			}
		}
		return 0;
	}
	public function actionIndex()
	{
		$this->renderData["AppId"]="";
		$this->renderData["AppName"]="";
		$this->renderData["AppVersion"]=array();
		
		$appId= trim(Yii::app()->request->getParam('AppId',""));
		if(empty($appId)){
			$this->alert("error","没有游戏AppId");
		}
		$this->renderData["AppId"]=$appId;
		
		$fAppData=new FAppData();
		$appDef=$fAppData->getViewApp($appId);
		if(count($appDef)<=0){
			$this->alert("error","未找到游戏");
		}
		$this->renderData["AppName"]=$appDef["AppName"];
		$this->renderData["AppRegisterState"]=$appDef["RegistState"];
		$versionName= trim(Yii::app()->request->getParam('VersionName',""));
		
		$CDNPackagePoolInfos=$fAppData->getCDNAppVersion();
		$AppVersionList=$fAppData->getAppVersion($appId,$versionName);
		$AllAppVersionList=$AppVersionList;
		if(empty($versionName)==false){
			$AllAppVersionList=$fAppData->getAppVersion($appId,"");
		}
		foreach ($AppVersionList as & $rowItem){
			$rowItem["SubstuiteVersion"]=$rowItem["App_AppVersionId"];
			if(empty($rowItem["App_AppVersionId"])==false){
				foreach ($AllAppVersionList  as $nameItem){
					if($rowItem["App_AppVersionId"]==$nameItem["AppVersionId"]){
						$rowItem["SubstuiteVersion"]=$nameItem["VersionName"];
						break;
					}
				}
			}
			$rowItem["PackageNum"]=sprintf("%s(%s)",$rowItem["PackageNum"],$this->getCDNPackageNum($rowItem["AppVersionId"], $CDNPackagePoolInfos)); 
		}		
		$this->renderData["AppVersion"]=$AppVersionList;
		
		$this->render("index",$this->renderData);
	}
	private function getPreviousVersionIntroPictures($AppId,$fAppData)
	{
		$AppVersions=$fAppData->getAppVersions($AppId);
		foreach ($AppVersions as $item){
			if(isset($item["GamePics"]) && empty($item["GamePics"])==false){
				if($item["State"]=="1"){
					return $item["GamePics"];
				}
			}
		}
		foreach ($AppVersions as $item){
			if(isset($item["GamePics"]) && empty($item["GamePics"])==false){
				return $item["GamePics"];
			}
		}
		return "";		
	}
	public function actionAdd()
	{
		$AppId =trim(Yii::app()->request->getParam('AppId',0));
		if(empty($AppId)){
			$this->exitWithError("参数错误","/fApp/index");
		}
		$fAppData=new FAppData();
		$appDef=$fAppData->getViewApp($AppId);
		if($appDef==false || is_array($appDef)==false || count($appDef)==0){
			$this->exitWithError("参数值错误","/fApp/index");
		}
		$this->renderData[$this->_tableName]=$appDef;
		$this->_next_url.="?AppId=".$AppId;
		$this->renderData["back_url"]=$this->_next_url;
		$this->renderData["AppIntroPicture"]=$this->getPreviousVersionIntroPictures($AppId, $fAppData);
		
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			
			$this->PackageMd5	= trim(Yii::app()->request->getParam('PackageMd5',""));
			$fileName			= trim(Yii::app()->request->getParam('PackagePath',""));
			
			if(empty($fileName)==false){
				$md5Value			=	$this->getFileMd5($fileName);
				if(empty($md5Value)==false){
					$this->PackageMd5	=$md5Value;
				}
			}
			
			//$fileName=$this->saveGamePackage();
				
			$VersionName	= trim(Yii::app()->request->getParam('VersionName',""));
			$GameSize		= trim(Yii::app()->request->getParam('GameSize',""));
			$GamePics		= trim(Yii::app()->request->getParam('GamePics',""));
			$State			= trim(Yii::app()->request->getParam('State',"0"));
			$TestPhone		= trim(Yii::app()->request->getParam('TestPhone',"0"));
			$IsPublishVersion =trim(Yii::app()->request->getParam('IsPublishVersion',"0"));
				
				
			$rowCount=$fAppData->insertAppVersionWithPackage($VersionName, $GameSize, $GamePics, $State, $TestPhone, $fileName,$IsPublishVersion,$this->PackageMd5,$AppId);
			if($rowCount>0){
				$this->exitWithSuccess(sprintf("增加%s成功",$this->_title),$this->_next_url);
			}else{
				$this->alert('error',sprintf("增加%s失败",$this->_title));
			}				
		}
		$this->render("add",$this->renderData);
	}
	public function actionModify()
	{
		$appVersionId = Yii::app()->request->getParam($this->_primaryKey,'');
		if(empty($appVersionId)){
			$this->exitWithError("参数错误","/fApp/index");
		}
		$appVersion=$this->getOneRowByFieldName($this->_tableName, $this->_primaryKey, $appVersionId);
		if($appVersion==false || is_array($appVersion)==false || count($appVersion)==0){
			$this->exitWithError("参数值错误","/fApp/index");
		}
		$fAppData=new FAppData();
		$appDef=$fAppData->getViewApp($appVersion["AppId"]);
		if(count($appDef)>0){
			$appVersion["AppName"]=$appDef["AppName"];
		}
		
		switch ($appVersion["PackageState"])
		{
			case 0:
				$appVersion["PackageStateName"]="未上传";
				break;
			case 1:
				$appVersion["PackageStateName"]="已上传";
				break;
			case 2:
				$appVersion["PackageStateName"]="已通知打包服务器";
				break;
			case 3:
				$appVersion["PackageStateName"]="打包服务器已获取";
				break;
			default:
				$appVersion["PackageStateName"]="";
				break;
		}
		$this->renderData[$this->_tableName]=$appVersion;
		$this->_next_url.="?AppId=".$appVersion["AppId"];
		$this->renderData["back_url"]=$this->_next_url;
		$this->renderData["OtherVersions"]=$fAppData->getOtherVersions($appVersion["AppId"],$appVersionId);
		
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			
			$this->PackageMd5	= trim(Yii::app()->request->getParam('PackageMd5',""));
			$fileName			= trim(Yii::app()->request->getParam('PackagePath',""));
			
			if(empty($fileName)==false){
				$md5Value			=	$this->getFileMd5($fileName);
				if(empty($md5Value)==false){
					$this->PackageMd5	=$md5Value;
				}				
			}
						
			//$fileName=$this->saveGamePackage();
			
			$VersionName	= trim(Yii::app()->request->getParam('VersionName',""));
			$GameSize		= trim(Yii::app()->request->getParam('GameSize',""));
			$GamePics		= trim(Yii::app()->request->getParam('GamePics',""));
			$State			= trim(Yii::app()->request->getParam('State',"0"));
			$TestPhone		= trim(Yii::app()->request->getParam('TestPhone',"0"));
			$IsPublishVersion =trim(Yii::app()->request->getParam('IsPublishVersion',"0"));
			$App_AppVersionId =trim(Yii::app()->request->getParam('App_AppVersionId',"NO"));
			
			
			$rowCount=$fAppData->updateAppVersionWithPackage($VersionName, $GameSize, $GamePics, $State, $TestPhone, $appVersionId,$fileName,$IsPublishVersion,$App_AppVersionId,$this->PackageMd5);
			if($rowCount>0){
				$this->exitWithSuccess(sprintf("修改%s成功",$this->_title),$this->_next_url);
			}else{
				$this->alert('error',sprintf("修改%s失败",$this->_title));
			}
		}						
		$this->render("modify",$this->renderData);
	}
	
	private $PackageMd5="";
	private function getFileMd5($fileName)
	{
		$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
		$picDir=$appConfig["FApp_game_package_path"];
		$filePath=$picDir.DIRECTORY_SEPARATOR.$fileName;
		if(file_exists($filePath)){
			return md5_file($filePath);
		}
		return "";	
	}
	private function checkExtensionName($extName)
	{
		$allowedExtensionName=array("jpg","JPG","png","PNG","gif","GIF","apk","APK");
		return in_array($extName, $allowedExtensionName);
	}
	
	private function saveGamePackage()
	{
		if(isset($_FILES["GamePackageSrc"])){
			$files=$_FILES["GamePackageSrc"];
			if($files["name"]!=""){
				$name=$files["name"];
				LunaLogger::getInstance()->info("Game Pacakge Name:".$name);
				$str_name=pathinfo($name);
				$extname=strtolower($str_name["extension"]);
				if($this->checkExtensionName($extname)==false){
					return "";
				}
				$filename=date("YndHis").rand(1000,9999).".".$extname;
				$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
				$picDir=$appConfig["FApp_game_package_path"];
				LunaLogger::getInstance()->info("Changed File Name:".$filename);
				if(!file_exists($picDir)){
					mkdir($picDir);
				}
				if(move_uploaded_file($files["tmp_name"],$picDir.DIRECTORY_SEPARATOR.$filename)){
					$this->PackageMd5=md5_file($picDir.DIRECTORY_SEPARATOR.$filename);
					return  $filename;
				}
			}
		}
		return "";		
	}
	
	public function actionDelPackage()
	{
		$appVersionId = Yii::app()->request->getParam($this->_primaryKey,'');
		if(empty($appVersionId)){
			$this->exitWithError("参数错误1","/fApp/index");
		}
		$appVersion=$this->getOneRowByFieldName($this->_tableName, $this->_primaryKey, $appVersionId);
		if($appVersion==false || is_array($appVersion)==false || count($appVersion)==0){
			$this->exitWithError("参数值错误","/fApp/index");
		}
		$appVersion["AppName"]="";
		$appVersion["PackageNum"]="0";
		$appVersion["MinPackingPoolSize"]="0";

		$fAppData=new FAppData();
		$appPackingInfo=$fAppData->getAppVersionPackageNums($appVersion["AppVersionId"]);
		if(count($appPackingInfo)>0){
			$appVersion["AppName"]				=$appPackingInfo["AppName"];
			$appVersion["PackageNum"]			=$appPackingInfo["PackageNum"];
			$appVersion["MinPackingPoolSize"]	=$appPackingInfo["MinPackingPoolSize"];
		}
		if($appVersion["State"]==0){
			$appVersion["MinPackingPoolSize"]=$fAppData->getSystemConfigItem("package.testing.min");
		}else{
			if(isset($appVersion["MinPackingPoolSize"])==false || empty($appVersion["MinPackingPoolSize"])){
				$appVersion["MinPackingPoolSize"]=$fAppData->getSystemConfigItem("package.prebuilding.min");
			}
		}
		
		$this->renderData[$this->_tableName]=$appVersion;
		$this->_next_url.="?AppId=".$appVersion["AppId"];
		$this->renderData["back_url"]=$this->_next_url;
		
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$Num= trim(Yii::app()->request->getParam('DeleteNum',0));
			if($Num>0){
				$AppConf=LunaConfigMagt::getInstance()->getAppConfig();
				$PromotionNo=$fAppData->getPackageForDeleting($appVersionId, $Num);
				if(count($PromotionNo)>0){
					$fAppDepend=new FAppDepend();
					$fAppDepend->DeletePromoterTask($appVersion["AppId"], $appVersionId, $AppConf["FApp_game_packing_channel_id"], $PromotionNo);
					$this->exitWithSuccess("游戏版本删除库存包成功。",$this->_next_url);
				}
			}
		}
		$this->render("packageDelete",$this->renderData);
	}
	
	public function actionPackageCreate()
	{
		$appVersionId = Yii::app()->request->getParam($this->_primaryKey,'');
		if(empty($appVersionId)){
			$this->exitWithError("参数错误1","/fApp/index");
		}
		$appVersion=$this->getOneRowByFieldName($this->_tableName, $this->_primaryKey, $appVersionId);
		if($appVersion==false || is_array($appVersion)==false || count($appVersion)==0){
			$this->exitWithError("参数值错误","/fApp/index");
		}
		$fAppData=new FAppData();
		$appDef=$fAppData->getViewApp($appVersion["AppId"]);
		if(count($appDef)>0){
			$appVersion["AppName"]=$appDef["AppName"];
		}
		$this->renderData[$this->_tableName]=$appVersion;
		$this->_next_url.="?AppId=".$appVersion["AppId"];
		$this->renderData["back_url"]=$this->_next_url;
		
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$AppConf=LunaConfigMagt::getInstance()->getAppConfig();
			$sourceUrl=$AppConf["FApp_game_package_src_download"].$appVersion["PackagePath"];
			$fAppDepend=new FAppDepend();
			if($fAppDepend->RegisterGameVersion($appVersion["AppId"], $appVersionId, $sourceUrl, $appVersion["PackageMd5"], $AppConf["FApp_game_packing_channel_id"])){
				$fAppData->updateAppVersionPackageState($appVersionId, 2);
				$this->exitWithSuccess("游戏版本发布到打包平台成功。",$this->_next_url);
			}else{
				$this->alert('error',sprintf("游戏版本发布到打包平台失败: %s",$fAppDepend->_LastErrorMessage));
			}
		}
		$this->render("packageCreate",$this->renderData);
	}
	
	public function actionPromoterCreate()
	{
		$appVersionId = Yii::app()->request->getParam($this->_primaryKey,'');
		if(empty($appVersionId)){
			$this->exitWithError("参数错误1","/fApp/index");
		}
		$appVersion=$this->getOneRowByFieldName($this->_tableName, $this->_primaryKey, $appVersionId);
		if($appVersion==false || is_array($appVersion)==false || count($appVersion)==0){
			$this->exitWithError("参数值错误","/fApp/index");
		}
		$fAppData=new FAppData();
		$appDef=$fAppData->getViewApp($appVersion["AppId"]);
		if(count($appDef)>0){
			$appVersion["AppName"]=$appDef["AppName"];
		}
		$this->renderData[$this->_tableName]=$appVersion;
		$this->_next_url.="?AppId=".$appVersion["AppId"];
		$this->renderData["back_url"]=$this->_next_url;
		
		$this->renderData["PackageNum"]=$fAppData->getTestingPackNum();
		
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$PackageNum=trim(Yii::app()->request->getParam('PackageNum',0));
			if($PackageNum<=0){
				$this->alert('error',"打包数量必须大于 0");
			}else{
				$isPublisCDN=Yii::app()->request->getParam("PublishCDN",'0');
				if($this->sendCreatePromoterTask($appVersion["AppId"],$appVersionId, $PackageNum,$isPublisCDN)){
					$this->exitWithSuccess("版本预打包请求成功，请稍后查看完成进度。",$this->_next_url);
				} else {
					$this->alert('error',"预打包失败:".$this->_CreatePromoterTask_Msg);
				}
			}
		}
		$this->renderData["PackingLog"]=$fAppData->getAppPackingLog($appVersionId);
		$this->render("promoterCreate",$this->renderData);
	}
	
	private $_CreatePromoterTask_Msg="";
	
	private function sendCreatePromoterTaskInner($appId,$appVersionId,$packageNum,$isPublisCDN)
	{
		$sendSucc=false;
		
		$fAppData=new FAppData();
		$startId=$fAppData->logPackingRequest($appVersionId, $packageNum);
		
		if($startId){
			$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
			$appendCallbackUrl=$appConfig["FApp_game_packing_callback"];
			if($isPublisCDN=="1"){
				$appendCallbackUrl=$appConfig["FApp_game_packing_callbackCdn"];
			}			
			$fAppDepend=new FAppDepend();
			if($fAppDepend->BuildPromoterPackage($appId, $appVersionId, $startId, $packageNum,$appConfig["FApp_game_packing_channel_id"],$appendCallbackUrl,$isPublisCDN)){
				$sendSucc=true;
			}else{
				$this->_CreatePromoterTask_Msg=$fAppDepend->_LastErrorMessage;
			}
		}else{
			$this->_CreatePromoterTask_Msg="访问预打包操作记录表错误";
		}
		return $sendSucc;
	}
	
	private function sendCreatePromoterTask($appId,$appVersionId,$packageNum,$isPublisCDN)
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
			$sendSucc=$this->sendCreatePromoterTaskInner($appId, $appVersionId, $packageNum,$isPublisCDN);
			sem_release($semaphore);
			return $sendSucc;				
		}else {
			return $this->sendCreatePromoterTaskInner($appId, $appVersionId, $packageNum,$isPublisCDN);
		}
	}	
}
?>