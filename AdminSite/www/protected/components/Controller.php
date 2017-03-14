<?php

class Controller extends CController 
{
	
	protected $_errno = 1;
	protected $_error = '';
	protected $_clientIp = '';
	protected $_isLogin = false;
	protected $_uid = '';
	protected $_userName = '';
	protected $_userInfo =array();
	protected $_permission = array();
	
	protected $_returnData=array();
	public $layout='//layouts/main';

	protected $renderData = array();

	protected  function cacheControl()
	{
		header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
		header("Pragma:no-cache");
		header("Cache-control:no-cache, must-revalidate");
	}
	
	protected function getData($params)
	{
		$data=array();
		foreach ($params as $paramName){
			$data[$paramName]=trim(Yii::app()->request->getParam($paramName));
		}
		return $data;
	}
	
	
	public function init()
	{
		header("Content-Type:text/html;charset=utf-8");
	}	
	
	protected function beforeAction($action) 
	{
		if(isset(Yii::app()->session["login_status"]) && Yii::app()->session["login_status"] == 1){
			$this->_isLogin = true;
			$this->_uid = Yii::app()->session['uid'];
			$this->_userName = Yii::app()->session['userName'];
			
			$this->_userInfo = UserInfo::getInstance()->getUserInfo($this->_uid);
			$this->_permission = explode(',',$this->_userInfo['group_role']);
		}
		Yii::app()->params['skin']="default";
		if (!empty($this->_userInfo['template'])){
			Yii::app()->params['skin'] = $this->_userInfo['template'];
		}
		
		$currentUrl = '/'.$this->getId().'/'.$this->getAction()->getId();	
        if (strtolower($this->getId()) !='user'){
			if (!$this->isLogin()){
				header('Location:/user/login');
				return false;
			}			
			//首页不检查权限
			if($currentUrl !='/admin/index'){
				$menu = Menu::getInstance()->getMenuByUrl($currentUrl);
				if ($menu){
					if(!in_array($menu['menu_id'], $this->_permission)){
						$this->exitWithError("您还没有使用该功能权限,请联系管理员。", '/admin/index');
						return false;
					}
				}else{
					$this->exitWithError("您还没有使用该功能权限,请联系管理员。", '/admin/index');
					return false;
				}
			}
		}
		Yii::app()->params['currentUrl'] = $currentUrl;
		return true;
	}
	
	protected function isLogin()
	{
		return $this->_isLogin;
	}
	protected function afterAction($action) 
	{
		return true;
    }
    
    /**
     * 配置成功提示
     * @param unknown $data
     * @param unknown $return_message
     * @param number $return_code
     */
    protected function setSuccessInfo($data,$return_message,$return_code=0)
    {
    	$this->_errno = 0;
    	$this->_returnData = $data;
    	$this->_error= $return_message;  
    }
    
    
    /**
     * 接口返回false时，通过本接口获取详细的错误信息
     * @return array('errno'=>**, 'error'=>**)
     */
    protected function getErrorInfo() {
    	return array('errno'=>$this->_errno, 'error'=>$this->_error);
    }
    
    /**
     * 将其他接口的错误信息传递到当前组件
     * @param array $err getErrorInfo()返回的结果
     */
    protected function setErrorInfo($err) {
    	$this->_errno = $err['errno'];
    	$this->_error = $err['error'];
    }
    
    protected function exitWithMessage($message_detail, $forward_url, $second = 3,$type="message"){
    	switch ($type) {
    		case "success" :
    			$page_title="操作成功！";
    			break;
    		case "error":
    			$page_title="错误!";
    			break;
    		default:
    			$page_title="嗯!";
    			break;
    	}
    	$temp = explode('?',$forward_url);
    	$file_url = $temp[0];
    	if($file_url{0} !=="/"){
    		$file_url ='/'.$file_url;
    		$forward_url ='/'.$forward_url;
    	}
    	
    	if($file_url{strlen($file_url) -1 } =="/"){
    		$file_url =substr($file_url, 0,-1);
    	}
    	
    	$menu = Menu::getInstance()->getMenuByUrl($file_url);
    	$forward_title = "首页";
    	if(sizeof($menu)>0){
    		$forward_title = $menu['menu_name'];
    	}
    	if ($forward_url) {
    		$message_detail = "$message_detail <script>setTimeout(\"window.location.href ='"."$forward_url';\", " . ($second * 1000) . ");</script>";
    	}

    	$data = array(
    			'type'=> $type,
    			'page_title'=> $page_title,
    			'message_detail'=> $message_detail,
    			'forward_url'=> $forward_url,
    			'forward_title'=> $forward_title,
    	);
    	$this->sysLog(array('result'=>$type));
    	$this->renderPartial("/layouts/message",$data);
    	Yii::app()->end();
    }
    
    public  function exitWithError($message_detail, $forward_url, $second = 3,$type="error") {
    	$this->exitWithMessage($message_detail, $forward_url, $second ,$type);
    }
    
    public  function exitWithSuccess($message_detail, $forward_url, $second = 3 ,$type="success") {
    	$this->exitWithMessage($message_detail, $forward_url, $second, $type);
    }
    
    public function alert($type,$message="",$render = ''){
    	if($message == "") {
    		switch(strtolower($type)){
    			case "success":
    				$message=ErrorCode::SUCCESS;
    				break;
    			case "error" :
    				$message=ErrorCode::ERROR;
    				break;
    		}
    	}
    	$alert_html="<div class=\"alert alert-$type\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>$message</div>";
    	$this->renderData['alert_message'] = $alert_html;
    	$this->sysLog(array('result'=>$type,'message'=>$message));
    	if ($render){
	    	if ($render)$this->render($render,$this->renderData);
	    	Yii::app()->end();
    	}
    }
    
    protected function sysLog($result)
    {
    	$data['user_name']	 = $this->_userName;
    	$data['class_name']  = $this->getid();
    	$data['action']      = $this->getAction()->getId();
    	$inputParams = array_merge($_GET,$_POST);
    	if(isset($inputParams['password']))unset($inputParams['password']);
    	$data['inputParams'] = json_encode($inputParams);
    	$data['result'] = json_encode($result);
    	SystemLog::getInstance()->addLog($data);
    }
}