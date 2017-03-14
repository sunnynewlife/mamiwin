<?php

LunaLoader::import("luna_lib.util.LunaPdo");

class GAppAskDBCheckController extends TableMagtController 
{
	private $_g_ask_database_cfg_node_name="GAppAsk";
		
	public function init()
	{
		$this->_PDO_NODE_NAME="GAppCfg";
	}
	
	public function actionIndex()
	{
		$this->renderData["result"]=array();
		$this->renderData["sql"]="";
		$submit = trim(Yii::app()->request->getParam('submit'),0);
		if ($submit){
			$sql=trim(Yii::app()->request->getParam('sql'),"");
			if(empty($sql)==false){
				$queryList=LunaPdo::GetInstance($this->_g_ask_database_cfg_node_name)->query_with_prepare($sql,array());
				if(isset($queryList) && is_array($queryList)){
					$this->renderData["result"]=$queryList;
				}
			}
			$this->renderData["sql"]=$sql;
		}
		$this->render("index",$this->renderData);
	}
}
?>