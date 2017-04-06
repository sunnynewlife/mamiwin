<?php

LunaLoader::import("luna_lib.verify.ILunaSmsCodeSender");
LunaLoader::import("luna_lib.log.LunaLogger");
LunaLoader::import("luna_lib.http.HttpInterface");

//实现短信验证码发送  需要实现
class SMSSender implements ILunaSmsCodeSender{ 
	private $_SKIP_SMS_SEND=false;
	
	const _LoginName="HUANJIN";
	const _CorpID="2059386";
	const _Password="100208abc";
	
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
			$this_password=self::_Password;
			
			$http=new HttpInterface("CoSMS","getSecretKey");
			$cookie_jar = tempnam('./tmp','cookie');
			$result=$http->submit(array("_v" =>	1),false,array(CURLOPT_COOKIEJAR => $cookie_jar));
			$this_password=md5(md5(self::_CorpID.self::_LoginName.self::_Password).$result);

			$params=array(
					"mtLevel"		=>	1,
					"msgFormat"		=>	2,
					"corpID"		=>	self::_CorpID,
					"loginName"		=>	self::_LoginName,
					"password"		=>	$this_password,
					"Mobs"			=>	$phone,
					"msg"			=>	sprintf("请在页面里输入验证码:%s。【父母赢】",$code),
					"kindFlag"		=>	"PhoneVerify",
					"MD5str"		=>	$result,
					
					"subNumber"		=>	"",
					"linkID"		=>	"",					
			);
			$http=new HttpInterface("CoSMS","sendSms");
			$result=$http->submit($params,false,array( "cookiejar" => $cookie_jar ));
			
			list($return_code)=explode("\n",$result);
			return 	$return_code=="100";
		}
	}
}

