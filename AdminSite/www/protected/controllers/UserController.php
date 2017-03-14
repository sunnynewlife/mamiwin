<?php

class UserController extends Controller {
		
	public $layout=false;
	public function actionInfo()
	{
		echo phpinfo();
	}
	private function doLogin()
	{
		$userName = trim(Yii::app()->request->getParam("user_name"));
		$password = trim(Yii::app()->request->getParam("password"));
		$remember = Yii::app()->request->getParam("remember");
		
		$info = UserInfo::getInstance()->getUserInfoByUserName($userName);
		 
		if(!$info){
			$this->alert("error",ErrorCode::USER_NOT_EXIST,'/user/login');
		}		
		if($info['status'] == 0){
			$this->alert("error",ErrorCode::BE_PAUSED,'/user/login');
		}
		 
		if(md5($password) !=  $info['password'])
		{
			$this->alert("error",ErrorCode::PWD_WRONG,'/user/login');
		}
		 
		Yii::app()->session['login_status'] = 1;
		Yii::app()->session['uid'] = $info['user_id'];
		Yii::app()->session['user_group'] = $info['user_group'];
		Yii::app()->session['user_role'] = $info['group_role'];
		Yii::app()->session['userName'] = $userName;
		UserInfo::getInstance()->updateUserInfo(array('login_time'=>time()),$info['user_id']);
		$this->sysLog(array('loginRet'=>'Success','userName'=>$userName,'userId'=>$info['user_id']));
		header('Location: /');
		Yii::app()->end();			
	}
	
    public function actionLogin()
    {
    	if ($this->isLogin()){
    		header('Location: /');
    		echo "header location /";
    	}
    	if (Yii::app()->request->getParam('submit')){
    		$this->doLogin();
    	}
    	$this->render('login');
    }
    public function actionLogout()
    {
    	unset(Yii::app()->session['login_status']);
    	unset(Yii::app()->session['uid']);
    	unset(Yii::app()->session['userName']); 
    	$this->exitWithSuccess("退出登录成功！", "/");
    }
}
?>