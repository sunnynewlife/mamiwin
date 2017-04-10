<?php

LunaLoader::import("luna_lib.verify.LunaCodeVerify");
require_once(dirname(__FILE__).'/../config/ConfTask.php');

class InterfaceController extends CController 
{

	public $_errorNo;
	public $_errorMessage;
	private $_USER_SESSION_KEY="user";								//session key
	private $_need_login_method_type = array(
		3004,3005);

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
			case '1001':		// 查询所有资料列表
				return 'queryMeterialList';
				break;
			case '1002':	//根据ID查询资料
				return 'getMeterialDetail';	
				break;
			case '1003':	//提交基础资料
				return 'addUserBasicInfo';	
				break;
			case '2001':		// 查询所有任务列表
				return 'queryTaskMeterialList';
				break;
			case '2002':	//根据ID查询任务
				return 'getTaskMeterialDetail';	
				break;
			case '3001':	// 查询所有测评题列表
				return 'getEvaluationQuesitonsList';
				break;
			case '3002':	//根据ID查询测评题
				return 'getEvaluationQuesitons';	
				break;
			case '3003':	// 查询所有测评题集列表
				return 'getEvaluationQuesitonsSetList';
				break;
			case '3004':	// 获取下一题
				return 'getUserNextQuestion';
				break;
			case '3005':	// 提交评测题结果
				return 'recordUserQuestionResult';
				break;
			case '9001':	//手机用户登录
				return 'userLogin';	
				break;
			case '9002':	//手机用户注册
				return 'userRegist';	
				break;
			case '9003':	//用户登出
				return 'userLogout';	
				break;
			case '9004':	//获取用户信息，登录成功后
				return 'getUserInfo';	
				break;
			case '9005':	//短信发送
				return 'sendSms';	
				break;
			case '9006':	//修改密码
				return 'resetPassword';	
				break;
			
			case '9999':	//TEST
				return 'test';		
				break;
			default:
				return false;
				break;
		
		}
	}	
	public function checkParams($params){
		if(!array_key_exists('type',$params) || isset($params['type']) == false){
			$this->_errorNo = ConfTask::ERROR_PARAMS;
		}
		$type = $params['type'];
		switch ($type) {
			case '1002':
				if(isset($params['IDX']) == false || empty($params['IDX'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " phone " ;
				}				
				break;
			case '1003':
				if(isset($params['Parent_Gender']) == false || empty($params['Parent_Gender'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " phone " ;
				}				
				if(isset($params['Parent_Marriage']) == false || empty($params['Parent_Marriage '])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " phone " ;
				}				
				if(isset($params['Child_Gender']) == false || empty($params['Child_Gender'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " phone " ;
				}				
				if(isset($params['Child_Birthday']) == false || empty($params['Child_Birthday'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " phone " ;
				}				
				break;
			case '1005':
				if(isset($params['IDX']) == false || empty($params['IDX'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " phone " ;
				}				
				break;
			case '1007':
				if(isset($params['IDX']) == false || empty($params['IDX'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " phone " ;
				}				
				break;
			case '2002':
				if(isset($params['IDX']) == false || empty($params['IDX'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " phone " ;
				}				
				break;
			case '3004':
				if(isset($params['User_IDX']) == false || empty($params['User_IDX'])){
					$this->_errorNo = ConfTask::ERROR_USER_NOT_LOGIN;
					$this->_errorMessage = " User_IDX " ;
				}				
				break;
			case '3005':
				if(isset($params['User_IDX']) == false || empty($params['User_IDX'])){
					$this->_errorNo = ConfTask::ERROR_USER_NOT_LOGIN;
					$this->_errorMessage = " User_IDX " ;
				}				
				break;
			case '9001':
				if(isset($params['phone']) == false || empty($params['phone'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " phone " ;
				}
				if(isset($params['password']) == false || empty($params['password'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " password " ;
				}
				
				break;
			case '9002':
				if(isset($params['phone']) == false || empty($params['phone'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " phone " ;
				}
				if(isset($params['password']) == false || empty($params['password'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " password " ;
				}
				
				break;
			case '9005':
				if(isset($params['phone']) == false || empty($params['phone'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " phone " ;
				}
				break;
			case '9006':
				if(isset($params['phone']) == false || empty($params['phone'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " phone " ;
				}
				if(isset($params['password']) == false || empty($params['password'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " password " ;
				}
				
				break;

			default:
				# code...
				break;
		}
	}

	private  function checkMethodLoginin($type){

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
		
		$userInfo=Yii::app()->session[$this->_USER_SESSION_KEY];
		$ret = array(
			"LoginName"		=>	$userInfo[0]["LoginName"],
			"IDX"			=>	$userInfo[0]["IDX"],
		);
		if(isset($userInfo)){
			$LoginName = $userInfo[0]["LoginName"];
			$User_IDX = $userInfo[0]["IDX"];
			$body = array_merge(array(
					'LoginName'=>$LoginName,
					'User_IDX'=>$User_IDX
				),$body);
		}
		
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
			$this->checkParams($body);
			if(!empty($this->_errorNo)){
				$this->_echoResponse($this->_errorNo);
				return;
			}
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
		$Is_Show_Index 	= isset($params['Is_Show_Index']) ? $params['Is_Show_Index'] : '';

		$mod = new ModMaterial();
		$ret = $mod->getMeterialList(1,$Is_Show_Index,$pagesize,$start);
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
	}  

	private function getMeterialDetail($params){
		$IDX = $params['IDX'] ;
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

	private function queryTaskMeterialList($params){
		if(isset($params['page'])){
			$page 	= $params['page'];
		}
		if(isset($params['pagesize'])){
			$pagesize 	= $params['pagesize'];
		}
		if(empty($pagesize)){$pagesize =  10; }
		if(empty($page)){$page =  1; }
		$start = ($page - 1) * $pagesize;	

		$mod = new ModTaskMaterial();
		$ret = $mod->getTaskMeterialList(1,$pagesize,$start);
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
	}  

	private function getTaskMeterialDetail($params){
		$IDX = $params['IDX'] ;
		$mod = new ModTaskMaterial();
		$ret = $mod->getTaskMeterialDetail($IDX);
		if($ret === false){			
			$errno = ConfTask::ERROR_QUEYR_TASK_DETAIL ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
	}

	/**
	 * 查询所有的评测题集
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	private function getEvaluationQuesitonsSetList($params){
		// if(isset($params['page'])){
		// 	$page 	= $params['page'];
		// }
		// if(isset($params['pagesize'])){
		// 	$pagesize 	= $params['pagesize'];
		// }
		// if(empty($pagesize)){$pagesize =  10; }
		// if(empty($page)){$page =  1; }
		// $start = ($page - 1) * $pagesize;	
		$Question_Set_IDX 	= isset($params['Question_Set_IDX']) ? $params['Question_Set_IDX'] : '';

		$mod = new ModEvaluationQuesitonsSet();
		$ret = $mod->getEvaluationQuesitonsSetList();
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret);
	}
	/**
	 * 根据题集选择评测题 
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	private function getEvaluationQuesitonsList($params){
		if(isset($params['page'])){
			$page 	= $params['page'];
		}
		if(isset($params['pagesize'])){
			$pagesize 	= $params['pagesize'];
		}
		if(empty($pagesize)){$pagesize =  10; }
		if(empty($page)){$page =  1; }
		$start = ($page - 1) * $pagesize;	
		$Question_Set_IDX 	= $params['Question_Set_IDX'];

		$mod = new ModEvaluationQuesitons();
		$ret = $mod->getEvaluationQuesitonsList($Question_Set_IDX,$pagesize,$start);
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
	}  

	/**
	 * 获取单道评测题
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	private function getEvaluationQuesitons($params){
		$IDX = $params['IDX'] ;
		$mod = new ModEvaluationQuesitons();
		$ret = $mod->getEvaluationQuesitons($IDX);
		if($ret === false){			
			$errno = ConfTask::ERROR_QUEYR_TASK_DETAIL ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
	}


	//账号注册
	private function userRegist($params)
	{
		$phone=$params['phone'] ;
		$password=$params['password'] ;
		// $img_verify_code=$params['img_verify_code'] ;
		$sms_verify_code=$params['sms_verify_code'] ;
		
		// if(empty($phone) || empty($password) || empty($img_verify_code) || empty($sms_verify_code)){
		// 	return $this->_response(-99,"参数错误");
		// }
		
		// $imgVerifyCode=$lunaCodeVerify->verifyImageCode($img_verify_code);
		// if($imgVerifyCode!=0){			
		// 	$errno = ConfTask::ERROR_USER_LOGIN_PASSWORD ;
		// 	$this->_echoResponse($errno);
		// 	return;
		// }
		$lunaCodeVerify=LunaCodeVerify::getInstance();
		$smsVerifyCode=$lunaCodeVerify->verifySmsCode($sms_verify_code);
		if($smsVerifyCode!=0){
			return $this->_response_error("sms_code", $smsVerifyCode-20);
			$errno = ConfTask::ERROR_USER_LOGIN_PASSWORD ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}
		$bizAppData= new BizAppData();
		$userInfo=$bizAppData->getUserInfoByLoginName($phone, BizDataDictionary::User_AcctSource_SelfSite);
		if(count($userInfo)>0){
			// return $this->_response("-1","手机号已注册");
			$errno = ConfTask::ERROR_USER_EXISTS ;
			$this->_echoResponse($errno);
			return;
		}
		$md5password=md5($password);
		if($bizAppData->registUserInfo($phone, BizDataDictionary::User_AcctSource_SelfSite, $md5password)){
			// return $this->_response();
			$errno = 1 ;
			$this->_echoResponse($errno);
			return;
		}
		// return $this->_response("-2","注册失败，请稍后重试");
		$errno = ConfTask::ERROR_USER_REGIST ;
		$this->_echoResponse($errno);
		return;

	}	

	//用户登录
	private function userLogin($params){
		$phone=$params['phone'] ;
		$password=$params['password'] ;
		// if(empty($phone) || empty($password)){
		// 	return $this->_response(-99,"参数错误");
		// }
		$bizAppData= new BizAppData();
		$userInfo=$bizAppData->getUserInfoByLoginName($phone, BizDataDictionary::User_AcctSource_SelfSite);
		$md5password=md5($password);
		if(count($userInfo)==0 || $userInfo[0]["LoginPwd"]!=$md5password ){
			$errno = ConfTask::ERROR_USER_LOGIN_PASSWORD ;
			$this->_echoResponse($errno);
			return;
		}
		if($userInfo[0]["AcctStatus"]!=BizDataDictionary::User_AcctStatus_Valid){
			$errno = ConfTask::ERROR_USER_LOGIN_USER ;
			$this->_echoResponse($errno);
			return;
		}
		$session_code_key="user";
		Yii::app()->session[$this->_USER_SESSION_KEY]=$userInfo;
		$errno = 1 ;
		$this->_echoResponse($errno);
		return ;
	}
	
	//账号注销
	private function userLogout()
	{
		unset(Yii::app()->session[$this->_USER_SESSION_KEY]);
		$errno = 1 ;
		$this->_echoResponse($errno);
	}
	//获取登录用户信息
	private  function getUserInfo()
	{
		if(isset(Yii::app()->session[$this->_USER_SESSION_KEY])==false){
			$errno = ConfTask::ERROR_USER_NOT_LOGIN ;
			$this->_echoResponse($errno);
			return;
		}
		$userInfo=Yii::app()->session[$this->_USER_SESSION_KEY];
		$ret = array(
			"LoginName"		=>	$userInfo[0]["LoginName"],
			"IDX"			=>	$userInfo[0]["IDX"],
		);
		$errno = 1 ;
		$this->_echoResponse($errno);
	}
	//发送短信前，必须 输入图片验证码，注册、修改密码
	private function sendSms($params){
		$phone=$params['phone'] ;
		$img_verify_code=$params['img_verify_code'] ;
		$lunaCodeVerify=LunaCodeVerify::getInstance();
		$imgVerifyCode=$lunaCodeVerify->verifyImageCode($img_verify_code);
		if($imgVerifyCode!=0){
			$errno = ConfTask::ERROR_USER_IMAGE_CODE ;
			$this->_echoResponse($errno);
			return ;
		}
		
		if($lunaCodeVerify->sendSmsCode($phone)){
			$errno = 1 ;
			$this->_echoResponse($errno);
			return ;
		}
		$errno = ConfTask::ERROR_SMS_SEND ;
		$this->_echoResponse($errno);
	}

	//修改密码
	private function resetPassword($params){
		$phone=$params['phone'] ;
		$password=$params['password'] ;
		$img_code=$params['img_verify_code'] ;
		$sms_code=$params['sms_verify_code'] ;

		// $lunaCodeVerify=LunaCodeVerify::getInstance();
		// $imgVerifyCode=$lunaCodeVerify->verifyImageCode($img_verify_code);
		// if($imgVerifyCode!=0){
		// 	// return $this->_response_error("img_code", $imgVerifyCode-10);
		// 	$errno = ConfTask::ERROR_USER_IMAGE_CODE ;
		// 	$this->_echoResponse($errno);
		// 	return ;
		// }
		// $smsVerifyCode=$lunaCodeVerify->verifySmsCode($sms_verify_code,$phone);
		// if($smsVerifyCode!=0){
		// 	// return $this->_response_error("sms_code", $smsVerifyCode-20);
		// 	$errno = ConfTask::ERROR_USER_SMS_CODE ;
		// 	$this->_echoResponse($errno);
		// 	return ;
		// }
		$bizAppData= new BizAppData();
		$userInfo=$bizAppData->getUserInfoByLoginName($phone, BizDataDictionary::User_AcctSource_SelfSite);
		if(count($userInfo)==0){
			// return $this->_response("-1","手机号未注册");
			$errno = ConfTask::ERROR_USER_PHONE_REGIST ;
			$this->_echoResponse($errno);
			return ;
		}
		$md5password=md5($password);
		if($bizAppData->resetPwd($phone, BizDataDictionary::User_AcctSource_SelfSite, $md5password)){
			$errno = 1 ;
			$this->_echoResponse($errno);
			return ;
		}
		// return $this->_response("-2","重设失败，请稍后重试");
		$errno = ConfTask::ERROR_USER_PASSWORD_RESET ;
		$this->_echoResponse($errno);
		return ;
	}

	/**
	 * 获取用户下一题
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	private function getUserNextQuestion($params){
		$User_IDX = $params['User_IDX'];
		$mod = new ModUserEvaluationQuesitons();
		$ret = $mod->getNextQuestion($User_IDX);
		if($ret === false){			
			$errno = ConfTask::ERROR_QUESTION_GET_NEXT ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}
		if(empty($ret)){
			$errno = ConfTask::ERROR_QUESTION_GET_NEXT_EMPTY ;
			$this->_echoResponse($errno,'',$ret);
			return;	
		}
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 

	}

	/**
	 * 提交评测题答案
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	private function recordUserQuestionResult($params){
		$User_IDX = $params['User_IDX'];
		$Question_IDX = $params['Question_IDX'];
		$Option = trim($params['Option']);
		$mod_question = ModEvaluationQuesitons::getInstance();
		$ret_question = $mod_question->getEvaluationQuesitons($Question_IDX);
		if(empty($ret_question)){
			$errno = ConfTask::ERROR_USER_QUESTION_GET ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}
	
		if($Option == "A"){
			$Point = $ret_question['Point_A'];
		}else  if($Option == "B"){
			$Point = $ret_question['Point_B'];
		}else  if($Option == "C"){
			$Point = $ret_question['Point_C'];
		}else  if($Option == "D"){
			$Point = $ret_question['Point_D'];
		}
		$mod_user_question = ModUserEvaluationQuesitons::getInstance();
		$ret_user_question = $mod_user_question->recordUserQuestionResult($User_IDX,$Question_IDX,$Point);
		if($ret_user_question === false){
			$errno = ConfTask::ERROR_USER_QUESTION_RECORD ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}
		$errno = 1 ;
		$this->_echoResponse($errno); 

	}
}