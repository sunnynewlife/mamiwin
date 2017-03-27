<?php

LunaLoader::import("luna_lib.verify.LunaCodeVerify");


class InterfaceController extends CController 
{
	//显示图片验证码 
	public function actionShowImgCode
	{	
		$lunaCodeVerify=LunaCodeVerify::getInstance();
		$lunaCodeVerify->showImageCode();
	}
	//发送短信验证码
	public function actionSendSms()
	{
		$lunaCodeVerify=LunaCodeVerify::getInstance();
		echo "send sms result:".$lunaCodeVerify->sendSmsCode();
	}
	
	/*
	 * -1		未申请过code验证或者会话过期
	 * -2		code已经过期，需要重新发生验证码或者显示验证码图片
	 * -3		验证尝试次数已达最大次数，需要重新发生验证码或者显示验证码图片
	 * -4		code  错误
	*/
	//验证图片验证码
	public function actionVerifyImgCode()
	{
		$code=Yii::app()->request->getParam('code',"");
		if(empty($code)==false){
			$lunaCodeVerify=LunaCodeVerify::getInstance();
			$verifyCode=$lunaCodeVerify->verifyImageCode($code);
			echo "result=$verifyCode";
		}
	}
	public function actionVerifySms()
	{
		$code=Yii::app()->request->getParam('code',"");
		if(empty($code)==false){
			$lunaCodeVerify=LunaCodeVerify::getInstance();
			$verifyCode=$lunaCodeVerify->verifySmsCode($code);
			echo "result=$verifyCode";
		}	
	}
}