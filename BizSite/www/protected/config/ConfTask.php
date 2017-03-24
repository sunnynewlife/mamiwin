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
	


	
	
	//权限相关
	const ERROR_CLUB_GAME_RIGHT					= -301000001 ;




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

		self::ERROR_QUEYR_TASK_DETAIL		=>	"查询资料失败",
		
		self::ERROR_CLUB_GAME_RIGHT 					=>	"您没有此操作权限" ,


	);

	static function transError($errno) {
		if(isset(self::$errorArray[$errno])) {
			return self::$errorArray[$errno];
		}
		
		return 'Unknown Error';
	}
}
?>