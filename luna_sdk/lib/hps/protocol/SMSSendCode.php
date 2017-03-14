<?php

LunaLoader::import('luna_lib.hps.HpsModel');
class SMSSendCode extends HpsModel {
	
	/**
	 * @param string  $className
	 * @return object Model的实例
	 */	
	public static function model($className=__CLASS__){
		$param=array(
			"mobile" => array(HpsModel::PARAM_KEY_REQUIRED =>true),
		);
		return parent::model("/dbbroke/validcode/sendCode",$param,true,$className);
	}	
}