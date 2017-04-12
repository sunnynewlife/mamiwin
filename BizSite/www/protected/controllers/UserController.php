<?php

LunaLoader::import("luna_lib.verify.LunaCodeVerify");
LunaLoader::import("luna_lib.log.LunaLogger");

class UserController extends CController 
{
	private $_ERROR_MAP=array(
			"img_code"	=>	array(	//图片验证码错误
					"-11"		=>	"请刷新图片验证码 ",					//-1		未申请过code验证或者会话过期
					"-12"		=>	"验证超时，请刷新图片验证码",			//-2		code已经过期，需要重新发生验证码或者显示验证码图片
					"-13"		=>	"验证错误，请刷新图片验证码",			//-3		验证尝试次数已达最大次数，需要重新发生验证码或者显示验证码图片
					"-14"		=>	"验证码错误",						//-4		code  错误
			),
			"sms_code"	=>	array(
					"-21"		=>	"请发送短信验证码",					//-1		未申请过code验证或者会话过期
					"-22"		=>	"短信验证超时，请重新发送短信验证码",	//-2		code已经过期，需要重新发生验证码或者显示验证码图片
					"-23"		=>	"短信验证错误，请重新发送短信验证码",	//-3		验证尝试次数已达最大次数，需要重新发生验证码或者显示验证码图片
					"-24"		=>	"短信验证码错误",					//-4		code  错误
			),
	);
	private $_USER_SESSION_KEY="user";								//session key
	
	
	//显示图片验证码 
	public function actionShowImgCode()
	{	
		$lunaCodeVerify=LunaCodeVerify::getInstance();
		$lunaCodeVerify->showImageCode();
	}
	//发送短信验证码
	public function actionSendSms()
	{
		$phone=Yii::app()->request->getParam('phone',"");
		if(empty($phone)){
			return $this->_response("-99","参数错误");
		}
		$lunaCodeVerify=LunaCodeVerify::getInstance();
		if($lunaCodeVerify->sendSmsCode($phone)){
			return $this->_response();
		}
		return $this->_response("-1","发送短信失败");
	}
	
	private function _response($code=0,$message="success",$data=array()){
		header("Content-Type:application/json;charset=utf-8");
		$responseData=array(
			"code"		=>	$code,
			"message"	=>	$message,
			"data"		=>	$data,
		);
		echo json_encode($responseData);
	}
	
	private function _response_error($component_name,$error_code){
		if(isset($this->_ERROR_MAP[$component_name])){
			$error_map=$this->_ERROR_MAP[$component_name];
			return $this->_response($error_code,$error_map[$error_code]);
		}
	}

	//账号注册
	public function actionRegist()
	{
		$phone=Yii::app()->request->getParam('phone',"");
		$password=Yii::app()->request->getParam('password',"");
		$img_verify_code=Yii::app()->request->getParam('img_code',"");
		$sms_verify_code=Yii::app()->request->getParam('sms_code',"");
		if(empty($phone) || empty($password) || empty($img_verify_code) || empty($sms_verify_code)){
			return $this->_response(-99,"参数错误");
		}
		$lunaCodeVerify=LunaCodeVerify::getInstance();
		$imgVerifyCode=$lunaCodeVerify->verifyImageCode($img_verify_code);
		if($imgVerifyCode!=0){
			return $this->_response_error("img_code", $imgVerifyCode-10);
		}
		$smsVerifyCode=$lunaCodeVerify->verifySmsCode($sms_verify_code,$phone);
		if($smsVerifyCode!=0){
			return $this->_response_error("sms_code", $smsVerifyCode-20);
		}
		$bizAppData= new BizAppData();
		$userInfo=$bizAppData->getUserInfoByLoginName($phone, BizDataDictionary::User_AcctSource_SelfSite);
		if(count($userInfo)>0){
			return $this->_response("-1","手机号已注册");
		}
		$md5password=md5($password);
		if($bizAppData->registUserInfo($phone, BizDataDictionary::User_AcctSource_SelfSite, $md5password)){
			return $this->_response();
		}
		return $this->_response("-2","注册失败，请稍后重试");
	}
	//账号登录
	public function actionLogin()
	{
		$phone=Yii::app()->request->getParam('phone',"");
		$password=Yii::app()->request->getParam('password',"");
		if(empty($phone) || empty($password)){
			return $this->_response(-99,"参数错误");
		}
		$bizAppData= new BizAppData();
		$userInfo=$bizAppData->getUserInfoByLoginName($phone, BizDataDictionary::User_AcctSource_SelfSite);
		$md5password=md5($password);
		if(count($userInfo)==0 || $userInfo[0]["LoginPwd"]!=$md5password ){
			return $this->_response("-1","账号或者密码错误");
		}
		if($userInfo[0]["AcctStatus"]!=BizDataDictionary::User_AcctStatus_Valid){
			return $this->_response("-2","账号异常，不能登录");
		}
		$userInfo[0]["AcctSource"]	=	BizDataDictionary::User_AcctSource_SelfSite;
		$session_code_key="user";
		Yii::app()->session[$this->_USER_SESSION_KEY]=$userInfo[0];
		$this->_response(0,"success",$userInfo[0]);		
	}
	//账号注销
	public function actionLogout()
	{
		unset(Yii::app()->session[$this->_USER_SESSION_KEY]);
	}
	//获取登录用户信息
	public  function actionGetLoginInfo()
	{
		if(isset(Yii::app()->session[$this->_USER_SESSION_KEY])==false){
			return $this->_response("-1","未登录");
		}
		$userInfo=Yii::app()->session[$this->_USER_SESSION_KEY];
		$this->_response(0,"success",array(
			"LoginName"		=>	$userInfo["LoginName"],
			"IDX"			=>	$userInfo["IDX"],
			"AcctSource"	=>	$userInfo["AcctSource"],
		));
	}
	//重新设置账号密码
	public function actionResetpwd()
	{
		$phone=Yii::app()->request->getParam('phone',"");
		$password=Yii::app()->request->getParam('password',"");
		$img_verify_code=Yii::app()->request->getParam('img_code',"");
		$sms_verify_code=Yii::app()->request->getParam('sms_code',"");
		if(empty($phone) || empty($password) || empty($img_verify_code) || empty($sms_verify_code)){
			return $this->_response(-99,"参数错误");
		}
		$lunaCodeVerify=LunaCodeVerify::getInstance();
		$imgVerifyCode=$lunaCodeVerify->verifyImageCode($img_verify_code);
		if($imgVerifyCode!=0){
			return $this->_response_error("img_code", $imgVerifyCode-10);
		}
		$smsVerifyCode=$lunaCodeVerify->verifySmsCode($sms_verify_code,$phone);
		if($smsVerifyCode!=0){
			return $this->_response_error("sms_code", $smsVerifyCode-20);
		}
		$bizAppData= new BizAppData();
		$userInfo=$bizAppData->getUserInfoByLoginName($phone, BizDataDictionary::User_AcctSource_SelfSite);
		if(count($userInfo)==0){
			return $this->_response("-1","手机号未注册");
		}
		$md5password=md5($password);
		if($bizAppData->resetPwd($phone, BizDataDictionary::User_AcctSource_SelfSite, $md5password)){
			return $this->_response();
		}
		return $this->_response("-2","重设失败，请稍后重试");
	}
	//微信登录
	public function actionWxLogin()
	{
		$code			=	Yii::app()->request->getParam('code',"");
		$needUserInfo	=	Yii::app()->request->getParam('userinfo',"");
		if(empty($code)){
			return $this->_response(-99,"参数错误");
		}
		$wxUser=WxHelper::getOpenId($code);
		if($wxUser==false){
			return $this->_response("-1","code 错误");
		}
		$bizAppData= new BizAppData();
		$userInfo=$bizAppData->getUserInfoByLoginName($wxUser["openid"], BizDataDictionary::User_AcctSource_Tencent_Wx);
		if(count($userInfo)==0){
			$bizAppData->registThirdUserInfo($wxUser["openid"], BizDataDictionary::User_AcctSource_Tencent_Wx);
			$userInfo=$bizAppData->getUserInfoByLoginName($wxUser["openid"], BizDataDictionary::User_AcctSource_Tencent_Wx);
		}
		if(count($userInfo)==0){
			return $this->_response("-2","记录第3方账号出错");
		}
		$userInfo[0]["AcctSource"]	=	BizDataDictionary::User_AcctSource_Tencent_Wx;
		$userInfo[0]["OpenUserInfo"]=  ($needUserInfo=="1"? WxHelper::getOpenIdUserInfo($wxUser["openid"]):array());

		$session_code_key="user";
		Yii::app()->session[$this->_USER_SESSION_KEY]=$userInfo[0];
		$this->_response(0,"success",array(
			"LoginName"		=>		$wxUser["openid"],
			"IDX"			=>		$userInfo[0]["IDX"],
			"AcctSource"	=>		BizDataDictionary::User_AcctSource_Tencent_Wx,
			"OpenUserInfo"	=>		$userInfo[0]["OpenUserInfo"],
		));
	}
	//微信JSAPI Config
	public function actionWxJSAPIConfig()
	{
		$url			=	Yii::app()->request->getParam('url',"");
		if(empty($url)){
			return $this->_response(-99,"参数错误");
		}
		$JsSDKData=	WxHelper::getJSSDKData($url);
		$this->_response(0,"success",$JsSDKData);
	}
	
}