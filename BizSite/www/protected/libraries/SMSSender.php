<?php

LunaLoader::import("luna_lib.verify.ILunaSmsCodeSender");
LunaLoader::import("luna_lib.log.LunaLogger");

//实现短信验证码发送  需要实现
class SMSSender implements ILunaSmsCodeSender{ 
	
	function init($configure)
	{
		
	}
	function sendSmsCode($code)
	{
		$logMsg=sprintf("Simulate send sms code:%s",$code);
		LunaLogger::getInstance()->info($logMsg);
		return 0;
	}
}

