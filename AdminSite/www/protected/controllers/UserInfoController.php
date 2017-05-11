<?php
LunaLoader::import("luna_lib.util.CGuidManager");

class UserInfoController extends TableMagtController 
{
	private $_tableName="User_Info";
	private $_searchName="";
	private $_next_url="/userInfo/index";
	private $_columns=array("IDX","LoginName","AcctSource","OpenId","Exp_Points","Create_Time","Update_Time");
	private $_title="用户信息";
	private $_primaryKey="IDX";
	
	protected $_EXTRA_SEARCH_FIELDS=array(
		"LoginName" 	=> array("compartion_type" =>"like","field_name" =>"LoginName"),
	);
	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="BizDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
	}
	
	public function actionIndex()
	{	
		$this->_actionIndex("User_Info", $this->_searchName, $this->_next_url, $this->_tableName,"index");	
	}

	public function actionAdd()
	{
		$submit = trim(Yii::app()->request->getParam('submit',0));
		$TaskConfigData	=new TaskConfigData();
		
		if ($submit){			
			$LoginName =Yii::app()->request->getParam("LoginName","");
			$LoginPwd=Yii::app()->request->getParam("LoginPwd","");
			
			if(empty($LoginName) || empty($LoginPwd)) {
				$this->alert('error',"请正确设置字段");
			}else{
				if($TaskConfigData->updateTaskConfig($Config_Key,$Config_Value,$Config_Remark,$value)){
					return $this->exitWithSuccess("修改任务配置项成功",$this->_next_url);
				}
				$this->alert('error',"修改任务配置项失败，请正确设置字段值");
			}			
		}

		$TaskConfig = $TaskConfigData->getTaskConfigByIDX($value);
		$this->renderData["TaskConfig"]=$TaskConfig;

		$this->render("modify",$this->renderData);
	}

}
