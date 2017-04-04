<?php

LunaLoader::import("luna_lib.log.LunaLogger");
LunaLoader::import("luna_lib.verify.ILunaImgCodeDrawer");

class LunaCodeVerify {
	private static $_instance = null;
	public static function getInstance()
	{
		if (!isset(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	const _CODE_VERIFY_CONFIG_SECTION	="CodeVerify";
	
	const _CODE_TYPE_IMG	=1;
	const _CODE_TYPE_SMS	=2;
	
	private $_expire_seconds =300;
	private $_max_sms_verify_counts=10;
	private $_max_img_verify_counts=1;
	
	private $_code_length=6;
	private $_enable_logger=true;
	
	//图片验证码属性
	private $_img_code_charset="abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789";
	private $_img_width=160;
	private $_img_height=50;
	private $_img_regenerate_showing=true;
	private $_img_draw_method=0;

	private $_img_draw_instance=null;
	
	//短信验证码属性
	private $_sms_code_charset="1234567890";
	private $_sms_sender_instance=null;
	
	private function __construct()
	{
		$confSection=LunaConfigMagt::getInstance()->getLunaSDKConfigSection(self::_CODE_VERIFY_CONFIG_SECTION);
		
		$this->_setPropertyIfExist($confSection, "_enable_logger","enable_logger","TRUE");
		$this->_setPropertyIfExist($confSection, "_code_length","code_lenth");
		$this->_setPropertyIfExist($confSection, "_expire_seconds","expire_seconds");
		$this->_setPropertyIfExist($confSection, "_max_sms_verify_counts","max_sms_verify_counts");
		$this->_setPropertyIfExist($confSection, "_max_img_verify_counts","max_img_verify_counts");
		
		$confImg=$confSection["ImgParameters"];
		if(isset($confImg)){
			$this->_setPropertyIfExist($confImg, "_img_code_charset","code_charset");
			$this->_setPropertyIfExist($confImg, "_img_width","width");
			$this->_setPropertyIfExist($confImg, "_img_height","height");
			$this->_setPropertyIfExist($confImg, "_img_regenerate_showing","regenerate_showing","TRUE");
			$this->_setPropertyIfExist($confImg, "_img_draw_method","drawing_method");
			if(isset($confImg["DrawImgImplementers"])){
				$imgImplementers=$confImg["DrawImgImplementers"];
				$implementerThisTime=null;
				if($this->_img_draw_method>=0 && count($imgImplementers)>=$this->_img_draw_method){
					$implementerThisTime=$imgImplementers[$this->_img_draw_method];
				}
				if($this->_img_draw_method<0 && count($imgImplementers)>0){
					$implementerIndex= rand(0,count($imgImplementers)-1);
					$implementerThisTime=$imgImplementers[$implementerIndex];
				}
				if($implementerThisTime!=null){
					$imgDrawer=$implementerThisTime["className"];
					$className=LunaLoader::import($imgDrawer);
					$this->_img_draw_instance=new $className;
					$this->_img_draw_instance->init($implementerThisTime,$this->_img_width,$this->_img_height);
				}
			}
		}
		$confSms=$confSection["SmsParameters"];
		if(isset($confSms)){
			$this->_setPropertyIfExist($confSms, "_sms_code_charset","code_charset");
			if(isset($confSms["senderClassName"]) && empty($confSms["senderClassName"])==false){
				$this->_sms_sender_instance=new $confSms["senderClassName"];
				$this->_sms_sender_instance->init($confSms);
			}
		}
		
	}
	private function _setPropertyIfExist($properties,$propertyName,$configureKey=null,$trueValue4BoolProprety=null)
	{
		if(isset($configureKey)==false || empty($configureKey)){
			$configureKey=$propertyName;
		}
		if(isset($properties[$configureKey])){
			if(isset($trueValue4BoolProprety)){
				$this->$propertyName=(strtoupper($properties[$configureKey])==$trueValue4BoolProprety);
			}else{
				$this->$propertyName=$properties[$configureKey];
			}
		}
	}
	
	private function getCode($code_type,$phone="")
	{
		$session_code_key="_code_".$code_type;
		if(isset(Yii::app()->session[$session_code_key])){
			$code_info=Yii::app()->session[$session_code_key];
			return $code_info["code"];
		}
		
		$code_charset=($code_type==self::_CODE_TYPE_IMG?$this->_img_code_charset:$this->_sms_code_charset);
		$code_charset_length=strlen($code_charset)-1;
		$code="";
		for($i=0;$i<$this->_code_length;$i++){
			$code.=$code_charset[mt_rand(0,$code_charset_length)];
		}
		
		$code_info=array(
			"code"			=>	$code,
			"verify_count"	=>	0,
			"time"			=>	time(),
			"phone"			=>	$phone,
		);
		Yii::app()->session[$session_code_key]=$code_info;
		if($this->_enable_logger){
			$logMsg=sprintf("<LunaCodeVerify>\tInitCode\t<%s>\t<%s>",$code,$code_info["time"]);
			LunaLogger::getInstance()->info($logMsg);			
		}
		return $code;
	}
	
	public function verifyImageCode($code)
	{
		return $this->verifyCode(self::_CODE_TYPE_IMG, $code);
	}
	
	public function showImageCode($responseDirectly=true)
	{
		$session_code_key="_code_".self::_CODE_TYPE_IMG;
		if($this->_img_regenerate_showing){
			unset(Yii::app()->session[$session_code_key]);
		}
		$code=$this->getCode(self::_CODE_TYPE_IMG);
		if($this->_img_draw_instance!=null){
			$imgData=$this->_img_draw_instance->getImgDatas($code);
			if($responseDirectly){
				header('Content-type:'.$this->_img_draw_instance->getImgContentType());
				Header("Accept-Ranges: bytes");
				echo $imgData;
				return true;
				
			}else{
				return array(
					"img_data"		=>		$imgData,
					"content_type"	=>		$this->_img_draw_instance->getImgContentType(),
				);
			}			
		}
		return false;
	} 
	
	public function verifySmsCode($code,$phone)
	{
		return  $this->verifyCode(self::_CODE_TYPE_SMS, $code,$phone);
	}
	
	public function sendSmsCode($phone)
	{
		$code=$this->getCode(self::_CODE_TYPE_SMS,$phone);
		if($this->_sms_sender_instance!=null){
			return $this->_sms_sender_instance->sendSmsCode($code,$phone);
		}
		$logMsg="<LunaCodeVerify>\tSendSmsCode\t<no sms sender implement class>";
		LunaLogger::getInstance()->info($logMsg);
		return false;
	}
	
	/*
	 * -1		未申请过code验证或者会话过期
	 * -2		code已经过期，需要重新发生验证码或者显示验证码图片
	 * -3		验证尝试次数已达最大次数，需要重新发生验证码或者显示验证码图片
	 * -4		code  错误
	 */
	private function verifyCode($code_type,$code,$phone="")
	{
		$session_code_key="_code_".$code_type;
		$code_info=Yii::app()->session[$session_code_key];
		if(isset($code_info) && is_array($code_info)){
			$timeNow=time();
			if(($timeNow-$code_info["time"])>$this->_expire_seconds){
				unset(Yii::app()->session[$session_code_key]);
				return -2;
			}
			$code_info["verify_count"]+=1;
			if($code!=$code_info["code"] || $phone!= $code_info["phone"]){
				if($code_type==self::_CODE_TYPE_IMG && $code_info["verify_count"]>=$this->_max_img_verify_counts){
					unset(Yii::app()->session[$session_code_key]);
					return -3;
				}
				if($code_type==self::_CODE_TYPE_SMS && $code_info["verify_count"]>=$this->_max_sms_verify_counts){
					unset(Yii::app()->session[$session_code_key]);
					return -3;
				}
				Yii::app()->session[$session_code_key]=$code_info;
				return -4;
			}
			unset(Yii::app()->session[$session_code_key]);
			return 0;
		}
		return -1;	
	}	
}

