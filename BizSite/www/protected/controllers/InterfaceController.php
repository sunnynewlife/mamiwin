<?php
LunaLoader::import("luna_lib.verify.LunaCodeVerify");
LunaLoader::import("luna_lib.util.CGuidManager");
require_once(dirname(__FILE__).'/../config/ConfTask.php');
require_once(dirname(__FILE__).'/../libraries/LibUserQuestions.php');



class InterfaceController extends CController 
{

	public $_errorNo;
	public $_errorMessage;
	private $_USER_SESSION_KEY="user";								//session key
	private $_OPENID_SESSION_KEY="openid";							//第三方 openid session key
	private $_need_login_method_type = array(1003,1004,1005,2003,2004,2005,2006,2007,2008,3003,3004,3005,4001,5001,5002,003,9004,9006,9007,9008);

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
		// if(!is_null($count)){
  //       	$showdata['count'] 	= $count;	
  //       }
		// LibLogger::log(' return  #(showdata:),('.json_encode($showdata).')');
		$str_return = json_encode($showdata);
		
        $callback=Yii::app()->request->getParam('callback',"");
        if(empty($callback)){
        	echo(json_encode($showdata));
        }else{
        	echo $callback."(". json_encode($showdata).");";
        }
        LunaLogger::getInstance()->info(($str_return));
    	
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
			case '1004':	//查询用户基础资料
				return 'queryUserBasicInfo';	
				break;
			case '1005':	//更新用户基础资料
				return 'updateUserBasicInfo';	
				break;
			case '2001':		// 查询所有任务列表
				return 'queryTaskMeterialList';
				break;
			case '2002':	//根据ID查询任务
				return 'getTaskMeterialDetail';	
				break;
			case '2003':	//获取用户任务列表
				return 'getUserTaskList';
				break;
			case '2004':	//开始任务
				return 'startUserTask';
				break;
			case '2005':	//完成、结束任务
				return 'finishUserTask';
				break;
			case '2006':	//换一个任务
				return 'switchUserTask';
				break;
			case '2007':	//按月查询用户已完成任务数
				return 'queryUserTaskMonth';
				break;
			case '2008':	//按日查询用户已完成任务数
				return 'queryUserTaskDay';
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
			case '3006':	// 查询所有测评题集列表
				return 'getEvaluationQuesitonsSetByIDX';
				break;
			
			case '4001':	// 分享
				return 'shareOut';
				break;
			
			case '5001':	// 任务评价
				return 'taskEvaluate';
				break;
			case '5002':	// 任务评价
				return 'queryTaskEvaluteList';
				break;
			case '8001':	// 图片上传
				return 'uploadFile';
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
			case '9007':	//用户签到
				return 'userSignIn';	
				break;
			case '9008':	//用户签到
				return 'userUnBind';	
				break;
			case '9010':	//微信登录跳转URL
				return 'getWechatRedirectUrl';	
				break;
			case '9011':	//微信token
				return 'wechatToken';	
				break;
			case '9012':	//微信签名，用于分享
				return 'wechatSign';	
				break;
			case '9013':	//微信用户完成登录
				return 'wechatLogin';	
				break;
			case '9014':	//微博用户完成登录
				return 'wbLogin';	
				break;
			case '9015':	//QQ用户完成登录
				return 'qqLogin';	
				break;
			case '9016':	//微博登录跳转URL
				return 'getWeiboRedirectUrl';	
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
					$this->_errorMessage = " Parent_Gender " ;
				}				
				if(isset($params['Parent_Marriage']) == false || empty($params['Parent_Marriage'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Parent_Marriage " ;
				}				
				if(isset($params['Child_Gender']) == false || empty($params['Child_Gender'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Child_Gender " ;
				}				
				if(isset($params['Child_Birthday']) == false || empty($params['Child_Birthday'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Child_Birthday " ;
				}				
				break;
			// case '1005':
			// 	if(isset($params['IDX']) == false || empty($params['IDX'])){
			// 		$this->_errorNo = ConfTask::ERROR_PARAMS;
			// 		$this->_errorMessage = " phone " ;
			// 	}				
			// 	break;
			// case '1007':
			// 	if(isset($params['IDX']) == false || empty($params['IDX'])){
			// 		$this->_errorNo = ConfTask::ERROR_PARAMS;
			// 		$this->_errorMessage = " phone " ;
			// 	}				
			// 	break;
			case '2002':
				if(isset($params['IDX']) == false || empty($params['IDX'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " phone " ;
				}				
				break;
			case '2004':
				if(isset($params['Task_IDX']) == false || empty($params['Task_IDX'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Task_IDX " ;
				}				
				break;
			case '2005':
				if(isset($params['Task_IDX']) == false || empty($params['Task_IDX'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Task_IDX " ;
				}				
				break;
			case '2007':
				if(isset($params['Query_Month']) == false || empty($params['Query_Month'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Query_Month " ;
				}				
				// if(isset($params['Finish_Status']) == false || empty($params['Finish_Status'])){
				// 	$this->_errorNo = ConfTask::ERROR_PARAMS;
				// 	$this->_errorMessage = " Finish_Status " ;
				// }				
				break;
			case '2008':
				if(isset($params['Query_Day']) == false || empty($params['Query_Day'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Query_Day " ;
				}				
				break;
			case '3004':
				if(isset($params['Question_Set_IDX']) == false || empty($params['Question_Set_IDX'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Question_Set_IDX " ;
				}				
				break;
			case '3005':
				if(isset($params['Question_IDX']) == false || empty($params['Question_IDX'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Question_IDX " ;
				}				
				if(isset($params['Option']) == false || empty($params['Option'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Option " ;
				}				
				break;
			case '3006':
				if(isset($params['Question_Set_IDX']) == false || empty($params['Question_Set_IDX'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Question_Set_IDX " ;
				}				
				break;
			case '4001':
				if(isset($params['Share_Type']) == false || empty($params['Share_Type'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Share_Type " ;
				}				
				if(isset($params['Share_IDX']) == false || empty($params['Share_IDX'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Share_IDX " ;
				}				
				if(isset($params['Share_To']) == false || empty($params['Share_To'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Share_To " ;
				}				
				break;
			case '5001':
				if(isset($params['Task_IDX']) == false || empty($params['Task_IDX'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Task_IDX " ;
				}				
				if(isset($params['Finish_Score']) == false || empty($params['Finish_Score'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Finish_Score " ;
				}				
				break;
			case '5002':
				if(isset($params['Task_IDX']) == false || empty($params['Task_IDX'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " Task_IDX " ;
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
				if(isset($params['sms_verify_code']) == false || empty($params['sms_verify_code'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " sms_verify_code " ;
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
			case '9008':
				if(isset($params['acctSource']) == false || empty($params['acctSource'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " acctSource " ;
				}				
				break;
			case '9012':
				if(isset($params['url']) == false || empty($params['url'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " url " ;
				}
				break;
			case '9013':
				if(isset($params['code']) == false || empty($params['code'])){
					$this->_errorNo = ConfTask::ERROR_PARAMS;
					$this->_errorMessage = " code " ;
				}
				break;
			default:
				# code...
				break;
		}
	}

	public function actionTest1(){
		$UserIDX = 2 ;
		$Question_Set_IDX = 2 ;
		$ret = LibUserTasks::generateUserTaskEva($UserIDX,$Question_Set_IDX);
		var_dump($ret);

	}

	private  function checkMethodLoginin($type){

	}
	public function actionIndex(){	
		// $body	= file_get_contents("php://input");
		$body = Yii::app()->request->getParam('body',"");
		$bodys	= json_decode($body,true);

		if(!is_array($bodys)){
			$this->_errorNo = ConfTask::ERROR_PARAMS;
			$this->_echoResponse($this->_errorNo);
			return;
		}
		// LibLogger::log(' Ok #(body:),('.json_encode($bodys).')');
		$header = isset($bodys['header'])?$bodys['header']:'';
		$body = $bodys['body'];
		
		
		// $str_token = empty($header['token']) ?  $_COOKIE["login_token"] : $header['token'] ;
		// $from  = empty($header['from']) ? "" : $header['from'];		//½Ó¿Úµ÷ÓÃÕß£º''£ºapp£»'wx'£ºÎ¢ÐÅ£»
		// $curlRet	= GamesHelper::token($str_token);
		// if($curlRet['message'] != '³É¹¦')
		// 	{
		// 		$errno = ConfTask::ERROR_VERIFY_TOKEN ;
		// 		$this->_echoResponse($errno);
		// 		return;
		// }
		
		
		if(array_key_exists('type',$body)){
			$type	= $body['type'];	
		}else{
			$errno  = ConfTask::ERROR_PARAMS ;
			$this->_errorMessage = " type " ;
			$this->_echoResponse($errno);
			return;
		}
		$UserIDX = 0 ;
		$userInfo=Yii::app()->session[$this->_USER_SESSION_KEY];
		$ret = array(
			"LoginName"		=>	$userInfo[0]["LoginName"],
			"IDX"			=>	$userInfo[0]["IDX"],
		);
		if(isset($userInfo)){
			$LoginName = $userInfo[0]["LoginName"];
			$UserIDX = $userInfo[0]["IDX"];
			$body = array_merge(array(
					'LoginName'=>$LoginName,
					'UserIDX'=>$UserIDX
				),$body);
		}

		$hostname = php_uname('n'); //本地环境，免登录
		if(in_array($hostname, array("ADMIN-PC","SUNNY-PC","DESKTOP-D4K54MD"))){
			$UserIDX =  2 ; 
			$body = array_merge(array(
				'LoginName'=>'15900828187',
				'UserIDX'=> $UserIDX,
			),$body);	
		}

		if(isset($type) && !empty($type)){
			if(in_array($type, $this->_need_login_method_type) && empty($UserIDX)){
				$errno  = ConfTask::ERROR_USER_NOT_LOGIN ;
				$this->_echoResponse($errno);
				return;
			}
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

	//用户基础资料录入,自动分配评测题给用户
	private function addUserBasicInfo($params){
		$UserIDX = $params['UserIDX'] ;
		$Parent_Gender = $params['Parent_Gender'] ;
		$Parent_Marriage = $params['Parent_Marriage'] ;
		$Child_Gender = $params['Child_Gender'] ;
		$Child_Birthday = $params['Child_Birthday'] ;
		$Avatar = isset($params['Avatar']) ? $params['Avatar'] : '' ;
		$mod = new ModUserBasicInfo();
		$ret = $mod->addUserBasicInfo($UserIDX,$Parent_Gender,$Parent_Marriage,$Child_Gender,$Child_Birthday,$Avatar);
		if($ret === false){			
			$errno = ConfTask::ERROR_USER_BASICINFO_ADD ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}
		// TODO ,基础资料录入完成之后 ，自动 分配任务 
		// LibUserQuestions::distributeUserQuestsion($UserIDX,$Parent_Gender,$Parent_Marriage,$Child_Gender,$Child_Birthday);
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
	}

	// 修改用户基础资料
	private function updateUserBasicInfo($params){
		$UserIDX = $params['UserIDX'] ;
		$Parent_Gender = isset($params['Parent_Gender']) ? $params['Parent_Gender'] : '' ;
		$Parent_Marriage = isset($params['Parent_Marriage']) ? $params['Parent_Marriage'] : '' ;
		$Child_Gender = isset($params['Child_Gender']) ? $params['Child_Gender'] : '' ;
		$Child_Birthday = isset($params['Child_Birthday']) ? $params['Child_Birthday'] : '' ;
		$Avatar = isset($params['Avatar']) ? $params['Avatar'] : '' ;
		$mod = new ModUserBasicInfo();
		$ret = $mod->updateUserBasicInfo($UserIDX,$Parent_Gender,$Parent_Marriage,$Child_Gender,$Child_Birthday,$Avatar);
		if($ret === false){			
			$errno = ConfTask::ERROR_USER_BASICINFO_UPDATE ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
	}

	//查询用户基础资料
	private function queryUserBasicInfo($params){
		$UserIDX = $params['UserIDX'] ;
		$mod = new ModUserBasicInfo();
		$ret = $mod->queryUserBasicInfo($UserIDX);
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
	}  

	//查询任务列表
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

	// 任务详情
	private function getTaskMeterialDetail($params){
		$IDX = $params['IDX'] ;
		$mod = new ModTaskMaterial();
		$ret = $mod->getTaskMeterialDetail($IDX);
		if($ret === false ||empty($ret)){			
			$errno = ConfTask::ERROR_QUEYR_TASK_DETAIL ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}
		$Material_Files_File_Type = $ret['File_Type'];
		$Material_Files_Location_Type = $ret['Location_Type'];
		$ret['Show_Url'] = '';
		if( $Material_Files_Location_Type == DictionaryData::Material_Files_Location_Type_OutUrl ){
			$ret['Show_Url'] = $ret['File_Content'];//如果是外链接，返回外链ur
			unset($ret['File_Content']);	
		}
		//登录用户，还将返回任务状态信息
		if(isset($params['UserIDX'])){
			$UserIDX = $params['UserIDX'] ;
			$mod_user_task = new ModUserTask();
			$ret_user_task = $mod_user_task->getUserTaskDetail($UserIDX,$IDX);
			if($ret_user_task){
				$ret['Finish_Status'] = $ret_user_task['Finish_Status'];
			}
		}
		// 返回任务平均分
		$mod_user_task = new ModUserTask();
		$ret_user_task = $mod_user_task->queryTaskAvgScore($IDX);
		$task_avg_score = 0 ;		
		if(!empty($ret_user_task)){
			$task_avg_score = $ret_user_task['score'];
		}
		$ret['task_avg_score'] = $task_avg_score;
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
	}

	// 获取另一项任务
	private function getAnotherTaskMeterialDetail($params){
		$UserIDX = $params['UserIDX'] ;
		$IDX = 2 ; //TODO 逻辑待完善
		$mod = new ModTaskMaterial();
		$ret = $mod->getTaskMeterialDetail($IDX);
		if($ret === false){			
			$errno = ConfTask::ERROR_QUEYR_TASK_DETAIL ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}
		$Material_Files_File_Type = $ret['File_Type'];
		$Material_Files_Location_Type = $ret['Location_Type'];
		$ret['Show_Url'] = '';
		if( $Material_Files_Location_Type == DictionaryData::Material_Files_Location_Type_OutUrl ){
			$ret['Show_Url'] = $ret['File_Content'];//Èç¹ûÊÇÍâÁ´½Ó£¬·µ»ØÍâÁ´url	
		}
		unset($ret['File_Content']);
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
	}

	//查询用户任务列表，
	//如果没有任务，则自动分配任务给用户
	//如果所有的的任务都做完了，则自动分配任务给用户
	private function getUserTaskList($params){
		$max_user_task_count = DictionaryData::Max_User_Task_Count;
		$UserIDX = $params['UserIDX'];
		$Task_Type = isset($params['Task_Type']) ? $params['Task_Type'] : 0 ;
		$mod = new ModUserTask();
		// $ret = $mod->getUserTaskListByTrun($UserIDX,$Task_Type,0,$max_user_task_count,0);
		$ret = LibUserTasks::getUserTaskListByTrun($UserIDX,$Task_Type,0,$max_user_task_count,0);

		if($ret === false){			
			$errno = ConfTask::ERROR_QUEYR_USER_TASK_LIST ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}
		// 没有任务，自动分配任务
		if(empty($ret)){
			$Turn = $mod->getCurrentUserTaskTurn($UserIDX) ;
			// $ret_user_task = $mod->generateUserTaskRandom($UserIDX,$Task_Type,$Turn + 1 );
			$ret_user_task = LibUserTasks::generateUserTaskRandom($UserIDX,$Task_Type,$Turn + 1 );
		
			// $ret = $mod->getUserTaskListByTrun($UserIDX,$Task_Type,$Turn,$max_user_task_count,0);
			$ret = LibUserTasks::getUserTaskListByTrun($UserIDX,$Task_Type,$Turn,$max_user_task_count,0);
			if($ret === false){			
				$errno = ConfTask::ERROR_QUEYR_USER_TASK_LIST ;
				$this->_echoResponse($errno,'',$ret);
				return;
			}
		}
		
		$is_all_task_finished = true;
		if(empty($ret) == false){
			foreach ($ret as $key => $value) {
				$Finish_Status = $value['Finish_Status'];
				$Turn = $value['Turn'];
				if($Finish_Status != DictionaryData::User_Task_Status_Finish){
					$is_all_task_finished = false;
				}
			}
			// 所有任务都已经完成，再分配新的任务
			if(($is_all_task_finished)){				
				$Next_Turn  = $Turn + 1 ;			
				// $ret_user_task = $mod->generateUserTaskRandom($UserIDX,$Task_Type,$Next_Turn);
				$ret_user_task = LibUserTasks::generateUserTaskRandom($UserIDX,$Task_Type,$Turn + 1 );			
				// $ret = $mod->getUserTaskListByTrun($UserIDX,$Task_Type,$Next_Turn,$max_user_task_count,0);
				$ret = LibUserTasks::getUserTaskListByTrun($UserIDX,$Task_Type,$Next_Turn,$max_user_task_count,0);
			}
		}
		// 如果最终什么也没有查到，返回用户最后一轮次的任务列表 
		if(empty($ret)){
			$Turn = $mod->getCurrentUserTaskTurn($UserIDX) ;
			// $ret = $mod->getUserTaskListByTrun($UserIDX,$Task_Type,$Turn,$max_user_task_count,0 );
			$ret = LibUserTasks::getUserTaskListByTrun($UserIDX,$Task_Type,$Turn,$max_user_task_count,0 );
		}

		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
		
	}

	//开始任务
	public function startUserTask($params){
		$UserIDX = $params['UserIDX'];
		$Task_IDX = $params['Task_IDX'];
		$Finish_Score = 0;
		$Finish_Pic = '' ;
		$Finish_Document = '' ;
		$Finish_Status = DictionaryData::User_Task_Status_Start ;
		$Start_Date = time();
		$Finish_Date = '';

		$mod_user_task = new ModUserTask();
		$ret_user_task = $mod_user_task->updateUserTask($UserIDX,$Task_IDX,$Finish_Status,$Finish_Date,$Finish_Score,$Finish_Pic,$Finish_Document);
		if($ret_user_task === false){
			$errno = ConfTask::ERROR_QUEYR_USER_TASK_START ;
			$this->_echoResponse($errno,'',$ret_user_task);
			return;
		}
		$errno = 1 ;
		$this->_echoResponse($errno);
	}

	//结束任务
	public function finishUserTask($params){
		$UserIDX = $params['UserIDX'];
		$Task_IDX = $params['Task_IDX'];
		$Finish_Score = 0;
		$Finish_Pic = '' ;
		$Finish_Document = '' ;
		$Finish_Status = DictionaryData::User_Task_Status_Finish ;
		$Finish_Date = time();

		$mod = new ModTaskMaterial();
		$task_info = $mod->getTaskMeterialDetail($Task_IDX);
		if($task_info === false ||empty($task_info)){			
			$errno = ConfTask::ERROR_QUEYR_TASK_DETAIL ;
			$this->_echoResponse($errno);
			return;
		}
		$Min_Time = $task_info['Min_Time'];


		$mod_user_task = new ModUserTask();
		$ret_user_task = $mod_user_task->updateUserTask($UserIDX,$Task_IDX,$Finish_Status,$Finish_Date,$Finish_Score,$Finish_Pic,$Finish_Document);
		if($ret_user_task === false){
			$errno = ConfTask::ERROR_QUEYR_USER_TASK_START ;
			$this->_echoResponse($errno,'',$ret_user_task);
			return;
		}

		// 先判断此是否已经获得过此任务经验值
		// 根据任务最小需要完成的时间 * 10，做为经验值 发给用户
		$Config_Key = 'Task_Finish_Point';
		$DWType = DictionaryData::User_Experience_DW_Type_Increase ;
		$Amount = $Min_Time * 10 ;
		$DWMemo = '用户完成任务获得经验值';

		LibUserExperience::recordUserExperience($UserIDX,$Config_Key,$DWType,$Amount,$DWMemo);
		// 如果用户所有任务 为当天分配且当天完成，则提示用户，明天再来
		$user_task_info = $mod_user_task->getUserTaskDetail($UserIDX,$Task_IDX);
		if(!empty($user_task_info)){
			$Turn = $user_task_info['Turn'];
			$user_task_list = $mod_user_task->getUserTaskListByTrun($UserIDX, 0 ,$Turn );

			
			if(!empty($user_task_list)){
				$user_finished_task_counts = 0 ;
				$is_today_task_finished = true ;
				foreach ($user_task_list as $key => $value) {
					// var_dump($value['Finish_Date'],$value['Finish_Status']);
					if(empty($value['Finish_Date']) || $value['Finish_Status'] != DictionaryData::User_Task_Status_Finish ){
						$is_today_task_finished = false;
					}else if(!empty($value['Assign_Date']) && !empty($value['Finish_Date'])){
						$Assign_Day = date('Y-m-d',strtotime($value['Assign_Date']));
						$Finish_Day = date('Y-m-d',strtotime($value['Finish_Date']));
						$diff_1 = CommonHelper::getDateDiff($Finish_Day,date('Y-m-d'),'d');
						$diff_2 = CommonHelper::getDateDiff($Assign_Day,$Finish_Day,'d');
						// var_dump($Assign_Day,$Finish_Day,date('Y-m-d'),$diff_1,$diff_2);echo("<br>");
						if($diff_1 == 0  && $diff_2 != 0 ){
							$is_today_task_finished = false;
						}
					}else{
						$is_today_task_finished = false;
					}
					if($value['Finish_Status'] == DictionaryData::User_Task_Status_Finish){
						$user_finished_task_counts++;
					}
					if($user_finished_task_counts >=  DictionaryData::Max_User_Task_Count){
						$is_today_task_finished = true;	//如果用户完成一天内最大数量，不能再做任务 了
					}
				}
				if($is_today_task_finished){
					$data = array('is_all_finished'=>1);
					$errno = 1 ;
					$this->_echoResponse($errno,'',$data);
					return ;
				}
			}
		}
		$errno = 1 ;
		$this->_echoResponse($errno);
		
	}

	/**
	 * 查询所有的评测题集，如果用户登录，返回用户评测题是否已经做过的状态
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
		// $ret = $mod->getEvaluationQuesitonsSetList();
		$UserIDX = isset($params['UserIDX']) ? $params['UserIDX'] : 0 ;
		$ret = $mod->getUserEvaluationQuesitonsSetList($UserIDX);
		if(!empty($ret)){
			$mod_question = new ModEvaluationQuesitons();
			foreach ($ret as $key => &$value) {
				$Set_IDX = $value['IDX'];
				$value['Set_Qty'] = count($mod_question->getEvaluationQuesitonsList($Set_IDX,0,9999));
				$value['Counts'] = (empty($value['Counts'])) ? 0 : intval($value['Counts']);
				$value['Question_Set_IDX'] = (!empty($value['Question_Set_IDX'])) ? $value['Question_Set_IDX'] : $value['IDX'];				 
			}
		}
		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret);
	}
	
	private function getEvaluationQuesitonsSetByIDX($params){
		$Question_Set_IDX 	= $params['Question_Set_IDX'];

		$mod = new ModEvaluationQuesitonsSet();
		$data = array();
		$ret = $mod->getEvaluationQuesitonsSetList($Question_Set_IDX);
		if(!empty($ret)){
			$data = $ret[0];		
			if(isset($params['UserIDX'])){
				$UserIDX = $params['UserIDX'];
				$mod_user_question = new ModUserEvaluationQuesitons();
				$Finish_Point = 0 ;
				if(empty($Unfinish_Qty)){
					$Question_Answer_Status = 1 ; 	//查询用户已经完成的评测题
					$ret_user_question_finished = $mod_user_question->getUserEvaluationQuesitonsList($UserIDX,$Question_Set_IDX,$Question_Answer_Status);				
					if(!empty($ret_user_question_finished)){
						foreach ($ret_user_question_finished as $key => $value) {
							$Point = (is_numeric($value['Point'])) ? $value['Point'] : 0 ;
							$Finish_Point += $Point;
						}
					}
				}
				$data['Finish_Point'] = $Finish_Point;
			}
		}
		$errno = 1 ;
		$this->_echoResponse($errno,'',$data);
	}
	
	/**
	 * ¸根据题集选择评测题
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


	// 账号注册
	private function userRegist($params)
	{
		$phone=$params['phone'] ;
		$password=$params['password'] ;
		// $img_verify_code=$params['img_verify_code'] ;
		$sms_verify_code=$params['sms_verify_code'] ;
		
		// if(empty($phone) || empty($password) || empty($img_verify_code) || empty($sms_verify_code)){
		// 	return $this->_response(-99,"²ÎÊý´íÎó");
		// }
		
		// $imgVerifyCode=$lunaCodeVerify->verifyImageCode($img_verify_code);
		// if($imgVerifyCode!=0){			
		// 	$errno = ConfTask::ERROR_USER_LOGIN_PASSWORD ;
		// 	$this->_echoResponse($errno);
		// 	return;
		// }
		$lunaCodeVerify=LunaCodeVerify::getInstance();
		$smsVerifyCode=$lunaCodeVerify->verifySmsCode($sms_verify_code,$phone);
		if($smsVerifyCode!=0){
			// return $this->_response_error("sms_code", $smsVerifyCode-20);
			$errno = ConfTask::ERROR_USER_SMS_CODE ;
			$this->_echoResponse($errno);
			return;
		}
		$bizAppData= new BizAppData();
		$userInfo=$bizAppData->getUserInfoByLoginName($phone, BizDataDictionary::User_AcctSource_SelfSite);
		$md5password=md5($password);
		if(count($userInfo)>0){
			// $errno = ConfTask::ERROR_USER_EXISTS ;
			// $this->_echoResponse($errno);
			// return;
			// 已注册手机用户，直接重置密码
			if($bizAppData->resetPwd($phone, BizDataDictionary::User_AcctSource_SelfSite, $md5password)){
				$errno = 1 ;
				$this->_echoResponse($errno);
				return;
			}
		}
		
		if($bizAppData->registUserInfo($phone, BizDataDictionary::User_AcctSource_SelfSite, $md5password)){
			$userInfo=$bizAppData->getUserInfoByLoginName($phone, BizDataDictionary::User_AcctSource_SelfSite);
			Yii::app()->session[$this->_USER_SESSION_KEY]=$userInfo;
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
		// 	return $this->_response(-99,"²ÎÊý´íÎó");
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
		$UserIDX = $userInfo[0]['IDX'];
		//登录成功后，判断session中是否有第三方OpenId，且与用户账号是否已经绑定，否则绑定第三方账号
		$third_UserInfo = Yii::app()->session[$this->_OPENID_SESSION_KEY] ; 
		if(empty($third_UserInfo) == false){
			$acctSource = $third_UserInfo['AcctSource'];
			if($acctSource == BizDataDictionary::User_AcctSource_Tencent_Wx){
				if(empty($userInfo[0]['OpenId_Wechat']) && !empty($third_UserInfo['OpenId'])){
					$mod_user_info = new ModUserInfo();
					$ret_user_info = $mod_user_info->bindThirdUserInfo($UserIDX,$third_UserInfo['OpenId'],$acctSource);
				}
			}
			if($acctSource == BizDataDictionary::User_AcctSource_Sina_Wb){
				if(empty($userInfo[0]['OpenId_Weibo']) && !empty($third_UserInfo['OpenId'])){
					$mod_user_info = new ModUserInfo();
					$ret_user_info = $mod_user_info->bindThirdUserInfo($UserIDX,$third_UserInfo['OpenId'],$acctSource);
				}
			}
		}
			
		// $session_code_key="user";
		Yii::app()->session[$this->_USER_SESSION_KEY]=$userInfo;
		$errno = 1 ;
		$this->_echoResponse($errno);
		return ;
	}

	// 用户解绑
	private function userUnBind($params){
		$acctSource=$params['acctSource'] ;
		if($acctSource == 1 ){
			$acctSource = BizDataDictionary::User_AcctSource_Tencent_Wx;
		}else if($acctSource == 2 ){
			$acctSource = BizDataDictionary::User_AcctSource_Sina_Wb;
		}else if($acctSource == 3 ){
			$acctSource = BizDataDictionary::User_AcctSource_Tencent_Qq;
		} 
		$UserIDX =  $params['UserIDX'];
		$mod_user_info = new ModUserInfo();
		$ret = $mod_user_info->unbindThirdUserInfo($UserIDX,$acctSource);
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
	//获取登录用户信息,以及是否已经填写过基础资料 尚未进行评测的时间 天 
	//当日是否已经签到
	//TODO 逻辑待实现
	private  function getUserInfo($params)
	{
		$userInfo=Yii::app()->session[$this->_USER_SESSION_KEY];
		// var_dump($userInfo);die();
		$UserIDX =  $params['UserIDX'];
		$TodaySignIn = 0 ;
		$mod_user_experience = new ModUserExpRevenue();
		$Config_Key = 'SignIn_Exp_Point' ;
		$Query_Date = date('Y-m-d');
		$ret_user_experience = $mod_user_experience->queryUserExperience($UserIDX,$Config_Key,$Query_Date);
		if(count($ret_user_experience)>0){
			$TodaySignIn = 1 ;
		}
		// ÓÃ»§»ù±¾ÐÅÏ¢
		$UserExperiencePoint = 0 ;
		$mod_user_info = new ModUserInfo();
		$ret_user_info = $mod_user_info->queryUserInfo($UserIDX)	;
		
		if(!empty($ret_user_info)){
			$UserExperiencePoint = $ret_user_info['Exp_Points'];
			$User_Sign_Time = $ret_user_info['Create_Time'];
			$UserEvaluationDay = CommonHelper::getDateDiff($User_Sign_Time,date('Y-m-d'));
		}
		// ÓÃ»§ÆÀ²âÐÅÏ¢
		$UserBasicInfo = 0;
		$mod_user_evaluation = new ModUserEvaluationQuesitons();
		$ret_user_evaluation = $mod_user_evaluation->getEvaluationQuesitonsList($UserIDX,999,0);
		if(!empty($ret_user_evaluation)){
			$UserBasicInfo = 1 ;
			$UserEvaluationDay = 0 ;
		}
		// ÓÃ»§ÈÎÎñÐÅÏ¢
		$mod_user_task = new ModUserTask();
		$Task_Type = 0 ;
		$Query_Day = 0 ;
		$ret_user_task = $mod_user_task->getUserTaskList($UserIDX,$Task_Type ,$Query_Day,$page_size = 999 ,$offSet = 0);
		$Finish_Task_Minutes = 0 ;
		$Finish_Task_Qty = 0 ;
		$UnFinish_Task_Minutes = 0 ;
		$UnFinish_Task_Qty = 0 ;
		if(!empty($ret_user_task)){
			foreach ($ret_user_task as $key => $value) {
				$Finish_Status = $value['Finish_Status'];
				$Start_Date = $value['Start_Date'];
				$Finish_Date = $value['Finish_Date'];
				$Min_Time = $value['Min_Time'];
				$Max_Time = $value['Max_Time'];
				if($Finish_Status == DictionaryData::User_Task_Status_Finish){
					$diff_mi = CommonHelper::getDateDiff($Start_Date,$Finish_Date,'mi');
					$Finish_Task_Minutes += intval($diff_mi);
					$Finish_Task_Qty++;
				// }else if($Finish_Status == DictionaryData::User_Task_Status_Not_Yet || $Finish_Status == DictionaryData::User_Task_Status_Start ){
				}else{
					$UnFinish_Task_Minutes += $Max_Time;
					$UnFinish_Task_Qty++;
				}


			}
		}
		$ret = array(
			"UserIDX"			=>	$userInfo[0]["IDX"],
			"User_Info"        => array(
                "LoginName"            =>    $userInfo[0]["LoginName"],               
                "UserExperiencePoint"  =>    $UserExperiencePoint,
                "UserLevel"            =>    TaskHelper::getUserLevelByExpPoints($UserExperiencePoint),
                "TodaySignIn"          =>    $TodaySignIn,
                ),
            "User_Evaluation_Info"    =>    array(
                "UserBasicInfo"        =>    $UserBasicInfo,
                "UserEvaluationDay"    =>    $UserEvaluationDay,
                ),
            "User_Task_Info"     =>    array(
                'Finish_Task'    =>    array(
                    'Qty'        => $Finish_Task_Qty,
                    'Minutes'    => $Finish_Task_Minutes ,
                    ),
                'UnFinish_Task'    =>    array(
                    'Qty'        => $UnFinish_Task_Qty,
                    'Minutes'    => $UnFinish_Task_Minutes ,
                    ),
                ),

		);
		$UserIDX = $ret['UserIDX'];
		$mod_user_basicinfo = new ModUserBasicInfo();
		$ret_user_basicinfo = $mod_user_basicinfo->queryUserBasicInfo($UserIDX);
		if(empty($ret_user_basicinfo) == false){
			$ret['User_Evaluation_Info']['UserBasicInfo'] = 1 ;
		}
		$UserIDX = $ret['UserIDX'];		
		$mod_user_evaluation = new ModUserEvaluationQuesitons();
		$ret_user_evaluation = $mod_user_evaluation->getEvaluationQuesitonsList($UserIDX);
		if(empty($ret_user_evaluation)){
			$mod_user_info = new ModUserInfo();
			$ret_user_info = $mod_user_info->queryUserInfo($UserIDX);
			if(empty($ret_user_info) == false){
				$user_register_date = $ret_user_info['CreateTime'];
				$diff = CommonHelper::getDateDiff($user_register_date,date('Y-m-d'));
				$ret['User_Evaluation_Info']['UserEvaluationDay'] = $diff ;
			}				
		}

		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret);
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

	// 修改密码
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
	 *  先判断此题集是否已经分配给用户，是则直接取，否则分配后再取题 
	 * 获取用户下一题
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	private function getUserNextQuestion($params){		
		$UserIDX = $params['UserIDX'];
		$Question_Set_IDX = $params['Question_Set_IDX'];

		$mod = new ModUserEvaluationQuesitons();
		$ret_query_user_question = $mod->getUserEvaluationQuesitonsList($UserIDX,$Question_Set_IDX,'');

		if(empty($ret_query_user_question)){//将此评测题分配给用户
			$ret_user_question = $mod->generateUserQuestion($UserIDX,$Question_Set_IDX);
		}
		$ret = $mod->getNextQuestion($UserIDX,$Question_Set_IDX);
		if($ret === false){			
			$errno = ConfTask::ERROR_QUESTION_GET_NEXT ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}

		$Option_List = array();
		if(!empty($ret['Option_A'])){
			$Option_List['A'] = $ret['Option_A'];
		}
		if(!empty($ret['Option_B'])){
			$Option_List['B'] = $ret['Option_B'];
		}
		if(!empty($ret['Option_C'])){
			$Option_List['C'] = $ret['Option_C'];
		}
		if(!empty($ret['Option_D'])){
			$Option_List['D'] = $ret['Option_D'];
		}
		if(!empty($ret['Option_E'])){
			$Option_List['E'] = $ret['Option_E'];
		}
		if(!empty($ret['Option_F'])){
			$Option_List['F'] = $ret['Option_F'];
		}
		if(empty($ret)){
			$Unfinish_Qty = 0 ; 
		}else{
			$Question_Answer_Status = 0 ; 	//查询用户尚未完成的评测题数量
			$ret_user_question = $mod->getUserEvaluationQuesitonsList($UserIDX,$Question_Set_IDX,$Question_Answer_Status);
			$Unfinish_Qty = count($ret_user_question);
		}
		unset($ret['Option_A']);
		unset($ret['Option_B']);
		unset($ret['Option_C']);
		unset($ret['Option_D']);
		unset($ret['Option_E']);
		unset($ret['Option_F']);
		$ret['Unfinish_Qty'] = $Unfinish_Qty;
		$ret['Option_List'] = $Option_List;


		$mod_question = new ModEvaluationQuesitons();
		$ret['Set_Qty'] = count($mod_question->getEvaluationQuesitonsList($Question_Set_IDX,0,9999));

		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 

	}

	/**
	 * 提交评测题答案
	 * TODO 全部评测结束 ，根据评测结果分配任务
	 * 当完成最后一题时，返回评测得分结果
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	private function recordUserQuestionResult($params){
		$UserIDX = $params['UserIDX'];
		$Question_IDX = $params['Question_IDX'];
		$Option = trim($params['Option']);
		$mod_question = new ModEvaluationQuesitons();
		$ret_question = $mod_question->getEvaluationQuesitons($Question_IDX);
		if(empty($ret_question)){
			$errno = ConfTask::ERROR_USER_QUESTION_GET ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}
		$Question_Set_IDX = $ret_question['Question_Set_IDX'];
	
		if($Option == "A"){
			$Point = $ret_question['Point_A'];
		}else  if($Option == "B"){
			$Point = $ret_question['Point_B'];
		}else  if($Option == "C"){
			$Point = $ret_question['Point_C'];
		}else  if($Option == "D"){
			$Point = $ret_question['Point_D'];
		}else  if($Option == "E"){
			$Point = $ret_question['Point_E'];
		}else  if($Option == "F"){
			$Point = $ret_question['Point_F'];
		}
		$mod_user_question = new ModUserEvaluationQuesitons();
		$ret_user_question = $mod_user_question->recordUserQuestionResult($UserIDX,$Question_IDX,$Option,$Point);
		if($ret_user_question === false){
			$errno = ConfTask::ERROR_USER_QUESTION_RECORD ;
			$this->_echoResponse($errno);
			return;
		}
		//判断是否已经全部答完，是否则返回得分，并同时返回剩余题目数
		$Question_Answer_Status = 0 ; 	//查询用户尚未完成的评测题数量
		$ret_user_question = $mod_user_question->getUserEvaluationQuesitonsList($UserIDX,$Question_Set_IDX,$Question_Answer_Status);
		$Unfinish_Qty = count($ret_user_question);

		$Finish_Point = 0 ;
		if(empty($Unfinish_Qty)){
			$Question_Answer_Status = 1 ; 	//查询用户已经完成的评测题
			$ret_user_question_finished = $mod_user_question->getUserEvaluationQuesitonsList($UserIDX,$Question_Set_IDX,$Question_Answer_Status);
			
			if(!empty($ret_user_question_finished)){
				foreach ($ret_user_question_finished as $key => $value) {
					$Point = (is_numeric($value['Point'])) ? $value['Point'] : 0 ;
					$Finish_Point += $Point;
				}
			}
		}
		$data = array();
		if($Unfinish_Qty == 0 ){
			$data = array('Finish_Point' => $Finish_Point);
			// 根据评测结果，自动分配任务给用户
			LibUserTasks::generateUserTaskEva($UserIDX,$Question_Set_IDX);
		}
		$errno = 1 ;
		$this->_echoResponse($errno,'',$data); 
	}

	//用户签到 ,增加经验值
	public function userSignIn($params){
		$UserIDX = $params['UserIDX'];
		$Config_Key = 'SignIn_Exp_Point';
		$DWType = DictionaryData::User_Experience_DW_Type_Increase ;
		$Amount = LibUserExperience::getValueByConfigKey($Config_Key);
		$DWMemo = '用户签到获得经验值';

		LibUserExperience::recordUserExperience($UserIDX,$Config_Key,$DWType,$Amount,$DWMemo);
		
		$errno = 1 ;
		$this->_echoResponse($errno);
	}


	/**
	 * ·分享 TODO 分享积分
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	private function shareOut($params){
		$UserIDX = $params['UserIDX'];
		$Share_Type = $params['Share_Type'];
		$Share_IDX = $params['Share_IDX'];
		$Share_To = $params['Share_To'];
		$Amount = 1 ;	

		$mod_user_share = new ModUserShare();
		$ret_user_share = $mod_user_share->addUserShare($UserIDX,$Share_Type,$Share_IDX,$Share_To,$Amount);
		if($ret_user_share === false){
			$errno = ConfTask::ERROR_USER_SHARE ;
			$this->_echoResponse($errno,'',$ret_user_share);
			return;
		}
		$errno = 1 ;
		$this->_echoResponse($errno);
	}

	// 任务评价
	private function taskEvaluate($params){
		$UserIDX = $params['UserIDX'];
		$Task_IDX = $params['Task_IDX'];
		$Finish_Score = $params['Finish_Score'];
		$Finish_Pic = isset($params['Finish_Pic']) ?$params['Finish_Pic'] : '' ;
		$Finish_Document = isset($params['Finish_Document']) ?$params['Finish_Document'] : '' ;

		$mod_user_task = new ModUserTask();
		$ret_user_task = $mod_user_task->updateUserTask($UserIDX,$Task_IDX,'','',$Finish_Score,$Finish_Pic,$Finish_Document);
		if($ret_user_task === false){
			$errno = ConfTask::ERROR_QUEYR_USER_TASK_EVALUATION ;
			$this->_echoResponse($errno,'',$ret_user_task);
			return;
		}
		$errno = 1 ;
		$this->_echoResponse($errno);
	}
	// 查询任务评价信息
	private function queryTaskEvaluteList($params){
		$Task_IDX = $params['Task_IDX'];
		$mod_user_task = new ModUserTask();
		$ret_user_task = $mod_user_task->queryTaskEvaluteList($Task_IDX,1);
		if($ret_user_task === false){
			$errno = ConfTask::ERROR_QUEYR_USER_TASK_EVALUATION ;
			$this->_echoResponse($errno,'',$ret_user_task);
			return;
		}
		// 返回任务平均分
		$ret_avg_tak = $mod_user_task->queryTaskAvgScore($Task_IDX);
		$task_avg_score = 0 ;		
		if(!empty($ret_avg_tak)){
			$task_avg_score = $ret_avg_tak['score'];
		}

		$data = array(
			'list'	=> $ret_user_task,
			'task_avg_score'	=> $task_avg_score,
			);
		$errno = 1 ;
		$this->_echoResponse($errno,'',$data);
	}

	// 按月查询用户已完成任务数
	public function queryUserTaskMonth($params){
		$UserIDX = $params['UserIDX'];
		$Query_Month = $params['Query_Month'];
		$Finish_Status = $params['Finish_Status'];
		$mod_user_task = new ModUserTask();
		$Start_Date = CommonHelper::getDateMonthFirstDay($Query_Month);
		$End_Date = CommonHelper::getDateMonthLastDay($Query_Month);
		$Month_Days = date('t',strtotime($Start_Date));
		$ret_user_tasks = $mod_user_task->queryUserTaskMonth($UserIDX,$Query_Month,$Finish_Status);
		if($ret_user_tasks === false){
			$errno = ConfTask::ERROR_QUEYR_USER_TASK_LIST ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}

		$data = array();
		for($i = 0 ; $i < $Month_Days ; $i++ ){
			$Task_Date  = date('Y-m-d', strtotime("$Start_Date +$i day")) ;
			$Task_Qty = 0 ;
			for($j = 0 ; $j < count($ret_user_tasks) ; $j++){
				if($Task_Date == $ret_user_tasks[$j]['Finish_Date']){
					$Task_Qty = $ret_user_tasks[$j]['Task_Qty'];
				}

			}
			$data[] = array(
				'Task_Date' => $Task_Date,
				'Task_Qty'	=> $Task_Qty
				);
		}
		$errno = 1 ;
		$this->_echoResponse($errno,'',$data);

	}

	// 按日查询用户已完成任务列表
	public function queryUserTaskDay($params){
		$UserIDX = $params['UserIDX'];
		$Query_Day = $params['Query_Day'];
		$Task_Type = isset($params['Task_Type']) ? $params['Task_Type'] : '';
		$mod_user_task = new ModUserTask();		
		$ret = $mod_user_task->getUserTaskList($UserIDX,$Task_Type,$Query_Day,999,0,9);
		if($ret === false){			
			$errno = ConfTask::ERROR_QUEYR_USER_TASK_LIST ;
			$this->_echoResponse($errno,'',$ret);
			return;
		}

		$errno = 1 ;
		$this->_echoResponse($errno,'',$ret); 
		
	}

	//获取微信授权URL
	public function getWechatRedirectUrl($params){
		$redirect_uri = "http://api.fumuwin.com/test/wxIndex?v=1";	//http%3A%2F%2Fapi.fumuwin.com%2Fsite%2FwxIndex%3Fv%3D1
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".WxHelper::WX_APP_ID."&redirect_uri=". $redirect_uri ."&response_type=code&scope=snsapi_userinfo&state=2&connect_redirect=1#wechat_redirect";
		$data = array('url'=>$url);
		$errno = 1 ;
		$this->_echoResponse($errno,'',$data);

	}

	//微信登录、注册 ，必须绑定系统手机账号
	public function getWeiboRedirectUrl($params){
		$redirect_uri = "http://api.fumuwin.com/site/wbLogin";	//http%3A%2F%2Fapi.fumuwin.com%2Fsite%2FwxIndex%3Fv%3D1
		// $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".WxHelper::WX_APP_ID."&redirect_uri=". $redirect_uri ."&response_type=code&scope=snsapi_userinfo&state=2&connect_redirect=1#wechat_redirect";
		$url = 'https://api.weibo.com/oauth2/authorize?client_id='.WbHelper::WB_APP_ID.'&response_type=code&redirect_uri='. $redirect_uri .'';
		$data = array('url'=>$url);
		$errno = 1 ;
		$this->_echoResponse($errno,'',$data);

	}

	//微信静默登录
	public function actionWechatLogin(){
		$code			=	Yii::app()->request->getParam('code',"");
		$wxUser=WxHelper::getOpenId($code);
		if($wxUser==false){
			$errno = ConfTask::ERROR_WECHAT_CODE ;
			$this->_echoResponse($errno);
			return;
		}
		$acctSource		  = BizDataDictionary::User_AcctSource_Tencent_Wx;
		$mod_user_info = new ModUserInfo();

		$ret_user_info = $mod_user_info->getUserInfoByOpenId($wxUser["openid"],$acctSource);
		if(!empty($ret_user_info)){
			Yii::app()->session[$this->_USER_SESSION_KEY]=$ret_user_info;	
					
		}
		$errno = 1 ;
		$this->_echoResponse($errno);
		

	}

	//微博登录，绑定系统手机账号,保存session
	public function actionWechatBind(){
		$code			=	Yii::app()->request->getParam('code',"");
		$wxUser=WxHelper::getOpenId($code);
		if($wxUser==false){
			$errno = ConfTask::ERROR_WECHAT_CODE ;
			$this->_echoResponse($errno);
			return;
		}
		$acctSource		  = BizDataDictionary::User_AcctSource_Tencent_Wx;
		$third_UserInfo = array();
		$third_UserInfo["AcctSource"]	=	$acctSource;
		$third_UserInfo["OpenId"]	=	$wxUser["openid"] ;
		$third_UserInfo["OpenUserInfo"]=  WxHelper::getOpenIdUserInfo($wxUser["openid"]);

		Yii::app()->session[$this->_OPENID_SESSION_KEY]=$third_UserInfo;

		$mod_user_info = new ModUserInfo();

		$ret_user_info = $mod_user_info->getUserInfoByOpenId($wxUser["openid"],BizDataDictionary::User_AcctSource_Tencent_Wx);
		if(!empty($ret_user_info)){
			Yii::app()->session[$this->_USER_SESSION_KEY]=$ret_user_info;	
			$errno = 1 ;
			$url = 'http://m.fumuwin.com/';
			$data = array('url'=>$url);
			$this->_echoResponse($errno,'',$data);
			return ;		
		}

		$userInfo=Yii::app()->session[$this->_USER_SESSION_KEY];
		if(isset($userInfo)){
			$LoginName = $userInfo[0]["LoginName"];
			$UserIDX = $userInfo[0]["IDX"];			
			if(empty($third_UserInfo["OpenId"])){		
				$ret_user_info = $mod_user_info->bindThirdUserInfo($UserIDX,$third_UserInfo['OpenId'],$acctSource);
				$errno = 1 ;
				$url = 'http://m.fumuwin.com/';
				$data = array('url'=>$url);
				$this->_echoResponse($errno,'',$data);
				return ;
			}
		}
		$url = ('http://m.fumuwin.com/#/signup?bind=weixin');
		$data = array('url'=>$url);
		$errno = 1 ;
		$this->_echoResponse($errno,'',$data);

	}
	//微博绑定
	public function actionWbBind(){
		$code			=	Yii::app()->request->getParam('code',"");

		$wbUser=WbHelper::getOpenId($code);
		if($wbUser==false){
			$errno = ConfTask::ERROR_WEIBO_CODE ;
			$this->_echoResponse($errno);
			return;
		}
		$acctSource		  = BizDataDictionary::User_AcctSource_Sina_Wb;
		$third_UserInfo = array();
		$third_UserInfo["AcctSource"]	=	$acctSource ;
		$third_UserInfo["OpenId"]	=	$wbUser["uid"] ;
		$third_UserInfo["OpenUserInfo"]=  WbHelper::getOpenIdUserInfo($wbUser["uid"]);
		Yii::app()->session[$this->_OPENID_SESSION_KEY]=$third_UserInfo;

		$mod_user_info = new ModUserInfo();
		$ret_user_info = $mod_user_info->getUserInfoByOpenId($wbUser["uid"],BizDataDictionary::User_AcctSource_Sina_Wb);
		if(!empty($ret_user_info)){
			Yii::app()->session[$this->_USER_SESSION_KEY]=$ret_user_info;	
			$errno = 1 ;
			$url = 'http://m.fumuwin.com/';
			$data = array('url'=>$url);
			$this->_echoResponse($errno,'',$data);
			return ;		
		}

		$userInfo=Yii::app()->session[$this->_USER_SESSION_KEY];
		if(isset($userInfo)){
			$LoginName = $userInfo[0]["LoginName"];
			$UserIDX = $userInfo[0]["IDX"];			
			if(empty($third_UserInfo["OpenId"])){		
				$ret_user_info = $mod_user_info->bindThirdUserInfo($UserIDX,$third_UserInfo['OpenId'],$acctSource);
				$errno = 1 ;
				$url = 'http://m.fumuwin.com/';
				$data = array('url'=>$url);
				$this->_echoResponse($errno,'',$data);
				return ;
			}
		}
		$url = ('http://m.fumuwin.com/#/signup?bind=weixin');
		$data = array('url'=>$url);
		$errno = 1 ;
		$this->_echoResponse($errno,'',$data);

	}

	//微信 token
	public function wechatToken($params){

	}

	//微信  登录 
	public function wechatSign($params){
		$url =	Yii::app()->request->getParam('url',"");
		
		$data =	WxHelper::getJSSDKData($url);
		$errno = 1 ;
		$this->_echoResponse($errno,'',$data);
	}


	// 上传文件 
	public function actionUploadFile(){
		if(isset($_FILES) && is_array($_FILES) && count($_FILES)>0){
			foreach ($_FILES as $uploadedFile){
				if(empty($uploadedFile["name"])==false){
					$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
					// var_dump($appConfig);die();
					$imgName=CGuidManager::GetFullGuid().".jpg";
					$img_path=$appConfig["UploadImage_Root"]."/".$imgName;
					if(copy($uploadedFile["tmp_name"],$img_path)){
						$img_url=$appConfig["UploadImage_Domain"]."/".$imgName;
						// echo json_encode(array(
						// 	"return_code"	=>	0,
						// 	"url"			=>	$img_url,
						// ));
						$errno = 1 ;
						$this->_echoResponse($errno,'',array("url" => $img_url));
						return;
					}
				}
			}
		}	
		$errno = ConfTask::ERROR_FILE_UPLOAD ;
		$this->_echoResponse($errno);
		return;
	}

	public function actionTest(){
		var_dump("test action");
	}
}