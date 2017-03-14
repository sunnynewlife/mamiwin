<?php

LunaLoader::import('luna_lib.hps.HpsModel');
class SMSValidCode  extends HpsModel {
	
	/**
	 * @param string  $className
	 * @return object Model的实例
	 */	
	public static function model($className=__CLASS__){
		$param=array(
			"key" 	=> array(HpsModel::PARAM_KEY_REQUIRED =>true),
			"vcode" => array(HpsModel::PARAM_KEY_REQUIRED =>true),
		);
		return parent::model("/dbbroke/validCode/valid",$param,true,$className);
	}	
}