<?php
LunaLoader::import('luna_lib.hps.HpsModel');

class Txt2BValidate  extends HpsModel {
	
	public static function model($className=__CLASS__){
		$param=array(
				"ticket" => array(HpsModel::PARAM_KEY_REQUIRED =>true),
				"SequenceId" => array(HpsModel::PARAM_KEY_REQUIRED =>true),
				"serviceId" => array(HpsModel::PARAM_KEY_REQUIRED =>true),
				
				"version" => array(HpsModel::PARAM_KEY_REQUIRED =>false),
				"netFlag" => array(HpsModel::PARAM_KEY_REQUIRED =>false),
				
				"NeedMid" => array(HpsModel::PARAM_KEY_CONST =>true,HpsModel::PARAM_KEY_VALUE => "0"),
		);
		return parent::model("/fk/yaoshi/verify_main/verifymain",$param,true,$className);
	}	
}

?>