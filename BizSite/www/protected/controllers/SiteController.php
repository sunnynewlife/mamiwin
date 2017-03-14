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