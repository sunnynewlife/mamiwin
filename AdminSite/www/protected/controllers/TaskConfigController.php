<?php
LunaLoader::import("luna_lib.util.CGuidManager");

class TaskConfigController extends TableMagtController 
{
	private $_tableName="Task_Config";
	private $_searchName="";
	private $_next_url="/taskConfig/index";
	private $_columns=array("IDX","Config_Key","Config_Value","Config_Remark");
	private $_title="任务配置项";
	private $_primaryKey="IDX";
	
	protected $_EXTRA_SEARCH_FIELDS=array(
		"Config_Key" 	=> array("compartion_type" =>"like","field_name" =>"Config_Key"),
	);
	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="BizDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
		
	}
	
	public function actionIndex()
	{	
		$this->_actionIndex("Task_Config", $this->_searchName, $this->_next_url, $this->_tableName,"index");	
	}
	
	public function actionModify()
	{
		$value = Yii::app()->request->getParam($this->_primaryKey,'');
		if(empty($value)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$submit = trim(Yii::app()->request->getParam('submit',0));
		$TaskConfigData	=new TaskConfigData();
		
		if ($submit){			
			$IDX =Yii::app()->request->getParam("IDX","");
			$Config_Key =Yii::app()->request->getParam("Config_Key","");
			$Config_Value=Yii::app()->request->getParam("Config_Value",0);
			$Config_Remark=Yii::app()->request->getParam("Config_Remark","");
			
			if(empty($Config_Key)) {
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
