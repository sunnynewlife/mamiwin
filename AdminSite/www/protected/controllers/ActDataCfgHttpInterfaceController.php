<?php

LunaLoader::import("luna_lib.util.LunaPdo");

class ActDataCfgHttpInterfaceController  extends TableMagtController
{

	public function init()
	{
		$this->_PDO_NODE_NAME="EvnPlatformCfg";
	}
	public function actionIndex()
	{
		$SystemCfg=$this->getHttpInterfaceCfg();
		$this->renderData["interface"]=$SystemCfg;
		/*
		echo "<textarea rows='40' style='width:1000px;'>";
		print_r($SystemCfg);
		echo "</textarea>";
		return;
		*/
		$this->render("index",$this->renderData);
	}
	
	private function getSystemCfg()
	{
		$SystemAttr=array(
			"domain" 		=> trim(Yii::app()->request->getParam('domain')),
			"responseType" 	=> trim(Yii::app()->request->getParam('responseType')),
			"method" 		=> trim(Yii::app()->request->getParam('method')),
			"needMd5Sign" 	=> trim(Yii::app()->request->getParam('needMd5Sign')),
			"signPwd" 		=> trim(Yii::app()->request->getParam('signPwd')),
			"signPosition" 	=> trim(Yii::app()->request->getParam('signPosition')),
			"signParaName" 	=> trim(Yii::app()->request->getParam('signParaName')),
			"signParamJoinChar" 	=> trim(Yii::app()->request->getParam('signParamJoinChar')),
			"interface" => array(),
				
		);
		return array(
				trim(Yii::app()->request->getParam('key')) => $SystemAttr,				
		);
	}
	private function getHttpInterfaceCfg()
	{
		$sql="select * from act_data_cfg where type=1";
		$params=array();
		$evnDefine=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($evnDefine) && is_array($evnDefine) && count($evnDefine)>0){
			return json_decode($evnDefine[0]["content"],true);
		}
		return array();
	}
	private function setHttpInterfaceCfg($SystemCfg)
	{
		$sql="select * from act_data_cfg where type=1";
		$params=array();
		$evnDefine=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($evnDefine) && is_array($evnDefine) && count($evnDefine)>0){
			$sql="update act_data_cfg set content=? where type=1";
		}else {
			$sql="insert into act_data_cfg (type,content) values (1,?) ";
		}
		$params=array(json_encode($SystemCfg));
		$rowCount=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount>=1;
	}
	
	public function actionAdd()
	{
		$submit = trim(Yii::app()->request->getParam('submit'),0);
		if ($submit){
			$systemCfg=$this->getSystemCfg();
			$result=array_merge($this->getHttpInterfaceCfg(),$systemCfg);
			if($this->setHttpInterfaceCfg($result)){
				$this->exitWithSuccess("增加外部系统成功","/actDataCfgHttpInterface/index");
			}else {
				$this->alert('error',"增加外部失败");
			}
		}
		$this->render("add",$this->renderData);
	}
	public function actionModify()
	{
		$submit = trim(Yii::app()->request->getParam('submit'),0);
		if ($submit){
		
		}
		$this->render("modify",$this->renderData);
	}
	public function actionDel()
	{
	}
}

?>