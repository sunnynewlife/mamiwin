<?php
LunaLoader::import('luna_lib.hps.HpsModel');
/**
 * 
 * @author wangbin10
 *
 *  mid+appid，
 *  查最近登录deviceid http://service.sdo.com/docenter/?api=querylastlogineddeviceid】
 *  获取deviceid，其中appid=791000028为混沌之理，
 *  如果返回结果为非0则表示没有登录过混沌之理不符合领奖条件
 * （如果返回的deviceid为empty或null，则同样不符合领奖资格）
 *
 */
class AcctQueryLastLoginedDeviceid  extends HpsModel {
	
	public static function model($className=__CLASS__){
		$param=array(
				//MID数字账号
				"mid" => array(HpsModel::PARAM_KEY_REQUIRED =>true),

				//盛大统一编码
				"appId" => array(HpsModel::PARAM_KEY_REQUIRED =>true),
				
				
		);
		return parent::model("/mobilegame/queryContinuousLogin",$param,true,$className);
	}	
}

?>