<?php
class PostionWidget extends CWidget
{
	public  $current_module_id =1;
	public function run() {
		$module = Module::getInstance()->getModuleList();
		//var_dump($module);
		foreach ($module as &$val)
		{
			$val['menu_list'] = Menu::getInstance()->getMenuByModuleId($val['module_id']);
		}
	
		$data['module'] = $module;
		$data['current_module_id'] = 1;
       $this->render('sidebar',$data);        
    }
}

