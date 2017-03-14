<?php
require_once(dirname(__FILE__).'/../libraries/LibValidateCode.php');

class InterfaceController extends CController 
{
	public function actionTest(){
		$vc = new LibValidateCode();
		$vc->doimg();
		var_dump($vc->getCode());
	}
}