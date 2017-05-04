<?php
LunaLoader::import("luna_lib.util.LunaWebUtil");
LunaLoader::import("luna_lib.util.LunaMemcache");
LunaLoader::import("luna_lib.http.HttpInterface");
LunaLoader::import("luna_lib.util.LunaUtil");
LunaLoader::import("luna_lib.log.LunaLogger");


class TestController extends CController 
{
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
	public function  actionIndex()
	{
		$this->render("index");
	}
	
	public function actionRegist()
	{
		$this->layout="test_page_list";
		$this->render("test_page_regist");
	}
	public function actionLogin()
	{
		$this->layout="test_page_list";
		$this->render("test_page_login");
	}
	public function  actionTest()
	{
		$this->layout="test_page_list";
		$this->render("test_page_list");
	}
	public function actionResetpwd()
	{
		$this->layout="test_page_list";
		$this->render("test_page_resetpwd");		
	}
	public function actionWxacct()
	{
		$this->layout="test_page_list";
		$this->render("test_page_wxacct");
	}	
	public function actionUpload()
	{
		$this->layout="test_page_list";
		$this->render("test_page_upload");
	}	
	public function actionWxIndex()
	{
		$data=array(
				"code"		=>	Yii::app()->request->getParam('code',""),
				"state"		=>	Yii::app()->request->getParam('state',"1"),
		);
		$this->layout="test_page_list";
		$this->render("test_page_wxlogin",$data);
	}
	public function actionWbIndex()
	{
		$data=array(
				"code"		=>	Yii::app()->request->getParam('code',""),
		);
		$this->layout="test_page_list";
		$this->render("test_page_wblogin",$data);
	}	
}