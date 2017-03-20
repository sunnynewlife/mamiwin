<?php
LunaLoader::import("luna_lib.util.LunaWebUtil");
LunaLoader::import("luna_lib.util.LunaMemcache");
LunaLoader::import("luna_lib.http.HttpInterface");
LunaLoader::import("luna_lib.util.LunaUtil");
LunaLoader::import("luna_lib.log.LunaLogger");


class SiteController extends CController 
{
	const WX_APP_ID			="wx8a417e8a8315d161";
	const WX_SECRET			="c458469a3a7ba712d2d8408dc488a45a";
	
	public  $layout	="main_h5";
    
	public function init()
	{
	}
	//资料文件下载
	public function actionDownload()
	{
		$download_id=Yii::app()->request->getParam('id',"");
		if(empty($download_id)==false){
			$bizAppData= new BizAppData();
			$Material_Info=$bizAppData->getMaterialInfoByDownloadId($download_id);
			if(count($Material_Info)>0){
				$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
				$file_path=$appConfig["Material_Root"]."/".$download_id;
				$File_Content;
				if(file_exists($file_path)==false){
					$Material_Info=$bizAppData->getMaterialInfoByDownloadId($download_id,true);
					if(count($Material_Info)>0){
						if($material_file=fopen($file_path,"wb")){
							$File_Content=$Material_Info[0]["File_Content"];
							fwrite($material_file,$File_Content);
							fclose($material_file);
						}						
					}
				}else{
					if($material_file=fopen($file_path,"rb")){
						$File_Content=fread($material_file,$Material_Info[0]["File_Size"]);
						fclose($material_file);
					}
				}
				if(empty($File_Content)==false){
					Header("Content-type:".$Material_Info[0]["Mime_Type"]);
					Header("Accept-Ranges: bytes");
					Header("Accept-Length: ".$Material_Info[0]["File_Size"]);
					Header("Content-Disposition: attachment; filename=" . $Material_Info[0]["Original_Name"]);
					echo $File_Content;
					return ;					
				}				
			}
		}
		echo "I'm sorry,cannot find the $download_id resource.";
	}
	
	
	public function actionRegist()
	{
		$this->render("regist");
	}
	public function actionLogin()
	{
		
	}
	public function  actionIndex()
	{
		$this->render("index");
	}
	
	public function actionSendCode()
	{
		
	}
	
	public function actionValidCode()
	{
		$data=array(
			"return_code"			=>	0,
			"return_message"		=>	"注册成功",
			"data"					=>	array(
					"next_url"		=>	"/site/bizIndex",
				),				
		);
		
		$phone=Yii::app()->request->getParam('mobileNo',"");
		$pwd=Yii::app()->request->getParam('newPwd',"");
		if(empty($phone)==false && empty($pwd)==false){
			$bizAppData= new BizAppData();
			$bizAppData->registUserInfo($phone, BizDataDictionary::User_AcctSource_SelfSite, $pwd);
		}
		
		
		echo json_encode($data);
		
	}
}