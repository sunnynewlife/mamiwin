<?php
LunaLoader::import('luna_lib.hps.HpsModel');

class AcctWOAValidate  extends HpsModel {
	
	public static function model($className=__CLASS__){
		$param=array(
				//应用编号
				"appId" => array(HpsModel::PARAM_KEY_REQUIRED =>true),

				//区域编号
				"areaId" => array(HpsModel::PARAM_KEY_CONST =>true,
						HpsModel::PARAM_KEY_VALUE => "-1"),
				
				//登录票据
				"sessionId" => array(HpsModel::PARAM_KEY_REQUIRED =>true),
				
				//是否要注册成盛大账号
				"needRegister" => array(HpsModel::PARAM_KEY_CONST =>true,
						HpsModel::PARAM_KEY_VALUE => "0"),
				
		);
		return parent::model("/woa/autologin/validate.shtm",$param,true,$className);
	}	
}

?>