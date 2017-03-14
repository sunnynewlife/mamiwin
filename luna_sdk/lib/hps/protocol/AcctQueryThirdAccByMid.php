<?php
LunaLoader::import('luna_lib.hps.HpsModel');
/**
 * 第三方归属
 *
 */
class AcctQueryThirdAccByMid  extends HpsModel {
	
	public static function model($className=__CLASS__){
		$param=array(
				//MID数字账号
				"mid" => array(HpsModel::PARAM_KEY_REQUIRED =>true),

				//盛大统一编码
				"appId" => array(HpsModel::PARAM_KEY_REQUIRED =>true),
				
				//区域编号
				"areaId" => array(HpsModel::PARAM_KEY_REQUIRED =>true,
						HpsModel::PARAM_KEY_DEFAULTVALUE => "-1"),
				
				//客户端IP
				"clientIp" => array(HpsModel::PARAM_KEY_REQUIRED =>true),

				//服务端IP
				"serverIp" => array(HpsModel::PARAM_KEY_REQUIRED =>true),
				
		);
		return parent::model("/mcas/queryThirdAccByMid",$param,true,$className);
	}	
}

?>