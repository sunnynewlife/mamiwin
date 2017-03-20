<?php
require_once(dirname(__FILE__).'/../libraries/LibValidateCode.php');
require_once(dirname(__FILE__).'/../config/ConfTask.php');
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
				return 'queryTaskList';
				break;
			case '1002':	//根据ID查询任务资料
				return 'queryTaskDetail';	
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
		// $body = array_merge(array('userid'=>$userid,'userphone'=>$phone,'realname'=>$realname,'nickname'=>$nickname,'address'=>$address,'sex'=>$rpc_sex,'avatar'=>$avatar,'from'=>$from),$body);
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

	private function queryTaskList($params){
		if(isset($params['page'])){
			$page 	= $params['page'];
		}
		if(isset($params['pagesize'])){
			$pagesize 	= $params['pagesize'];
		}
		if(empty($pagesize)){$pagesize =  10; }
		if(empty($page)){$page =  1; }
		$start = ($page - 1) * $pagesize;	


		$mod = new ModTask();
		$ret = $mod->getTaskList(0,$pagesize,$start);
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
	}  

	private function queryTaskDetail($params){
		$task_id = $params['id'] ;
		$mod = new ModTask();
		$ret = $mod->getTaskDetail($task_id);
		if($ret === false){			
			$errno = ConfTask::ERROR_QUEYR_TASK_DETAIL ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
	}

}