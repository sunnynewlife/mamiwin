<?php

LunaLoader::import("luna_lib.verify.ILunaSmsCodeSender");
LunaLoader::import("luna_lib.log.LunaLogger");
LunaLoader::import("luna_lib.http.HttpInterface");

//实现短信验证码发送  需要实现
class SMSSender implements ILunaSmsCodeSender{ 
	private $_SKIP_SMS_SEND=false;
	
	function init($configure)
	{
		if(isset($configure["skip_sms_send"]) && empty($configure["skip_sms_send"])==false){
			$this->_SKIP_SMS_SEND=(strtoupper($configure["skip_sms_send"])=="TRUE");
		}
	}
	function sendSmsCode($code,$phone)
	{
		$logMsg=sprintf("sms code send,phone=%s code=%s",$phone,$code);
		if($this->_SKIP_SMS_SEND){
			LunaLogger::getInstance()->info("SKIP ".$logMsg);
			return true;
		}else{
			LunaLogger::getInstance()->info($logMsg);
			$params=array(
					"mtLevel"		=>	1,
					"msgFormat"		=>	2,
					"corpID"		=>	"",
					"loginName"		=>	"",
					"password"		=>	"",
					"Mobs"			=>	$phone,
					"msg"			=>	sprintf("短信验证码:%s",$code),
					"kindFlag"		=>	"PhoneVerify",
						
					"subNumber"		=>	"",
					"linkID"		=>	"",
			);
			$http=new HttpInterface("CoSMS","sendSms");
			$result=$http->submit($params);
			list($return_code)=explode($result,"\r");
			return 	$return_code=="100";
		}
	}
}

