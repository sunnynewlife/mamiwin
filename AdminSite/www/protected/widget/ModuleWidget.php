<?php
Yii::import('model.Module');
class ModuleWidget extends CWidget
{
	public $current_module = 0;
	public function run() {
		$modules = Module::getInstance()->getAllModule();
		if (trim(Yii::app()->request->getParam('module_id')))
		 	 $this->current_module = Yii::app()->request->getParam('module_id');
		 
		
		$data['modules'] = $modules;
		$data['current_module'] = $this->current_module;
       	$this->render('module',$data);        
    }
}

