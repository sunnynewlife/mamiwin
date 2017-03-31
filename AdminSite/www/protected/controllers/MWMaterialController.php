<?php

class MWMaterialController extends TableMagtController
{
	private $_tableName="Task_Material";
	private $_searchName="";
	private $_next_url="/mWMaterial/index";
	private $_columns=array("Task_Type","Task_Title","Task_Status","Min_Time","Max_Time","Matrial_IDX","Min_Age","Max_Age","Child_Gender","Parent_Gender","Parent_Marriage","Only_Children","Matrial_IDX");
	private $_title="父母赢管理后台-任务库定义";
	private $_primaryKey="IDX";
	
	protected $_EXTRA_SEARCH_FIELDS=array(
			"Task_Type" 		=> array("compartion_type" =>"equal","field_name" =>"Task_Type"),
			"Task_Status" 		=> array("compartion_type" =>"equal","field_name" =>"Task_Status"),
			"Min_Time" 			=> array("compartion_type" =>"greater","field_name" =>"Min_Time"),
			"Max_Time" 			=> array("compartion_type" =>"less","field_name" =>"Max_Time"),
			"Min_Age" 			=> array("compartion_type" =>"greater","field_name" =>"Min_Age"),
			"Max_Age" 			=> array("compartion_type" =>"less","field_name" =>"Max_Age"),
			"Child_Gender" 		=> array("compartion_type" =>"equal","field_name" =>"Child_Gender"),
			"Parent_Gender" 	=> array("compartion_type" =>"equal","field_name" =>"Parent_Gender"),
			"Parent_Marriage" 	=> array("compartion_type" =>"equal","field_name" =>"Parent_Marriage"),
			"Only_Children" 	=> array("compartion_type" =>"equal","field_name" =>"Only_Children"),
			"Task_Title" 		=> array("compartion_type" =>"like","field_name" =>"Task_Title"),
	);	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="BizDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
	
	}
	
	public function actionIndex()
	{
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index");
	}
	
	
	public function actionAdd()
	{	
		$submit = trim(Yii::app()->request->getParam('submit',0));
		$mwData=new MWData();
		$Ability_Type=$mwData->getAbility_Type();
		
		if ($submit){			
			$Task_Type =Yii::app()->request->getParam("Task_Type","1");
			$Task_Status=Yii::app()->request->getParam("Task_Status","1");
			$Child_Gender=Yii::app()->request->getParam("Child_Gender","1");
			$Parent_Gender=Yii::app()->request->getParam("Parent_Gender","1");
			$Parent_Marriage=Yii::app()->request->getParam("Parent_Marriage","1");
			$Only_Children=Yii::app()->request->getParam("Only_Children","1");

			$Matrial_IDX=Yii::app()->request->getParam("Matrial_IDX","");
				
			$Task_Title=Yii::app()->request->getParam("Task_Title","");
			$Min_Time=Yii::app()->request->getParam("Min_Time","");
			$Max_Time=Yii::app()->request->getParam("Max_Time","");
			$Min_Age=Yii::app()->request->getParam("Min_Age","");
			$Max_Age=Yii::app()->request->getParam("Max_Age","");
			
			if(empty($Task_Title) || empty($Min_Time) || empty($Max_Time) || empty($Min_Age) || empty($Max_Age)) {
				$this->alert('error',"请正确设置字段");
			}else{
				$AbilityIds=array();
				foreach ($Ability_Type as $rowItem){
					if(Yii::app()->request->getParam(sprintf("Ability_%d",$rowItem["IDX"]),"")=="1"){
						$AbilityIds[]=$rowItem["IDX"];
					}
				}
				if($mwData->insertTask_Material($Task_Type,$Task_Title,$Task_Status,$Min_Time,$Max_Time,$Min_Age,$Max_Age,
						$Child_Gender,$Parent_Gender,$Parent_Marriage,$Only_Children,$Matrial_IDX,$AbilityIds)){
					return $this->exitWithSuccess("增加任务成功",$this->_next_url);
				}
				$this->alert('error',"增加任务失败，请正确设置字段值");
			}
		}
		$this->renderData["Ability_Type"]=$Ability_Type;
		$this->renderData["Material_Files"]=$mwData->getMaterial_Files();
		$this->renderData["Matrial_IDX"]=Yii::app()->request->getParam("Matrial_IDX","");
		$this->render("add",$this->renderData);
	}
	public function actionModify()
	{
		$value = Yii::app()->request->getParam($this->_primaryKey,'');
		if(empty($value)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$submit = trim(Yii::app()->request->getParam('submit',0));
		$mwData=new MWData();
		$Ability_Type=$mwData->getAbility_Type();
		
		if ($submit){			
			$Task_Type =Yii::app()->request->getParam("Task_Type","1");
			$Task_Status=Yii::app()->request->getParam("Task_Status","1");
			$Child_Gender=Yii::app()->request->getParam("Child_Gender","1");
			$Parent_Gender=Yii::app()->request->getParam("Parent_Gender","1");
			$Parent_Marriage=Yii::app()->request->getParam("Parent_Marriage","1");
			$Only_Children=Yii::app()->request->getParam("Only_Children","1");
			
			$Matrial_IDX=Yii::app()->request->getParam("Matrial_IDX","");
			
			$Task_Title=Yii::app()->request->getParam("Task_Title","");
			$Min_Time=Yii::app()->request->getParam("Min_Time","");
			$Max_Time=Yii::app()->request->getParam("Max_Time","");
			$Min_Age=Yii::app()->request->getParam("Min_Age","");
			$Max_Age=Yii::app()->request->getParam("Max_Age","");
				
			if(empty($Task_Title) || empty($Min_Time) || empty($Max_Time) || empty($Min_Age) || empty($Max_Age)) {
				$this->alert('error',"请正确设置字段");
			}else{
				$AbilityIds=array();
				foreach ($Ability_Type as $rowItem){
					if(Yii::app()->request->getParam(sprintf("Ability_%d",$rowItem["IDX"]),"")=="1"){
						$AbilityIds[]=$rowItem["IDX"];
					}
				}
				if($mwData->updateTask_Material($Task_Type,$Task_Title,$Task_Status,$Min_Time,$Max_Time,$Min_Age,$Max_Age,
						$Child_Gender,$Parent_Gender,$Parent_Marriage,$Only_Children,$Matrial_IDX,$AbilityIds,$value)){
					return $this->exitWithSuccess("修改任务成功",$this->_next_url);
				}
				$this->alert('error',"修改任务失败，请正确设置字段值");
			}			
		}
		$this->renderData["Ability_Type"]=$Ability_Type;
		$this->renderData["Material_Files"]=$mwData->getMaterial_Files();
		$this->renderData["Task_Material"]=$mwData->getTask_Material($value);
		$this->renderData["Task_Ability"]=$mwData->getTask_Ability($value);
		
		$this->render("modify",$this->renderData);
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url);
	}
	
	protected function deleteRow($tableName,$primaryKey,$value)
	{
		$mwData=new MWData();
		return $mwData->deleteTask_Material($value);
	}	
	
	public function actionPreview()
	{
		$material_idx=Yii::app()->request->getParam('id',"");
		if(empty($material_idx)==false){
			$mwData=new MWData();
			$download_id=$mwData->getMaterialInfoDownloadByIDX($material_idx);
			$Material_Info=$mwData->getMaterialInfoByDownloadId($download_id);
			if(count($Material_Info)>0){
				$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
				$file_path=$appConfig["Material_Root"]."/".$download_id;
				$File_Content;
				if(file_exists($file_path)==false){
					$Material_Info=$mwData->getMaterialInfoByDownloadId($download_id,true);
					if(count($Material_Info)>0){
						if($material_file=fopen($file_path,"wb")){
							$File_Content=$Material_Info[0]["File_Content"];
							fwrite($material_file,$File_Content);
							fclose($material_file);
						}
					}
				}else{
					if($material_file=fopen($file_path,"rb")){
						$File_Content=fread($material_file,$Material_Info[0]["File_Size"]);
						fclose($material_file);
					}
				}
				if(empty($File_Content)==false){
					switch ($Material_Info[0]["Location_Type"]){
						case "1":
							$html='<!DOCTYPE html><html><head><meta charset="utf-8"><meta content="text/html; charset=utf-8"><title>父母赢-素材文件预览</title></head><body>'.$File_Content.'</body></html>';
							Header("Content-type:".$Material_Info[0]["Mime_Type"]);
							Header("Accept-Ranges: bytes");
							Header("Accept-Length: ".strlen($html));				
							echo $html;
							return;							
						case "3":
							Header("Location:".$File_Content);
							return;
						default:
							Header("Content-type:".$Material_Info[0]["Mime_Type"]);
							Header("Accept-Ranges: bytes");
							Header("Accept-Length: ".$Material_Info[0]["File_Size"]);
							Header("Content-Disposition: attachment; filename=" . $Material_Info[0]["Original_Name"]);
							echo $File_Content;
							return ;
					}
				}
			}
		}
		echo "I'm sorry,cannot find the $material_idx resource.";
	}
	
}
