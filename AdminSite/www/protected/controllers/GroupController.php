<?php
Yii::import('model.UserGroup');
Yii::import('model.UserInfo');
Yii::import('model.Module');
Yii::import('lib.Util');
class GroupController extends Controller {	
    public function actionIndex()
    {
    	$groups = UserGroup::getInstance()->getGroups();
    	$this->renderData['groups'] = $groups;
    	$this->render('index',$this->renderData);
    }
   

    public function actionModify()
    {
    	$group_id = trim(Yii::app()->request->getParam('group_id'));
		$submit = trim(Yii::app()->request->getParam('submit',0));
	    if($submit)
	    {
	    	$data['group_name'] = trim(Yii::app()->request->getParam('group_name'));
	    	$data['group_desc'] = trim(Yii::app()->request->getParam('group_desc'));
	    	try {
	    		$ret =  UserGroup::getInstance()->updateGroup($data,$group_id);
	    		if($ret)
	    		{
	    			$this->exitWithSuccess("编辑账号组成功","/group/modify?group_id=".$group_id);
	    		}else {
	    			$this->alert('error','编辑账号组失败');
	    		}
		    } catch (Exception $e) {
		    		$this->alert('error','编辑账号组失败');
		    }
	    }
    	$group = UserGroup::getInstance()->getGroupInfo($group_id);
    	$this->renderData['group'] = $group;
    	$this->render('modify',$this->renderData);
    }
    
    public function actionDel()
    {
    	$group_id = Yii::app()->request->getParam('group_id',0);
    	if(!$group_id)
    	{
    		  $this->exitWithError("参数错误","/group/index");
    	}
    	$ret = UserGroup::getInstance()->delGroup($group_id);
   		if($ret) 
   		{
			$this->exitWithSuccess("删除账号组成功", '/group/index');
		}else{
    		$this->exitWithError("删除账号组失败", '/group/index');
    	}
    }
    
    public function actionAdd()
    {
    	$submit = trim(Yii::app()->request->getParam('submit',0));
    	if($submit)
    	{
    		$data['group_name'] = trim(Yii::app()->request->getParam('group_name'));
    		$data['group_desc'] = trim(Yii::app()->request->getParam('group_desc'));
    		$data['owner_id'] = $this->_uid;
    		try {
    			$ret =  UserGroup::getInstance()->insertGroup($data);
    			if($ret)
    			{
    				$this->exitWithSuccess("添加账号组成功","/group/add");
    			}else {
    				$this->alert('error','添加账号组失败');
    			}
    		} catch (Exception $e) {
    			$this->alert('error','添加账号组失败');
    		}
    	}			 
    	$this->render('add');
    }
    
    
    public function actionMember()
    {
    	$group_id = Yii::app()->request->getParam('group_id',0);
    	$submit = trim(Yii::app()->request->getParam('submit',0));
    	if($submit)
    	{
    		$users = Yii::app()->request->getParam('user_ids',array());
    		$user_group = Yii::app()->request->getParam('user_group',0);
    		if(!$group_id || !$user_group)
    		{
    			$this->exitWithError("参数错误","/group/index");
    		}
    		try {
    			$ret = UserInfo::getInstance()->changeUserGroup($users,$user_group);
    			if($ret)
    			{
    				$this->exitWithSuccess("修改账号组成功","/group/member?group_id=".$user_group);
    			}else {
    				$this->alert('error','修改账号组失败');
    			}
    		} catch (Exception $e) {
    			$this->alert('error','修改账号组失败');
    		}
    	}
    	if(!$group_id)
    	{
    		$this->exitWithError("参数错误","/group/index");
    	}
    	$users = UserInfo::getInstance()->getUsers($group_id,'',0,100);
    	if($users==false){
    		$users=array();
    	}
    	$this->renderData['users'] = $users;
    	$this->renderData['group_id'] = $group_id;
    	$this->render('member',$this->renderData);
    }
    
    public function actionPermission()
    {
    	$submit = trim(Yii::app()->request->getParam('submit',0));
    	$group_id = Yii::app()->request->getParam('group_id',1);
    	if($submit)
    	{
    		$menus = Yii::app()->request->getParam('menu_ids',array());
    		if(!$group_id)
    		{
    			$this->exitWithError("参数错误","/group/permission/");
    		}
    		try {
    			$menuIds = implode(',', $menus);
    			$ret =  UserGroup::getInstance()->updateGroup(array('group_role'=>$menuIds),$group_id);
    			if($ret)
    			{
    				$this->exitWithSuccess("修改账号组权限成功","/group/permission?group_id=".$group_id);
    			}else {
    				$this->alert('error','修改账号组权限失败');
    			}
    		} catch (Exception $e) {
    			$this->alert('error','修改账号组权限失败');
    		}
    	}
    	$module = Module::getInstance()->getModuleList();
    	$data = array();
    	foreach ($module as  $key =>$val)
    	{
    		$menu = Menu::getInstance()->getMenuByModuleId($val['module_id'],'permission');
    		if($menu==false){
    			$menu=array();
    		}
    		$data[$key]['module_name'] = $val['module_name']; 
    		$data[$key]['module_id'] = $val['module_id']; 
    		$data[$key]['menu'] = $menu;
     	}	
    	$this->renderData['group_id'] = $group_id;
    	$this->renderData['permission'] = $data;
    	$groupInfo = UserGroup::getInstance()->getGroupInfo($group_id);
    	$this->renderData['group_permission'] = explode(',', $groupInfo['group_role']);
    	$this->render('permission',$this->renderData);
    }
}
?>