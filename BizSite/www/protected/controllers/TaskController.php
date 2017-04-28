<?php
LunaLoader::import("luna_lib.verify.LunaCodeVerify");
require_once(dirname(__FILE__).'/../config/ConfTask.php');
require_once(dirname(__FILE__).'/../libraries/LibUserQuestions.php');


class TaskController extends CController 
{
	// 上传图片
	public function actionUploadFile($params){
		if(isset($_FILES) && is_array($_FILES) && count($_FILES)>0){
			foreach ($_FILES as $uploadedFile){
				if(empty($uploadedFile["name"])==false){
					$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
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
		$this->_echoResponse($errno,'',$ret);
		return;
	}
}