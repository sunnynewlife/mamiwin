<?php

class AccountController extends Controller 
{	
	private function isStrongPwd($str)
	{
		$score = 0;
		if(strlen($str) >= 8) {
			$score ++; //密码长度
		}
		if(preg_match("/[0-9]+/",$str)) {
			$score ++; //含有数字
		}
		if(preg_match("/[a-z]+/",$str)) {
			$score ++; //含有小写字母
		}
		if(preg_match("/[A-Z]+/",$str)) {
			$score ++; //含有小写字母
		}
		if(preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]+/",$str)) {
			$score ++; //含有特殊字符
		}
		//必须含有至少一个数字，一个字母，长度大于8
		//判断 $score>=3
		return $score;		
	} 
	
    public function actionIndex()
    {
    	$where = array();
    	$userGroup = trim(Yii::app()->request->getParam('user_group',0));
    	$userName = Yii::app()->request->getParam('user_name','');
    	$page_no  = Yii::app()->request->getParam('page_no',1);
    	$search  = Yii::app()->request->getParam('search','');
	
    	if ($userGroup) $where['user_group'] = $userGroup;
    	if ($userName) $where['user_name'] = $userName;

    	$page_size = 10;
    	$page_no=$page_no<1?1:$page_no;
    	
    	$info =UserInfo::getInstance()->getUsersCount($userGroup,$userName);
 
    	$row_count = $info['num'];
    	$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
    	$total_page=$total_page<1?1:$total_page;
    	$page_no=$page_no>($total_page)?($total_page):$page_no;
    	$start = ($page_no - 1) * $page_size;
    
    	
    	$page_str=Util::showPager("/account/index?user_group=$userGroup&user_name=$userName&search=1",$page_no,$page_size,$row_count);
    	$users = UserInfo::getInstance()->getUsers($userGroup,$userName,$start,$page_size);
    	if($users==false){
    		$users=array();
    	}

    	$this->renderData['users']= $users;
    	$this->renderData['page']= $page_str;
    	$this->renderData['page_no'] = $page_no;
    	$this->render('account',$this->renderData);
    }
    
    
    public function actionAdd()
    {
    	$submit= Yii::app()->request->getParam('submit');
    	if ($submit)
    	{
			$data["user_name"] = trim(Yii::app ()->request->getParam ( 'user_name' ));
			$data["password"] = trim(Yii::app ()->request->getParam ( 'password' ));
			$data["real_name"] = trim(Yii::app ()->request->getParam ( 'real_name' ));
			$data["mobile"] = trim(Yii::app ()->request->getParam ( 'mobile' ));
			$data["email"] = trim(Yii::app ()->request->getParam ( 'email' ));
			$data["user_desc"] = trim(Yii::app ()->request->getParam ( 'user_desc' ));
			$data["user_group"] = trim(Yii::app ()->request->getParam ( 'user_group' ));
			
			//判断密码强度
			$pwdstrong = $this->isStrongPwd($data["password"]);
			if($pwdstrong < 3)
			{
				$this->exitWithError('密码强度不够，必须长度大于8，并至少含有一个数字和一个字母', '/account/index');
			}
			//pwd md5
			$data["password"] = md5($data["password"]);
			try {
				if (UserInfo::getInstance()->insertUserInfo($data))
				{
					$this->exitWithSuccess("添加用户成功", '/account/index');
				}else{
					$this->exitWithError("添加用户失败", '/account/index');
				}
			} catch (Exception $e) {
				$this->exitWithError("添加用户失败", '/account/index');
			}
    		return;
    	}
    	$this->render('add');
    }
    
    /**
     * 修改
     */
    public function actionModify()
    {
    	$submit= Yii::app()->request->getParam('submit');
    	$user_id = Yii::app()->request->getParam('user_id');
    	if ($submit && $user_id)
    	{
    		$data["user_name"] = trim(Yii::app ()->request->getParam ( 'user_name' ));
    		$data["password"] = trim(Yii::app ()->request->getParam ( 'password' ));
    		$data["real_name"] = trim(Yii::app ()->request->getParam ( 'real_name' ));
    		$data["mobile"] = trim(Yii::app ()->request->getParam ( 'mobile' ));
    		$data["email"] = trim(Yii::app ()->request->getParam ( 'email' ));
    		$data["user_desc"] = trim(Yii::app ()->request->getParam ( 'user_desc' ));
    		$data["user_group"] = trim(Yii::app ()->request->getParam ( 'user_group' ));

    		//如果密码为空则不修改
    		if(!empty($data["password"])) {
				//判断密码强度
				$pwdstrong = $this->isStrongPwd($data["password"]);
				if($pwdstrong < 3)
				{
					$this->exitWithError('密码强度不够，必须长度大于8，并至少含有一个数字和一个字母', '/account/index');
				}
				//pwd md5
				$data["password"] = md5($data["password"]);
			}
    		if (UserInfo::getInstance()->updateUserInfo($data,$user_id))
    		{    			
    			$this->exitWithSuccess("修改用户信息成功", '/account/index');
    		}else{
    			$this->exitWithError("修改用户信息失败", '/account/index');
    		}
    		return;
    	}
    	$user_id = trim(Yii::app()->request->getParam('user_id'));
    	$userInfo = UserInfo::getInstance()->getUserInfo($user_id);
    	$data['user'] = $userInfo;
    	$data = array_merge($data,$this->renderData);
    	$this->render('modify',$data);
    }
    
    /**
     * 封停账号
     */
    public function actionArchived()
    {
    	$userId  = Yii::app()->request->getParam('user_id');
    	$page_no = Yii::app()->request->getParam('page_no');
    	$data = array('status'=>0);
    	$ret = UserInfo::getInstance()->updateUserInfo($data,$userId);
    	if ($ret)
    	{
    		$this->exitWithSuccess("封停用户成功", '/account/index?page_no='.$page_no);
    	}else {
    		$this->exitWithError("封停用户失败", '/account/index?page_no='.$page_no);
    	}
    }
    
    public function actionShortCut()
    {
    	$array = array("result"=>"1","msg"=>"");
    	$menu_id = Yii::app()->request->getParam('menu_id');
    	$method = Yii::app()->request->getParam('method');
    	
    	$userInfo = UserInfo::getInstance()->getUserInfo($this->_uid);
    	$shortCuts = explode(',',$userInfo['shortcuts']);
    	$data = array();
    	if ($method == 'add')
    	{
    		if(!in_array($menu_id, $shortCuts))
    		{
    			$shortCuts[] = $menu_id;
    		}
    		$array['msg'] = '添加快捷菜单';
    		$data = $shortCuts;
    	}elseif($method == 'del'){
   
    		foreach ($shortCuts as $key => $val)
    		{
    			if ($val != $menu_id) $data[] = $val; 
    		}
    		$array['msg'] = '删除快捷菜单';
    	}
    	$t['shortcuts'] = implode(',', $data);
    	$ret = UserInfo::getInstance()->updateUserInfo($t,$this->_uid);
    	if(!$ret)
    	{
    		$array['result'] = 0;
    		$array['msg'] .='失败' ;
    	}else{
    		$array['msg'] .='成功' ;
    	}
    	echo json_encode($array);
    }
    
    /**
     * 解封
     */
    public function actionOpen()
    {
    	$userId  = Yii::app()->request->getParam('user_id');
    	$page_no = Yii::app()->request->getParam('page_no');
    	$data = array('status'=>1);
    	$ret = UserInfo::getInstance()->updateUserInfo($data,$userId);
    	if ($ret)
    	{
    		$this->exitWithSuccess("解封用户成功", '/account/index?page_no='.$page_no);
    	}else {
    		$this->exitWithError("解封用户失败", '/account/index?page_no='.$page_no);
    	}
    }
    
    /**
     * 删除用户
     */
    public function actionDel()
    {
    	$userId  = Yii::app()->request->getParam('user_id');
    	$page_no = Yii::app()->request->getParam('page_no');
    	$ret = UserInfo::getInstance()->delUser($userId);
    	if ($ret)
    	{
    		$this->exitWithSuccess("删除用户成功", '/account/index?page_no='.$page_no);
    	}else {
    		$this->exitWithError("删除用户失败", '/account/index?page_no='.$page_no);
    	}
    }
    
    public function actionProfile()
    {
    	$do = Yii::app()->request->getParam('do','');
    	if ($do){
	    	switch ($do)
	    	{
	    		case 'modify_profile':
	    			$this->renderData['do'] = 'modify_profile';
	    			$data["user_name"] = trim(Yii::app ()->request->getParam ( 'user_name',''));
	    			$data["real_name"] = trim(Yii::app ()->request->getParam ( 'real_name' ));
	    			$data["mobile"] = trim(Yii::app ()->request->getParam ( 'mobile' ));
	    			$data["email"] = trim(Yii::app ()->request->getParam ( 'email' ));
	    			$data["user_desc"] = trim(Yii::app ()->request->getParam ( 'user_desc' ));
	    			$data["show_quicknote"] = trim(Yii::app ()->request->getParam ( 'show_quicknote' ));
	    			
	    			$ret = UserInfo::getInstance()->updateUserInfo($data,$this->_uid);
	    			if ($ret)
	    			{
	    				$this->exitWithSuccess("修改信息成功","/account/profile");
	    			}else {
	    				$this->_profileFailed('修改失败');
	    			}
	    			break;
	    		case 'change_password':
	    			$this->renderData['do'] = 'change_password';
	    			$old = trim(Yii::app ()->request->getParam ( 'old' ));
	    			$new = trim(Yii::app ()->request->getParam ( 'new' ));
	    			
	    			if(empty($old) || empty($new))
	    			{
	    				$this->_profileFailed('参数不正确');
	    			}
	    			if($old == $new)
	    			{
	    				$this->_profileFailed('两个密码相同');
	    			}
					//判断密码强度
					$pwdstrong = $this->isStrongPwd($new);
	    			if($pwdstrong < 3)
	    			{
	    				$this->_profileFailed('密码强度不够，必须长度大于8，并至少含有一个数字和一个字母');
	    			}
					
	    			$userInfo = UserInfo::getInstance()->getUserInfo($this->_uid);
	    			if(md5($old) !=  $userInfo['password'])
	    			{
	    				$this->_profileFailed('原密码不正确！');
	    			}
	    			
	    			$newPwd = md5($new);
	    			$data['password'] = $newPwd;
	    			$ret = UserInfo::getInstance()->updateUserInfo($data,$this->_uid);
	    			if ($ret)
	    			{
	    				$this->exitWithSuccess("修改密码成功","/account/profile");
	    			}else {
	    				$this->_profileFailed('修改密码失败');
	    			}

	    			break;
				default:
					$this->exitWithError('非法操作！', '/account/profile');
	    	}
    	}
    	$user = UserInfo::getInstance()->getUserInfo($this->_uid);
    	$data['user'] = $user;
    	$this->renderData['do'] = 'modify_profile';
    	$this->renderData = array_merge($data,$this->renderData);
    	$this->render('profile',$this->renderData);	
    }
    
    private function _profileFailed($msg)
    {
    	$user = UserInfo::getInstance()->getUserInfo($this->_uid);
    	$data['user'] = $user;
    	$this->renderData = array_merge($data,$this->renderData);
    	$this->alert('error',$msg,"/account/profile");
    }
}
?>