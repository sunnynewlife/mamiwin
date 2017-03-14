<?php

LunaLoader::import("luna_lib.util.LunaPdo");

class SystemDBCheckController extends TableMagtController 
{
	public function init()
	{
	}
	
	public function injection_check()
	{
		//skip parent  injection check	
	}
	
	private function export($list)
	{
		$txtStr="". PHP_EOL;
		foreach ($list as $rowItem){
			if(is_array($rowItem)){
				$txtStr.=implode(',', $rowItem).PHP_EOL;
			}else{
				$txtStr.=$rowItem.PHP_EOL;
			}

		}
		$currentTime=time();
		$fileName=sprintf("%s_%s.csv",date("Y-m-d",$currentTime),"");
		header("Content-Type:text/html;charset=utf-8");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".strlen($txtStr));
		Header("Content-Disposition: attachment; filename=" . $fileName);
		echo $txtStr;
	}
	public function actionIndex()
	{
		$this->renderData["result"]=array();
		$this->renderData["sql"]="";
		$this->renderData["node_name"]="";
		
		$submit = trim(Yii::app()->request->getParam('submit'),0);
		$export = trim(Yii::app()->request->getParam('export'),0);
		if ($submit){
			$sql			=trim(Yii::app()->request->getParam('sql',""));
			$cfg_node_name	=trim(Yii::app()->request->getParam('node_name',""));
			
			//$sql=str_ireplace(array("insert ","update ","delete "),"",$sql);
			if(empty($sql)==false && empty($cfg_node_name)==false) {
				$queryList=LunaPdo::GetInstance($cfg_node_name)->query_with_prepare($sql,array());
				if(isset($queryList) && is_array($queryList)){
					$this->renderData["result"]=$queryList;
					if($export){
						return $this->export($queryList);
					}
				}
			}
			$this->renderData["sql"]=$sql;
			$this->renderData["node_name"]=$cfg_node_name;
		}
		$this->render("index",$this->renderData);
	}
}
?>