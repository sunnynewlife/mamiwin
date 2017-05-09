<?php
LunaLoader::import("luna_lib.util.CGuidManager");

class EvQuestionsController extends TableMagtController 
{
	private $_tableName="Evaluation_Questions";
	private $_searchName="";
	private $_next_url="/evQuestions/index";
	private $_columns=array("Question_Stems","Option_A","Option_B","Option_C","Option_D","Option_E","Option_F","Point_A","Point_B","Point_C","Point_D","Point_E","Point_F");
	private $_title="素材资料";
	private $_primaryKey="IDX";
	
	protected $_EXTRA_SEARCH_FIELDS=array(
		"File_Type" 	=> array("compartion_type" =>"equal","field_name" =>"File_Type"),
		"File_Title" 	=> array("compartion_type" =>"like","field_name" =>"Question_Stems"),
	);
	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="BizDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
	
	}
	
	public function actionIndex()
	{	
		$this->_actionIndex("v_Questions", $this->_searchName, $this->_next_url, $this->_tableName,"index");
		// $EvaluationQuestionsSetData	=new EvaluationQuestionsSetData();
		// $Evaluation_Questions_List = 

		// $this->renderData['users']= $users;
  //   	$this->renderData['page']= $page_str;
  //   	$this->renderData['Evaluation_Questions'] = $Evaluation_Questions;
  //   	$this->render('indx',$this->renderData);
    // }

	}
	public function actionAdd()
	{	
		$submit = trim(Yii::app()->request->getParam('submit',0));
		$EvaluationQuestionsSetData	=new EvaluationQuestionsSetData();
		$EvaluationQuestionsSet_List=$EvaluationQuestionsSetData->getEvaluationQuestionsSetList();
		$mwData=new MWData();
		$Ability_Type=$mwData->getAbility_Type();
		
		$EvQuestionData=new EvQuestionData();
		if ($submit){			
			$Question_Set_IDX =Yii::app()->request->getParam("Question_Set","");
			$Ability_Type_ID=Yii::app()->request->getParam("Ability_Type_ID","");
			$Question_Stems=Yii::app()->request->getParam("Question_Stems","");
			$Option_A=Yii::app()->request->getParam("Option_A","");
			$Option_B=Yii::app()->request->getParam("Option_B","");
			$Option_C=Yii::app()->request->getParam("Option_C","");
			$Option_D=Yii::app()->request->getParam("Option_D","");
			$Option_E=Yii::app()->request->getParam("Option_E","");
			$Option_F=Yii::app()->request->getParam("Option_F","");
			$Point_A=Yii::app()->request->getParam("Point_A","");
			$Point_B=Yii::app()->request->getParam("Point_B","");
			$Point_C=Yii::app()->request->getParam("Point_C","");
			$Point_D=Yii::app()->request->getParam("Point_D","");
			$Point_E=Yii::app()->request->getParam("Point_E","");
			$Point_F=Yii::app()->request->getParam("Point_F","");

			if(empty($Question_Stems) || empty($Option_A) || empty($Option_B) || empty($Point_A) ) {
				$this->alert('error',"请正确设置评测题及选项AB");
			}else{
				//查询当前题集已有题目数
				$Question_List = $EvQuestionData->getEvQuestionList($Question_Set_IDX);
				$Order_Index = (count($Question_List) + 1) * 10 ;
				if($EvQuestionData->insertEvQuestion($Question_Set_IDX,$Ability_Type_ID,$Question_Stems,$Option_A,$Option_B,$Option_C,$Option_D,$Option_E,$Option_F,$Point_A,$Point_B,$Point_C,$Point_D,$Point_E,$Point_F,$Order_Index)){
					$next_url = "/evQuestions/add";
					return $this->exitWithSuccess("增加评测题成功",$next_url);
				}
				$this->alert('error',"增加评测题失败，请正确设置字段值");
			}
		}
		$this->renderData["Evaluation_Quesitons"]=$EvQuestionData->getEvQuestion();
		$this->renderData["EvaluationQuestionsSet_List"]=$EvaluationQuestionsSet_List;
		$this->renderData["Ability_Type"]=$Ability_Type;
		$this->render("add",$this->renderData);
	}
	public function actionModify()
	{
		$value = Yii::app()->request->getParam($this->_primaryKey,'');
		if(empty($value)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$submit = trim(Yii::app()->request->getParam('submit',0));
		$EvQuestionData=new EvQuestionData();$EvaluationQuestionsSetData	=new EvaluationQuestionsSetData();
		$EvaluationQuestionsSet_List=$EvaluationQuestionsSetData->getEvaluationQuestionsSetList();
		$mwData=new MWData();
		$Ability_Type=$mwData->getAbility_Type();

		if ($submit){			
			$Question_Set_IDX =Yii::app()->request->getParam("Question_Set","");
			$Ability_Type_ID =Yii::app()->request->getParam("Ability_Type_ID","");
			$Question_Stems=Yii::app()->request->getParam("Question_Stems","");
			$Option_A=Yii::app()->request->getParam("Option_A","");
			$Option_B=Yii::app()->request->getParam("Option_B","");
			$Option_C=Yii::app()->request->getParam("Option_C","");
			$Option_D=Yii::app()->request->getParam("Option_D","");
			$Option_E=Yii::app()->request->getParam("Option_E","");
			$Option_F=Yii::app()->request->getParam("Option_F","");
			$Point_A=Yii::app()->request->getParam("Point_A","");
			$Point_B=Yii::app()->request->getParam("Point_B","");
			$Point_C=Yii::app()->request->getParam("Point_C","");
			$Point_D=Yii::app()->request->getParam("Point_D","");
			$Point_E=Yii::app()->request->getParam("Point_E","");
			$Point_F=Yii::app()->request->getParam("Point_F","");
			$Order_Index=Yii::app()->request->getParam("Order_Index",0);
				
			if(empty($Question_Stems) || empty($Option_A) || empty($Option_B) || empty($Point_A) ) {
				$this->alert('error',"请正确设置字段");
			}else{
				// $AbilityIds=array();
				// foreach ($Ability_Type as $rowItem){
				// 	if(Yii::app()->request->getParam(sprintf("Ability_%d",$rowItem["IDX"]),"")=="1"){
				// 		$AbilityIds[]=$rowItem["IDX"];
				// 	}
				// }
				if($EvQuestionData->updateEvQuestion($Question_Set_IDX,$Ability_Type_ID,$Question_Stems,$Option_A,$Option_B,$Option_C,$Option_D,$Option_E,$Option_F,$Point_A,$Point_B,$Point_C,$Point_D,$Point_E,$Point_F,$Order_Index,$value)){
					return $this->exitWithSuccess("修改评测题成功",$this->_next_url);
				}
				$this->alert('error',"修改评测题失败，请正确设置字段值");
			}			
		}
		// $this->renderData["Ability_Type"]=$Ability_Type;
		// $this->renderData["Material_Files"]=$EvQuestionData->getMaterial_Files();
		// $this->renderData["Task_Material"]=$EvQuestionData->getTask_Material($value);
		// $this->renderData["Task_Ability"]=$EvQuestionData->getTask_Ability($value);
		$Evaluation_Quesiton = $EvQuestionData->getEvQuestionByIDX($value);
		$this->renderData["Evaluation_Quesiton"]=$Evaluation_Quesiton;
		$this->renderData["Ability_Type"]=$Ability_Type;
		$this->renderData["EvaluationQuestionsSet_List"]=$EvaluationQuestionsSet_List;

		$this->render("modify",$this->renderData);
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url);
	}
	
	protected function deleteRow($tableName,$primaryKey,$value)
	{
		$EvQuestionData=new EvQuestionData();
		return $EvQuestionData->delEvQuestion($value);
	}	
	
	public function actionPreview()
	{
		$material_idx=Yii::app()->request->getParam('id',"");
		if(empty($material_idx)==false){
			$EvQuestionData=new EvQuestionData();
			$download_id=$EvQuestionData->getMaterialInfoDownloadByIDX($material_idx);
			$Material_Info=$EvQuestionData->getMaterialInfoByDownloadId($download_id);
			if(count($Material_Info)>0){
				$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
				$file_path=$appConfig["Material_Root"]."/".$download_id;
				$File_Content;
				if(file_exists($file_path)==false){
					$Material_Info=$EvQuestionData->getMaterialInfoByDownloadId($download_id,true);
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
