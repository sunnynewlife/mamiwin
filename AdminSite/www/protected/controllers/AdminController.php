<?php

class AdminController extends Controller 
{	
    public function actionIndex()
    {
    	$user = UserInfo::getInstance()->getUserInfo($this->_uid);
    	$this->renderData['user_info'] = $user;
    	$menus = array();
    	if ($user['shortcuts'])
    	{
    		
    		$menu_ids = explode(',', $user['shortcuts']);
    		foreach($menu_ids as $val)
    		{
    			$menu_info = Menu::getInstance()->getMenuInfo($val);
    			if(isset($menu_info) && is_array($menu_info)){
    				$menus[] = array("menu_name"=> $menu_info['menu_name'],"menu_url"=>$menu_info['menu_url']);
    			}
    		}
    	}
    	$this->renderData['menus'] = $menus;
    	$this->render('index',$this->renderData);
    }
    
    /**
     * 设置模版
     */
    public function actionSetSkin()
    {
    	$skin = Yii::app()->request->getParam('skin'); 	
    	$templates=array("default","blacktie","wintertide","schoolpainting");
    	if(!in_array($skin,$templates)){
    		$skin="wintertide";
    	}
       	Yii::app()->session['skin'] = $skin;
    	UserInfo::getInstance()->updateUserInfo(array('template'=>$skin),$this->_uid);
    	$rand=rand(0,10000);
    	$back_url=$_SERVER['HTTP_REFERER']."#".$rand;
    	header("Location:$back_url");
    }
    
    public function actionSetting()
    {
    	$this->render('setting');
    }
    
    public function actionSystem()
    {
    	$sys_info = Util::getSysInfo ();
    	$mysql_version = UserInfo::getInstance()->getMysqlVersion();
    	$sys_info['mysql_version'] = $mysql_version;
    	$this->renderData['sys_info'] = $sys_info;
    	$this->render('system',$this->renderData);
    }
    
    public function actionSysLog()
    {
    	$class_name = trim(Yii::app()->request->getParam('class_name',''));
    	$user_name  = trim(Yii::app()->request->getParam('user_name',''));
    	$start_date = trim(Yii::app()->request->getParam('start_date',''));
    	$end_date   = trim(Yii::app()->request->getParam('end_date',''));
    	$page_no    = trim(Yii::app()->request->getParam('page_no',1));
    	$search     = trim(Yii::app()->request->getParam('search',''));
    	$page_size = 10;
    	$page_no=$page_no<1?1:$page_no;
    	$row_count = SystemLog::getInstance()->getLogCount($class_name,$start_date,$end_date,$user_name);
    	$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
    	$total_page=$total_page<1?1:$total_page;
    	$page_no=$page_no>($total_page)?($total_page):$page_no;
    	$start = ($page_no - 1) * $page_size;
    	$page_str=Util::showPager("/admin/syslog?class_name=$class_name&user_name=$user_name&search=1&start_date=$start_date&end_date=$end_date",$page_no,$page_size,$row_count);
    	
    	$log = SystemLog::getInstance()->getLog($class_name,$start_date,$end_date,$user_name,$page_size,$start);
    	if($log==false){
    		$log=array();
    	}
    	$this->renderData['sys_logs'] = $log;
    	$this->renderData['page'] = $page_str;
    	$this->renderData['start_date'] = $start_date;
    	$this->renderData['end_date'] = $end_date;
    	$this->renderData['user_name'] = $user_name;
    	
    	$this->render('sysLog',$this->renderData);
    }
}
?>