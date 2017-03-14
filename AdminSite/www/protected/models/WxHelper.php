<?php
LunaLoader::import("luna_lib.http.HttpInterface");
LunaLoader::import("luna_lib.util.CGuidManager");
LunaLoader::import("luna_lib.util.LunaMemcache");

defined('WX_PAY_CERT_PEM') || define('WX_PAY_CERT_PEM', dirname(__FILE__)."/pay/apiclient_cert.pem");
defined('WX_PAY_KEY_PEM') || define('WX_PAY_KEY_PEM', dirname(__FILE__)."/pay/apiclient_key.pem");

class WxHelper
{
	const WX_APP_ID="wx8a417e8a8315d161";
	const WX_SECRET="c458469a3a7ba712d2d8408dc488a45a";
	
	
	public static function getAccessToken($bUsingCache=true)
	{
		$cacheCfgNodeName="TencentWx";
		$cacheKey="WX.ACCESS.TOKEN";
		$expire=7100;
		
		$cachedValue =LunaMemcache::GetInstance($cacheCfgNodeName)->read($cacheKey);
		if(isset($cachedValue) &&  $cachedValue!=false && empty($cachedValue)==false && $bUsingCache){
			return $cachedValue;
		}else{
			$http=new HttpInterface("Tencent","AccessToken");
			$params=array(
					"grant_type"		=>	"client_credential",
					"appid"				=>	WxHelper::WX_APP_ID,
					"secret"			=>	WxHelper::WX_SECRET,
			);
			$data=$http->submit($params);
			if(isset($data) && is_array($data) && isset($data["access_token"]) && empty($data["access_token"])==false){
				$cachedValue=$data["access_token"];
				LunaMemcache::GetInstance($cacheCfgNodeName)->write($cacheKey,$cachedValue,$expire);
			}
			return $cachedValue;
		}
	} 
	
	public static function sendWxPlayerNotifyMsg($openId,$msgTemplateId,$msgParams,$topColor="#FFF000")
	{
		$http=new HttpInterface("Tencent","NotifyMsg");
		$appendUrl=sprintf("?access_token=%s",WxHelper::getAccessToken());
		$params=array(
				"touser"		=>	$openId,
				"template_id"	=>	$msgTemplateId,
				"topcolor"		=>	$topColor,
				"data"			=>	$msgParams,
		);
		LunaLogger::getInstance()->info(print_r($params,true));
		$data=$http->submit($params,true,array(),false,$appendUrl,true);
		if(isset($data) && is_array($data) && isset($data["errcode"]) && ($data["errcode"]=="40001" || $data["errcode"]=="40002" || $data["errcode"]=="42001")){
			$appendUrl=sprintf("?access_token=%s",WxHelper::getAccessToken(false));
			$data=$http->submit($params,true,array(),false,$appendUrl,true);
		}
	}
		
	const WX_PAY_MCH_ID		=	"1243763002";						//微信支付分配的商户号
	const WX_PAY_SIGN_KEY	=	"sDg2015SumMdac23kdA85kAS93nB36cn";
	
	public static $_LAST_SEND_RED_ENVELOPE_ERROR_CODE_DES="";
	
	public static function generateOurOrderId()
	{
		
		$ourOrderId=sprintf("%s%s%s",WxHelper::WX_PAY_MCH_ID,date("Ymd"),CGuidManager::GetRandomString(10));
		$cachedValue=LunaMemcache::GetInstance()->read($ourOrderId);
		while( (isset($cachedValue) && empty($cachedValue)==false) ){
			$ourOrderId=sprintf("%s%s%s",WxHelper::WX_PAY_MCH_ID,date("Ymd"),CGuidManager::GetRandomString(10));
			$cachedValue=LunaMemcache::GetInstance()->read($ourOrderId);
		}
		LunaMemcache::GetInstance()->write($ourOrderId, $ourOrderId,24*3600);
		return $ourOrderId; 
	}
	public static function queryRedEnvelope($mchBillingNo)
	{
		$params=array(
			"nonce_str"		=>	CGuidManager::GetGuid(),
			"mch_billno"	=>	$mchBillingNo,
			"mch_id"		=>	WxHelper::WX_PAY_MCH_ID,
			"appid"			=>	WxHelper::WX_APP_ID,	
			"bill_type"		=>	"MCHT",	
		);
		$params["sign"]			=self::getSign($params);
		$opt=array(
				CURLOPT_SSLCERTTYPE => 	"PEM",
				CURLOPT_SSLCERT		=>	WX_PAY_CERT_PEM,
				CURLOPT_SSLKEYTYPE	=>	"PEM",
				CURLOPT_SSLKEY		=>	WX_PAY_KEY_PEM,
		);
		
		$http=new HttpInterface("WxPay", "QueryRedEnvelope");
		$data=$http->submit($params,false,$opt,false,"",false,true);
		if(empty($data)==false){
			libxml_disable_entity_loader(true);
			return json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		}
		return array();
	}
	
	public static function sendRedEnvelope($openId,$amount,$mchBillingNo)
	{
		$fAppData=new FAppData();
		$envelopeParam=$fAppData->getSystemConfigItem("tencent.mq.envelope.info","",true);
		if(is_array($envelopeParam) && count($envelopeParam)>0){
			$envelopeParam["nonce_str"] 	= CGuidManager::GetGuid();
			$envelopeParam["mch_id"]		= WxHelper::WX_PAY_MCH_ID;
			$envelopeParam["mch_billno"]	= $mchBillingNo;
			$envelopeParam["wxappid"]		= WxHelper::WX_APP_ID;
			$envelopeParam["re_openid"]		= $openId;
			$envelopAmt=bcmul($amount,"100",0);
			$envelopeParam["total_amount"]	= $envelopAmt;
			$envelopeParam["min_value"]		= $envelopAmt;
			$envelopeParam["max_value"]		= $envelopAmt;
			$envelopeParam["total_num"]		= 1;
			$envelopeParam["client_ip"]		= LunaWebUtil::getServerIp();
			$envelopeParam["sign"]			=self::getSign($envelopeParam);
			
			$opt=array(
					CURLOPT_SSLCERTTYPE => 	"PEM",
					CURLOPT_SSLCERT		=>	WX_PAY_CERT_PEM,
					CURLOPT_SSLKEYTYPE	=>	"PEM",
					CURLOPT_SSLKEY		=>	WX_PAY_KEY_PEM,
			);
				
			$http=new HttpInterface("WxPay", "SendRedEnvelope");
			$data=$http->submit($envelopeParam,false,$opt,false,"",false,true);
			if(empty($data)==false){
				libxml_disable_entity_loader(true);
				$responseObj = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
				if($responseObj->return_code=="SUCCESS"){
					if($responseObj->result_code=="SUCCESS"){
						return $responseObj->send_listid;
					}else{
						self::$_LAST_SEND_RED_ENVELOPE_ERROR_CODE_DES=$responseObj->err_code_des;
					}					
				}else{
					self::$_LAST_SEND_RED_ENVELOPE_ERROR_CODE_DES=$responseObj->return_msg;
				}
			}
		}
		return "";
	}
	
	private static function getSign($Obj)
	{
		foreach ($Obj as $k => $v){
			$Parameters[$k] = $v;
		}
		ksort($Parameters);
		$String = self::formatBizQueryParaMap($Parameters, false);
		$String = $String."&key=".WxHelper::WX_PAY_SIGN_KEY;
		LunaLogger::getInstance()->info($String);
		$String = md5($String);
		return strtoupper($String);
	}
	private static  function formatBizQueryParaMap($paraMap, $urlencode)
	{
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
			if($urlencode){
				$v = urlencode($v);
			}
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0)
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
}
