<?php
LunaLoader::import("luna_lib.util.CGuidManager");

class MWFilesController extends TableMagtController 
{
	private $_tableName="Material_Files";
	private $_searchName="";
	private $_next_url="/mWFiles/index";
	private $_columns=array("File_Title","File_Type","Location_Type");
	private $_title="素材资料";
	private $_primaryKey="IDX";
	
	protected $_EXTRA_SEARCH_FIELDS=array(
		"File_Type" 	=> array("compartion_type" =>"equal","field_name" =>"File_Type"),
		"Download_Id" 	=> array("compartion_type" =>"like","field_name" =>"Download_Id"),
		"File_Title" 	=> array("compartion_type" =>"like","field_name" =>"File_Title"),
	);
	
	
	public function init()
	{
		$this->_PDO_NODE_NAME="BizDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
	
	}
	
	public function actionIndex()
	{
		$this->_actionIndex("V_Material_Files", $this->_searchName, $this->_next_url, $this->_tableName,"index");
	}
	public function actionAdd()
	{

		$submit = trim(Yii::app()->request->getParam('submit',0));
		if ($submit){
			$File_Type =Yii::app()->request->getParam("File_Type","1");
			$File_Title=Yii::app()->request->getParam("File_Title","");
			$Location_Type=Yii::app()->request->getParam("Location_Type","1");
			if(empty($File_Title)){
				$this->alert('error',"请输入文件内容标题");
			}else{
				$bParameterReady=false;
				switch ($Location_Type){
					case "1":
						$Mime_Type="text/html";
						$File_Name="";
						$File_Content=Yii::app()->request->getParam("Article_Content","");
						if(empty($File_Content)==false){
							$File_Size=strlen($File_Content);
							$bParameterReady=true;
						}
						break;
					case "2":
						if(isset($_FILES["File_Content"])){
							$files=$_FILES["File_Content"];
							if(isset($files["error"]) && $files["error"]==0){
								$File_Name=$files["name"];
								$Mime_Type=$files["type"];
								$File_Size=$files["size"];
								$File_Path=$files["tmp_name"];
								$File_Content = fread(fopen($files["tmp_name"], "r"), $File_Size);
								$bParameterReady=true;
							}
						}						
						break;
					case "3":
						$Mime_Type="application/octet-stream";
						$File_Name="";
						$File_Content=Yii::app()->request->getParam("File_Content_URL","");
						if(empty($File_Content)==false){
							$File_Size=strlen($File_Content);
							$bParameterReady=true;
						}						
						break;
					default:
						break;
				}
				if($bParameterReady)
				{
					$Download_Id=CGuidManager::GetFullGuid();
					$mwData=new MWData();
					$lastInsertID = $mwData->insertMaterial_Files($File_Title, $File_Type,$Location_Type,$Mime_Type,$File_Name,$File_Size, $Download_Id, $File_Content);
					if($lastInsertID){						
						$next_url = "/mWMaterial/add?Matrial_IDX=".$lastInsertID ;
						return $this->exitWithSuccess(sprintf("增加%s成功",$this->_title),$next_url);
					}						
				}
				$this->alert('error',"请选择上传的文件");
			}
		}
		$this->render("add",$this->renderData);		
	}
	public function actionModify()
	{
		$value = Yii::app()->request->getParam($this->_primaryKey,'');
		if(empty($value)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$rowData=$this->getOneRowByFieldName("V_Material_Files", $this->_primaryKey, $value);
		if($rowData==false || is_array($rowData)==false || count($rowData)==0){
			$this->exitWithError("参数值错误",$this->_next_url);
		}
		$this->renderData[$this->_tableName]=$rowData;
		$submit = trim(Yii::app()->request->getParam('submit',0));
		$mwData=new MWData();
		if($submit){
			$File_Type =Yii::app()->request->getParam("File_Type","1");
			$File_Title=Yii::app()->request->getParam("File_Title","");
			$Location_Type=Yii::app()->request->getParam("Location_Type","1");			
			if(empty($File_Title)){
				$this->alert('error',"请输入文件内容标题");
			}else{
				$bParameterReady=false;
				$bUpdateFileContent=false;
				switch ($Location_Type){
					case "1":
						$Mime_Type="text/html";
						$File_Name="";
						$File_Content=Yii::app()->request->getParam("Article_Content","");
						if(empty($File_Content)==false){
							$File_Size=strlen($File_Content);
							$bParameterReady=true;
							$bUpdateFileContent=true;
						}
						break;
					case "2":
						$bParameterReady=true;
						if(isset($_FILES["File_Content"])){
							$files=$_FILES["File_Content"];
							if(isset($files["error"]) && $files["error"]==0){
								$File_Name=$files["name"];
								$Mime_Type=$files["type"];
								$File_Size=$files["size"];
								$File_Path=$files["tmp_name"];
								$File_Content = fread(fopen($files["tmp_name"], "r"), $File_Size);
								$bUpdateFileContent=true;
							}
						}
						break;
					case "3":
						$Mime_Type="application/octet-stream";
						$File_Name="";
						$File_Content=Yii::app()->request->getParam("File_Content_URL","");
						if(empty($File_Content)==false){
							$File_Size=strlen($File_Content);
							$bParameterReady=true;
							$bUpdateFileContent=true;
						}
						break;
					default:
						break;
				}
				if($bParameterReady){
					$Download_Id=CGuidManager::GetFullGuid();
					if($bUpdateFileContent){
						if($mwData->updateMaterial_Files_All($File_Title, $File_Type,$Location_Type,$Mime_Type,$File_Name,$File_Size, $Download_Id, $File_Content,$value)>0){
							return $this->exitWithSuccess(sprintf("修改%s成功",$this->_title),$this->_next_url);
						}
					}else{
						if($mwData->updateMaterial_Files($File_Title, $File_Type,$value)>0){
							return $this->exitWithSuccess(sprintf("修改%s成功",$this->_title),$this->_next_url);
						}
					}
					
				}
				$this->alert('error',sprintf("修改%s失败",$this->_title));
			}
		}
		$this->renderData["Material_Content"]="";
		if($rowData["Location_Type"]==1|| $rowData["Location_Type"]==3){
			$Material_Content_Info=$mwData->getMaterialInfoByIDX($value);
			if(count($Material_Content_Info)>0){
				$this->renderData["Material_Content"]=$Material_Content_Info[0]["File_Content"];
			}
		}
		$this->render("modify",$this->renderData);
	}
	public function actionDel()
	{
		$this->_actionDel($this->_tableName, $this->_primaryKey, $this->_title, $this->_next_url);
	}	
	
	public function actionUploadImage()
	{
		if(isset($_FILES) && is_array($_FILES) && count($_FILES)>0){
			foreach ($_FILES as $uploadedFile){
				if(empty($uploadedFile["name"])==false){
					$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
					$imgName=CGuidManager::GetFullGuid().".jpg";
					$img_path=$appConfig["UploadImage_Root"]."/".$imgName;
					if(copy($uploadedFile["tmp_name"],$img_path)){
						$img_url=$appConfig["UploadImage_Domain"]."/".$imgName;
						echo json_encode(array(
							"return_code"	=>	0,
							"url"			=>	$img_url,
						));
						return;
					}
				}
			}
		}	
		echo json_encode(array(
			"return_code"	=>	-1,
			"message"		=>	"图片上传失败",
		));
	}


	public function actionIsShowIndex(){
		$value = Yii::app()->request->getParam($this->_primaryKey,'');
		$Is_Show_Index=Yii::app()->request->getParam("Is_Show_Index");
			if(empty($value)){
			$this->exitWithError("参数错误",$this->_next_url);
		}
		$Is_Show_Index = (empty($Is_Show_Index)) ? 1 :0 ;
		$mwData=new MWData();
		if($mwData->updateIsShowIndex($Is_Show_Index,$value)>0){
			return $this->exitWithSuccess(sprintf("修改%s成功",$this->_title),$this->_next_url);
		}
	}
}
