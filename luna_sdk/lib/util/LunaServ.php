<?php
LunaLoader::import("luna_core.LunaConfigMagt");
LunaLoader::import("luna_lib.log.LunaLogger");

class LunaServ 
{
	const	ERROR_MECHANT_NAME_CODE		=	-150010001;										//调用缺失调用者参数
	const	ERROR_MECHANT_NAME_MSG		=	"访问错误，确保传递调用者ID已在逗逗 dsdk申请过。";	//调用缺失调用者参数
	const	ERROR_MECHANT_SIGN_CODE		=	-150010002;										//签名失败
	const	ERROR_MECHANT_SIGN_MSG		=	"访问错误，参数签名不匹配。";						//签名失败
	
	const   ERROR_SUCCESS_CODE			=	0;												//操作成功
	const	ERROR_SUCCESS_MSG			=	"操作成功";

	public $code;
	public $message;
	
	protected $_MERCHANT_NAME		=	"";						//调用者ID
	protected $_SIGN_KEY			=	"";						//调用者密钥

	protected $_PARAM_MERCHANT_LIST =	array("merchant_name","osap_user");	//传递 调用者ID 可能的参数名
	
	protected $_PARAM_MERCHANT		=	"merchant_name";		//传递 调用者ID 参数名
	protected $_PARAM_SIGN_METHOD	=	"signature_method";		//传递 签名方法   参数名
	protected $_PARAM_SIGNATURE		=	"signature";			//传递 签名结果   参数名
	protected $_PARAM_TIMESTAMP		=	"timestamp";			//传递 时间戳      参数名
	
	protected $_SIGN_KEY_POSITION	=	"foot";					//密钥位置
	protected $_PARAM_JOIN_PARAM	=	"&";					//参数和参数之间连接字符
	protected $_PARAM_JOIN_VALUE	=	"=";					//参数和值之间连接字符
	protected $_EXCLUDE_PARAM_NAME	=	false;					//签名串是否剔除参数名称
	
	protected $_SIGN_ALGORITHM		=	"md5";					//签名算法
	
	protected $_AUTH_CALLER			=	false;					//调用者身份校验是否通过
	protected $_LOG_SIGN_STRING		=	false;					//是否记录签名前字符串
	
	protected $_SKIP_SIGN_VERIFY	=	false;					//开发测试开关
	
	private $_configs;
	
	public function query($arrayParameters)
	{
		$queryResult=array();
		foreach ($arrayParameters as $parameterKey){
			$paramValue = Yii::app()->request->getParam($parameterKey,"");
			$queryResult[$parameterKey]=$paramValue;
		}
		return $queryResult;
	}	
	
	protected function loadAuthParamsConf($authParams)
	{
		if(isset($authParams["merchant"]) && empty($authParams["merchant"])==false){
			$this->_PARAM_MERCHANT		=	$authParams["merchant"];
		}
		if(isset($authParams["signature_method"]) && empty($authParams["signature_method"])==false){
			$this->_PARAM_SIGN_METHOD		=	$authParams["signature_method"];
		}
		if(isset($authParams["signature"]) && empty($authParams["signature"])==false){
			$this->_PARAM_SIGNATURE		=	$authParams["signature"];
		}
		if(isset($authParams["timestamp"]) && empty($authParams["timestamp"])==false){
			$this->_PARAM_TIMESTAMP		=	$authParams["timestamp"];
		}
		if(isset($authParams["key_position"]) && empty($authParams["key_position"])==false){
			$this->_SIGN_KEY_POSITION		=	 strtolower($authParams["key_position"]);
		}
		if(isset($authParams["param_join_param"])){
			$this->_PARAM_JOIN_PARAM		=	$authParams["param_join_param"];
		}
		if(isset($authParams["param_join_value"])){
			$this->_PARAM_JOIN_VALUE		=	$authParams["param_join_value"];
		}
		if(isset($authParams["exclude_param_name"])){
			$this->_EXCLUDE_PARAM_NAME=($authParams["exclude_param_name"]=="true");
		}
		if(isset($authParams["sign_algorithm"])){
			$this->_SIGN_ALGORITHM= strtolower($authParams["sign_algorithm"]);
		}
		if(isset($authParams["log_sign_trace"])){
			$this->_LOG_SIGN_STRING= ($authParams["log_sign_trace"]=="true");
		}		
		if(isset($authParams["merchant_list"]) && empty($authParams["merchant_list"])==false){
			$this->_PARAM_MERCHANT_LIST = explode(",", $authParams["merchant_list"]);
		}
	}
	
	public function __construct()
	{
		$this->code		= self::ERROR_SUCCESS_CODE;
		$this->message	= self::ERROR_SUCCESS_MSG;
			
		$this->_configs=LunaConfigMagt::getInstance()->getLunaSDKConfigSection("LunaService");
		$servConfig=$this->_configs;
		if(isset($servConfig)){
			if(isset($servConfig["auth_params"])){
				$authParams=$servConfig["auth_params"];
				$this->loadAuthParamsConf($authParams);
			}
		}
		$parameter = $this->query(array($this->_PARAM_MERCHANT));
		//未找到，尝试其他参数名
		if(isset($parameter)==false || isset($parameter[$this->_PARAM_MERCHANT])==false || empty($parameter[$this->_PARAM_MERCHANT])){
			if(isset($this->_PARAM_MERCHANT_LIST) && is_array($this->_PARAM_MERCHANT_LIST) && count($this->_PARAM_MERCHANT_LIST)>0){
				$parameter = $this->query($this->_PARAM_MERCHANT_LIST);
				foreach ($parameter  as $paramKey => $paramValue){
					if(empty($paramValue)==false){
						$this->_PARAM_MERCHANT=$paramKey;						//传递 调用者ID 参数名
						break;
					}
				}
			}
		}
		
		if(isset($parameter) && is_array($parameter) && isset($parameter[$this->_PARAM_MERCHANT]) && empty($parameter[$this->_PARAM_MERCHANT])==false){
			$this->_MERCHANT_NAME	=	$parameter[$this->_PARAM_MERCHANT];		//调用者
			if(isset($servConfig[$this->_MERCHANT_NAME])){
				$authorCfg	=	$servConfig[$this->_MERCHANT_NAME];				//调用者 参数配置
				if(isset($authorCfg["sign_key"])){
					$this->_SIGN_KEY	=	$authorCfg["sign_key"];				//签名密钥
				}
				if(isset($authorCfg["auth_params"])){
					$authParams=$authorCfg["auth_params"];
					if(isset($authParams["skip_signature"])){
						$this->_SKIP_SIGN_VERIFY= $authParams["skip_signature"];
					}
					$this->loadAuthParamsConf($authParams);						
				}
				$this->_AUTH_CALLER	=true;
			}
		}		
	}
	
	public function check_merchant()
	{
		if($this->_AUTH_CALLER==false){
			$this->code		=		self::ERROR_MECHANT_NAME_CODE;
			$this->message	=		self::ERROR_MECHANT_NAME_MSG;
		}
		return $this->_AUTH_CALLER;
	}
	
	public function verify_signature($bizParameters)
	{
		$sign_params=$this->query(array($this->_PARAM_MERCHANT,$this->_PARAM_SIGN_METHOD,$this->_PARAM_SIGNATURE,$this->_PARAM_TIMESTAMP));
		if(isset($sign_params[$this->_PARAM_SIGN_METHOD]) && empty($sign_params[$this->_PARAM_SIGN_METHOD])==false){
			$this->_SIGN_ALGORITHM	=	strtolower($sign_params[$this->_PARAM_SIGN_METHOD]);
		}
	
		$signature_value	= 	 $sign_params[$this->_PARAM_SIGNATURE];			//传递的签名值
	
		unset($sign_params[$this->_PARAM_SIGNATURE]);							//去除签名值参数
	
		$verify_params=array_merge($sign_params,$bizParameters);
		$expect_signature_value	=$this->calculateSignature($verify_params);
		$verify_ok=(strtolower($signature_value)== strtolower($expect_signature_value));
		if($verify_ok==false){
			if($this->_LOG_SIGN_STRING){
				LunaLogger::getInstance()->info("expect signature:$expect_signature_value");
			}
			$this->code		=		self::ERROR_MECHANT_SIGN_CODE;
			$this->message	=		self::ERROR_MECHANT_SIGN_MSG;
		}
		if($this->_SKIP_SIGN_VERIFY){
			$verify_ok=true;
			$this->code		= self::ERROR_SUCCESS_CODE;
			$this->message	= self::ERROR_SUCCESS_MSG;
		}
		return $verify_ok;
	}	
	
	protected function calculateSignature($params)
	{
		if($this->_SIGN_ALGORITHM  == "hmac-sha1"){
			return $this->calculateSignatureHmacSha1($params);
		}
		return $this->calculateSignatureMd5($params);
	}
	
	protected function calculateSignatureHmacSha1($params)
	{
		ksort($params);
		$arr_sign = array();
		foreach ($params as $key=>$val) {
			$arr_sign[] = sprintf("%s%s%s",($this->_EXCLUDE_PARAM_NAME?"":$key),$this->_PARAM_JOIN_VALUE,$val);
		}
		$str_sign = implode($this->_PARAM_JOIN_PARAM, $arr_sign);
		if($this->_LOG_SIGN_STRING){
			LunaLogger::getInstance()->info("Before HMAC-SHA1 String:$str_sign");
		}
		return bin2hex(hash_hmac('sha1', $str_sign, $this->_signPwd, TRUE));
	}
	
	protected function calculateSignatureMd5($params)
	{
		ksort($params);
		$arr_sign = array();
		foreach ($params as $key=>$val) {
			$arr_sign[] = sprintf("%s%s%s",($this->_EXCLUDE_PARAM_NAME?"":$key),$this->_PARAM_JOIN_VALUE,$val);
		}
		$str_sign = implode($this->_PARAM_JOIN_PARAM, $arr_sign);
		if($this->_SIGN_KEY_POSITION=="head"){
			$str_sign =  $this->_SIGN_KEY.$str_sign;
		}else if($this->_SIGN_KEY_POSITION=="foot"){
			$str_sign .= $this->_SIGN_KEY;
		}
		if($this->_LOG_SIGN_STRING){
			LunaLogger::getInstance()->info("Before MD5 String:$str_sign");
		}
		return md5($str_sign);
	}	
}
