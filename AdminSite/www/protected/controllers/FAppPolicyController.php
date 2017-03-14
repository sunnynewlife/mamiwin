<?php

class FAppPolicyController extends TableMagtController 
{
	private $_tableName="";
	private $_searchName="";
	private $_next_url="";
	private $_columns=array();
	private $_title="分红管理后台-风控批量冻结账号";
	private $_primaryKey="";
	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
	}
	
	public function actionIndex()
	{
		$submit = trim(Yii::app()->request->getParam('submit'),0);
		if ($submit){
			if(isset($_FILES["PhoneFile"])){
				$files=$_FILES["PhoneFile"];
				if($files["name"]!=""){
					$phoneList=file_get_contents($files["tmp_name"]);
					$phoneList=str_replace(array("\n","\r","，","。",".",";","'","\"","`","~","#","%","*"), ",", $phoneList);
					if(empty($phoneList)==false){
						$phones=explode(",",$phoneList);
						$phoneCount=0;
						$rowCount=0;
						$fAppData=new FAppData();
						foreach ($phones as $p){
							if(empty($p)==false){
								$phoneCount++;
								$rowCount+=$fAppData->updatePromoterPayStateByPhone($p, 0);
							}
						}
						$this->renderData["alert_message"]=sprintf("共有：%s个手机，本次冻结%s个手机。",$phoneCount,$rowCount);
					}
				}
			}			
		}
		$this->render("index",$this->renderData);		
	}
}
?>