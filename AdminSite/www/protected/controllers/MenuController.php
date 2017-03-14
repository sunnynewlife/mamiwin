<?php

class MenuController extends Controller 
{	
	public function actionIndex()
	{  	
    	$module_id = trim(Yii::app()->request->getParam('module_id',0));
    	$menu_name = Yii::app()->request->getParam('menu_name','');
    	$page_no  = Yii::app()->request->getParam('page_no',1);
    	$search  = Yii::app()->request->getParam('search','');
		$page_size = 10;
    	$page_no=$page_no<1?1:$page_no;
    	$info =Menu::getInstance()->getMenuCount($module_id,$menu_name);
 
    	$row_count = $info['num'];
    	$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
    	$total_page=$total_page<1?1:$total_page;
    	$page_no=$page_no>($total_page)?($total_page):$page_no;
    	$start = ($page_no - 1) * $page_size;
    
    	
    	$page_str=Util::showPager("/menu/index?module_id=$module_id&menu_name=$menu_name&search=1",$page_no,$page_size,$row_count);
    	$menus = Menu::getInstance()->getAllMenu($module_id,$menu_name,$page_size,$start);
    	
    	if($menus==false){
    		$menus=array();
    	}   	

    	$this->renderData['menus']= $menus;
    	$this->renderData['page']= $page_str;
    	$this->renderData['page_no'] = $page_no;
    	$this->renderData['module_id'] = $module_id;
    	$this->render('index',$this->renderData);
	}
	
	/**
	 * 修改功能菜单
	 */
	public function actionModify()
	{
		$menu_id = trim(Yii::app()->request->getParam('menu_id',0));
		$submit = trim(Yii::app()->request->getParam('submit'),0);
		if (!$menu_id)
		{
			$this->exitWithError("参数错误","/menu/index");
		}
		if ($submit)
		{
			$data["menu_name"] = trim(Yii::app()->request->getParam('menu_name'));
			$data["menu_url"] = trim(Yii::app()->request->getParam('menu_url'));
			$data["module_id"] = trim(Yii::app()->request->getParam('module_id'));
			$data["is_show"] = trim(Yii::app()->request->getParam('is_show'));
			$data["father_menu"] = trim(Yii::app()->request->getParam('father_menu'));
			$data["online"] = trim(Yii::app()->request->getParam('online'));
			$data["shortcut_allowed"] = trim(Yii::app()->request->getParam('shortcut_allowed'));
			$data["menu_desc"] = trim(Yii::app()->request->getParam('menu_desc'));
			$data["menu_group"]=trim(Yii::app()->request->getParam('menu_group'));
			
			try {
				$ret = Menu::getInstance()->updateMenu($data,$menu_id);
				if($ret)
				{
					$this->exitWithError("修改功能菜单成功","/menu/modify?menu_id=".$menu_id);
				}else {
					$this->alert('error','修改功能菜单失败');
				}
			} catch (Exception $e) {
				$this->alert('error','修改功能菜单失败');
			}
			
		}		
		$menu = Menu::getInstance()->getMenuInfo($menu_id);
		$this->renderData['menu'] = $menu;
		$module = Module::getInstance()->getModuleList();
		foreach ($module as &$val)
		{
			$val['menu_list'] = Menu::getInstance()->getMenuByModuleId($val['module_id']);
		}
		$this->renderData['module'] = $module;
		$this->render('modify',$this->renderData);
	}
	
	/**
	 * 删除功能菜单
	 */
	public function actionDel(){
		$menu_id = trim(Yii::app()->request->getParam('menu_id',0));
		$page_no = trim(Yii::app()->request->getParam('page_no',0));
		if(!$menu_id)
		{
			$this->exitWithError("参数错误","/menu/index");
		}
		
		$ret = Menu::getInstance()->delMenu($menu_id);
		if ($ret)
		{
			$this->exitWithSuccess("删除功能菜单成功", "/menu/index?page_no=".$page_no);
		}else {
			$this->exitWithError("删除失败错误","/menu/index?page_no=".$page_no);
		}
	}
	
	public function actionAdd()
	{
		$submit = trim(Yii::app()->request->getParam('submit'),0);
		if ($submit)
		{
			$data["menu_name"] = trim(Yii::app()->request->getParam('menu_name'));
			$data["menu_url"] = trim(Yii::app()->request->getParam('menu_url'));
			$data["module_id"] = trim(Yii::app()->request->getParam('module_id'));
			$data["is_show"] = trim(Yii::app()->request->getParam('is_show'));
			$data["father_menu"] = trim(Yii::app()->request->getParam('father_menu'));
			$data["shortcut_allowed"] = trim(Yii::app()->request->getParam('shortcut_allowed'));
			$data["menu_desc"] = trim(Yii::app()->request->getParam('menu_desc'));
			$data["menu_group"]=trim(Yii::app()->request->getParam('menu_group'));
			
			try {
				$ret = Menu::getInstance()->insertMenu($data);
				if($ret)
				{
					$this->exitWithError("增加功能菜单成功","/menu/index");
				}else {
					$this->alert('error','增加功能菜单失败');
				}
			} catch (Exception $e) {
				$this->alert('error','增加功能菜单失败');
			}
		}
		$module = Module::getInstance()->getModuleList();
		foreach ($module as &$val)
		{
			$val['menu_list'] = Menu::getInstance()->getMenuByModuleId($val['module_id']);
		}
		$this->renderData['module'] = $module;	
		$this->render('add',$this->renderData);
	}
}
?>