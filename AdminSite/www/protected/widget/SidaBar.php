<?php
LunaLoader::import("luna_lib.util.LunaUtil");

class SidaBar extends CWidget
{
	public $currentUrl = '';
	public $current_module_id =1;
	public $current_menu_id=1;
	public $current_sub_cat="";
		
	public function run() 
	{
		if ($this->currentUrl){		
			$currentMenu = Menu::getInstance()->getMenuByUrl($this->currentUrl);
			if ($currentMenu){
				$data['current_module_id'] = $currentMenu['module_id'];
				$data['current_module'] =  Module::getInstance()->getModuleInfo($currentMenu['module_id']);
				$data['current_menu_id'] = $currentMenu['menu_id'];
				$data['current_sub_cat'] = isset($currentMenu['menu_group'])?$currentMenu['menu_group']:"";
			}		
		}else {
			Yii::app()->end();
		}
		$user_info = UserInfo::getInstance()->getUserInfo($_SESSION['uid']);
		$user_info['shortcuts'] = $user_info['shortcuts'] ? explode(',', $user_info['shortcuts']) : array();
		
		$menu = Menu::getInstance()->getMenuByUserPermission($user_info['group_role']);
		$module = array();
		if($menu && is_array($menu)){
			foreach ($menu as $val){
				$module[$val['module_id']]['menu_list'][] = $val;
				$module[$val['module_id']]['module_name'] = $val['module_name'];
				$module[$val['module_id']]['module_id'] = $val['module_id'];
				$module[$val['module_id']]['module_icon'] = $val['module_icon'];
			
			}
			//菜单名称排序
			foreach ($module as & $itemModule){
				//$itemModule["menu_list"]=$this->multi_array_sort($itemModule["menu_list"], "menu_name");
				$thisMenuList=$itemModule["menu_list"];
				LunaUtil::sort_array($thisMenuList, "menu_group", "asc","menu_name","asc");
				$itemModule["menu_list"]=$thisMenuList;
			}
		}
		$data['module'] = $module;
		$data['currentMenu'] = $currentMenu;
		$data['user_info'] = $user_info;

		$data["display"]="";
		$data["width"]="240px";
		if(isset($_COOKIE["sidebar"])){
			$data["width"]=$_COOKIE["sidebar"];
			if($data["width"]=="0px"){
				$data["display"]="none";
			}
		}	
       	$this->render('sidebar',$data);        
    }
    
    private function multi_array_sort($arr,$shortKey,$short=SORT_DESC,$shortType=SORT_REGULAR)
    {
    	foreach ($arr as $key => $data){
    		$name[$key] = $data[$shortKey];
    	}
    	array_multisort($name,$shortType,$short,$arr);
    	return $arr;
    }
}

