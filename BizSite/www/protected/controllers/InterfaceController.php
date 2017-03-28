<?php

LunaLoader::import("luna_lib.verify.LunaCodeVerify");


class InterfaceController extends CController 
{

	public $_errorNo;
	public $_errorMessage;
	

	public function _echoResponse($errno, $attach_errmsg = '', $data = array(), $count = null) {
		$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '*';  		
		header("Access-Control-Allow-Origin: {$origin}");
		header("Access-Control-Allow-Credentials: true");		
   		header("Access-Control-Allow-Headers: *, X-Requested-With, Content-Type");
   		header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");

        $showdata = array();
        $showdata['code'] = $errno;
        $showdata['server_time'] = time();
        $errmsg = ConfTask::transError($errno);
        if($attach_errmsg) { 
            $errmsg = $errmsg . ' -- ' . $attach_errmsg;
        }
        if($this->_errorMessage) {
            $showdata['debug_message']  =  $this->_errorMessage;
        }
        $showdata['message'] 	= $errmsg;
        if(!empty($data)){
        	$showdata['data'] 	= $data;	
        }		
		if(!is_null($count)){
        	$showdata['count'] 	= $count;	
        }
		// LibLogger::log(' return  #(showdata:),('.json_encode($showdata).')');
    	echo(json_encode($showdata));
    	return ;
    }

    //路由
	public function urlRouter($type){
		switch ($type) {
			case '1001':		// 查询所有任务资料列表
				return 'queryMeterialList';
				break;
			case '1002':	//根据ID查询任务资料
				return 'getMeterialDetail';	
				break;
			case '1003':	//提交基础资料
				return 'addUserBasicInfo';	
				break;
			
			case '9999':	//TEST
				return 'test';		
				break;
			default:
				return false;
				break;
		
		}
	}	
	public function actionTest(){
		$vc = new LibValidateCode();
		$vc->doimg();
		var_dump($vc->getCode());
	}

	public function actionIndex(){		
		$body	= file_get_contents("php://input");
		$bodys	= json_decode($body,true);

		if(!is_array($bodys)){
			$this->_errorNo = ConfTask::ERROR_PARAMS;
			$this->_echoResponse($this->_errorNo);
			return;
		}
		// LibLogger::log(' Ok #(body:),('.json_encode($bodys).')');
		$header = $bodys['header'];
		$body = $bodys['body'];
		// $str_token = empty($header['token']) ?  $_COOKIE["login_token"] : $header['token'] ;
		// $from  = empty($header['from']) ? "" : $header['from'];		//接口调用者：''：app；'wx'：微信；
		// $curlRet	= GamesHelper::token($str_token);
		// if($curlRet['message'] != '成功')
		// 	{
		// 		$errno = ConfTask::ERROR_VERIFY_TOKEN ;
		// 		$this->_echoResponse($errno);
		// 		return;
		// }
			
		// $userid = 0 ;
		// $phone = '';
		// $realname = '';
		// $nickname = '';
		// $address = '';

		// if(isset($curlRet['userinfo'])){
		// 	$userid = $curlRet['userinfo']['id'];
		// 	$phone = $curlRet['userinfo']['phone'];
		// 	$realname = $curlRet['userinfo']['realname'];
		// 	$nickname = $curlRet['userinfo']['nickname'];
		// 	$address = $curlRet['userinfo']['address'];
		// 	$avatar = $curlRet['userinfo']['avatar'];
		// 	$rpc_sex = ($curlRet['userinfo']['sex'] == 2 ) ? 0 : 1 ;//默认为男,用户中心：0：未知；1：男；2：女
		// 	LibUsers::checkUserInfo($curlRet['userinfo']);
		// }
		$body = array_merge(array('UserIDX'=>1),$body);
		if(array_key_exists('type',$body)){
			$type	= $body['type'];	
		}else{
			$errno  = ConfTask::ERROR_PARAMS ;
			$this->_errorMessage = " type " ;
			$this->_echoResponse($errno);
			return;
		}

		if(isset($type) && !empty($type)){
			$method	= $this->urlRouter($type);
		}
		if(isset($method) && !empty($method)){
			// var_dump("method=" . $method);
			// CommonHelper::my_var_dump(get_defined_vars()); 
			$this->$method($body);
		}else{
			$errno  = ConfTask::ERROR_METHOD ;
			$this->_echoResponse($errno);
			return;
		}
		die();
	}

	private function queryMeterialList($params){
		if(isset($params['page'])){
			$page 	= $params['page'];
		}
		if(isset($params['pagesize'])){
			$pagesize 	= $params['pagesize'];
		}
		if(empty($pagesize)){$pagesize =  10; }
		if(empty($page)){$page =  1; }
		$start = ($page - 1) * $pagesize;	


		$mod = new ModMaterial();
		$ret = $mod->getMeterialList(1,$pagesize,$start);
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
	}  

	private function getMeterialDetail($params){
		// $IDX = $params['IDX'] ;
		$mod = new ModMaterial();
		$ret = $mod->getMeterialDetail($IDX);
		if($ret === false){			
			$errno = ConfTask::ERROR_QUEYR_TASK_DETAIL ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
	}

	//用户基础资料录入
	private function addUserBasicInfo($params){
		$UserIDX = isset($params['UserIDX']) ? 1 :$params['UserIDX']  ;
		$Parent_Gender = $params['Parent_Gender'] ;
		$Parent_Marriage = $params['Parent_Marriage'] ;
		$Child_Gender = $params['Child_Gender'] ;
		$Child_Birthday = $params['Child_Birthday'] ;
		$mod = new ModUserBasicInfo();
		$ret = $mod->addUserBasicInfo($UserIDX,$Parent_Gender,$Parent_Marriage,$Child_Gender,$Child_Birthday);
		if($ret === false){			
			$errno = ConfTask::ERROR_USER_BASICINFO_ADD ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
	}


	//显示图片验证码 
	public function actionShowImgCode()
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