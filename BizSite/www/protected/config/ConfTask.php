<?php
class ConfTask {
	const DB_CONF_FUNC = 'CollectDbconfig';

	const DEFAULT_FACE_PIC	= "http://avatarimg.ymq.me/";				//选手默认头像
	
	//错误码定义
	const ERROR_OK = 0;
	const ERROR_OK_SCORE = "1";
	const ERROR_PARAMS = - 1;
	const ERROR_GET_PDO = - 2;
	const ERROR_INSERT_DB = - 3;
	const ERROR_UPDATE_DB = - 4;
	const ERROR_DELETE_DB = - 5;
	const ERROR_GET_DB = - 6;
	const ERROR_METHOD = - 7;
	const ERROR_PARAM_ILLEGAL = - 8;			//参数不合法
	const ERROR_FROM_DATA_NOT_EXIST = -9;
	const ERROR_ACTIVITY_DATA_NOT_EXIST = - 10;
	const ERROR_FROM_ACTIVITY_DATA_NOT_EXIST = - 11;
	const ERROR_VERIFY_TOKEN				 = - 12;

	const ERROR_ERROR 						= -101000000;
	const ERROR_QUEYR_TASK_DETAIL 			= -101000001;
	const ERROR_USER_BASICINFO_ADD 			= -101000002;

	const ERROR_QUEYR_USER_TASK_LIST 			= -101000003;
	const ERROR_QUEYR_USER_TASK_DETAIL 			= -101000004;
	const ERROR_QUEYR_USER_TASK_START 			= -101000005;
	const ERROR_QUEYR_USER_TASK_FINISH 			= -101000006;	

	const ERROR_QUESTION_GET_NEXT			= -201000001 ;
	const ERROR_QUESTION_GET_NEXT_EMPTY		= -201000002 ;
	const ERROR_USER_QUESTION_GET			= -201000003 ;
	const ERROR_USER_QUESTION_RECORD		= -201000004 ;
	


	const ERROR_USER_SHARE				= -401000001 ;

	const ERROR_QUEYR_USER_TASK_EVALUATION	= -501000001 ;
	
	const ERROR_FILE_UPLOAD 	 			= -800100001;

	const ERROR_USER_LOGIN 	 			= -901000001;
	const ERROR_USER_REGIST 			= -901000002;
	const ERROR_USER_LOGIN_PASSWORD 	= -901000003;
	const ERROR_USER_LOGIN_USER 		= -901000004;
	const ERROR_USER_EXISTS				= -901000005;
	const ERROR_USER_NOT_LOGIN			= -901000006;
	const ERROR_USER_IMAGE_CODE			= -901000007;
	const ERROR_USER_SMS_CODE			= -901000008;
	const ERROR_SMS_SEND				= -901000009;
	const ERROR_USER_PHONE_REGIST		= -901000010;
	const ERROR_USER_PASSWORD_RESET		= -901000011;
	const ERROR_WECHAT_CODE				= -902000001;
	const ERROR_THIRD_USER_REGIST		= -902000002;





	static $errorArray = array(
		self::ERROR_OK => "成功",
		self::ERROR_OK_SCORE => "成功",
		self::ERROR_ERROR 		=> "失败失败",
		self::ERROR_VERIFY_TOKEN	=> "用户信息校验失败",
		self::ERROR_PARAMS 			=> "Params Error", 
		self::ERROR_GET_PDO 		=> "Get Pdo Error", 
		self::ERROR_INSERT_DB 		=> "DB Insert Error", 
		self::ERROR_UPDATE_DB 		=> "DB Update Error", 
		self::ERROR_DELETE_DB 		=> "DB Delete Error", 
		self::ERROR_GET_DB 			=> "DB Get Error", 
		self::ERROR_METHOD 				=> "未知接口",
		self::ERROR_PARAM_ILLEGAL	=> " 参数不合法" ,
		self::ERROR_FROM_DATA_NOT_EXIST => "From Data Not Exist In DB",
		self::ERROR_ACTIVITY_DATA_NOT_EXIST => "Activity Data Not Exist In DB", 
		self::ERROR_FROM_ACTIVITY_DATA_NOT_EXIST => "Fromid Activity Data Not Exist In View", 

		self::ERROR_QUESTION_GET_NEXT			=>	"查询用户下一题失败",
		self::ERROR_QUESTION_GET_NEXT_EMPTY		=>	"没有下一题，全部答完",	
		self::ERROR_USER_QUESTION_GET			=>	"未找到此评测题",
		self::ERROR_USER_QUESTION_RECORD		=>	"记录评测题失败",	
	

		self::ERROR_QUEYR_TASK_DETAIL		=>	"查询资料失败",
		self::ERROR_USER_BASICINFO_ADD		=>	"用户基础资料录入失败" ,

		self::ERROR_QUEYR_USER_TASK_LIST		=>	"查询用户任务列表失败",
		self::ERROR_QUEYR_USER_TASK_DETAIL		=>	"查询用户任务详情失败",
		self::ERROR_QUEYR_USER_TASK_START		=>	"开始用户任务失败",
		self::ERROR_QUEYR_USER_TASK_FINISH		=>	"完成用户任务失败",
		
		self::ERROR_QUEYR_USER_TASK_EVALUATION	=>	"任务评价失败",

		self::ERROR_USER_SHARE					=> 	"分享失败",

		self::ERROR_FILE_UPLOAD				=> 	"文件上传失败",
		self::ERROR_USER_LOGIN				=>	"用户登录失败" ,
		self::ERROR_USER_REGIST				=>	"用户注册失败" ,
		self::ERROR_USER_LOGIN_PASSWORD		=>	"账号或者密码错误" ,
		self::ERROR_USER_LOGIN_USER			=>	"账号异常，不能登录" ,
		self::ERROR_USER_EXISTS 			=>	"手机号已注册" ,
		self::ERROR_USER_NOT_LOGIN 			=>	"您尚未登录" ,
		self::ERROR_USER_IMAGE_CODE			=>	"图片验证码不正确" ,
		self::ERROR_USER_SMS_CODE			=>	"短信验证码不正确" ,
		self::ERROR_SMS_SEND				=>	"短信发送失败" ,
		self::ERROR_USER_PHONE_REGIST		=>	"手机号未注册" ,
		self::ERROR_USER_PASSWORD_RESET		=>	"密码重设失败" ,
		self::ERROR_WECHAT_CODE				=>	"微信CODE不正确" ,
		self::ERROR_THIRD_USER_REGIST		=>	"记录第3方账号出错" ,



	);

	static function transError($errno) {
		if(isset(self::$errorArray[$errno])) {
			return self::$errorArray[$errno];
		}
		
		return 'Unknown Error';
	}
}
?>