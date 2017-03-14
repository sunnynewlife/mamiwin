<?php

class ModuleController extends Controller 
{
	public function actionIndex()
	{  	
		$modules = Module::getInstance()->getAllModule();
		$this->renderData['modules'] = $modules;
	  	$this->render('index',$this->renderData);
	}
	
	public function actionAdd()
	{
		$submit = Yii::app()->request->getParam('submit','');
		if ($submit)
		{
				$data["module_name"] = trim(Yii::app()->request->getParam('module_name'));
				$data["module_url"]  = trim(Yii::app()->request->getParam('module_url'));
				$data["module_icon"] = trim(Yii::app()->request->getParam('module_icon'));
				$data["module_sort"]= trim(Yii::app()->request->getParam('module_sort'));
				$data ["module_desc"]= trim(Yii::app()->request->getParam('module_desc'));
				try {
					$ret = Module::getInstance()->insertModule($data);
					if($ret)
					{
						$this->exitWithSuccess("添加菜单模块成功", '/module/index');
					}
				} catch (Exception $e) {
					$this->alert('error','添加菜单模块失败','/module/add');
				}
		}
		$this->render('add');	
	}
	
	public function actionMenu()
	{
		$submit = Yii::app()->request->getParam('submit',0);
		$menu_ids = Yii::app()->request->getParam('menu_ids',array());
		$module = Yii::app()->request->getParam('module',array());
		if ($submit)
		{
			if(count($menu_ids) && $module){
			 	$ret = Menu::getInstance()->changeModuleId($menu_ids,$module);
				if($ret)
				{
					$this->exitWithError("修改菜单模块成功", '/module/menu?module_id='.$module);
				}else {
					$this->alert('error',"修改菜单模块失败！");
				}
			}else {
				$this->alert('error',"参数错误！");
			}
		}
		$module_id = Yii::app()->request->getParam('module_id',$module);
		if(!$module_id)
		{
			$this->exitWithError("参数错误","/module/index");
		}
		$menus = Menu::getInstance()->getAllMenuByModuleId($module_id);
		if($menus==false){
			$menus=array();
		}
		$this->renderData['module_id'] = $module_id;
		$this->renderData['menus'] = $menus;
		$this->render('menu',$this->renderData);
	}
	
	public function actionModify()
	{
		$module_id = Yii::app()->request->getParam('module_id',0);
		$submit = Yii::app()->request->getParam('submit','');

		if ($submit)
		{
			$data["module_name"] = trim(Yii::app()->request->getParam('module_name'));
			$data["module_url"]  = trim(Yii::app()->request->getParam('module_url'));
			$data["module_icon"] = trim(Yii::app()->request->getParam('module_icon'));
			$data["module_sort"] = trim(Yii::app()->request->getParam('module_sort'));
			$data["module_desc"] = trim(Yii::app()->request->getParam('module_desc'));
			$data["online"] = trim(Yii::app()->request->getParam('online'));
			try {
				$ret = Module::getInstance()->updateModule($data,$module_id);
				if($ret)
				{
					$this->exitWithSuccess("修改菜单模块成功", '/module/modify?module_id='.$module_id);
				}else {
					$this->alert('error','修改菜单模块失败');
				}
			} catch (Exception $e) {
				$this->alert('error','修改菜单模块失败');
			}
		}
		if(!$module_id)
		{
			$this->exitWithError("参数错误","/module/index");
		}
		$module = Module::getInstance()->getModuleInfo($module_id);
		
		$this->renderData['module_id'] = $module_id;
		$this->renderData['module'] = $module;
		$this->render('modify',$this->renderData);
	}
	
	
	/**
	 * 删除模块
	 */
	public function actionDel()
	{
		//@todo 删除模块需要判断是否有子模块
		$module_id = Yii::app()->request->getParam('module_id',0);
		if(!$module_id)
		{
			$this->exitWithError("参数错误","/module/index");
		}
		$ret  =  Module::getInstance()->delModule($module_id);
		if ($ret)
		{
			$this->exitWithSuccess("删除菜单模块成功", '/module/index');
		}else {
			$this->exitWithError("删除菜单模块成功", '/module/index');
		}
	}
}
?>