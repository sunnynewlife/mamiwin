<?php

class CInjection
{
	private $_GET_FILTER	="'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?Select|Update.+?SET|Insert\\s+INTO.+?VALUES|(Select|Delete).+?FROM|(Create|Alter|Drop|TRUNCATE)\\s+(TABLE|DATABASE)" ;
	private $_POST_FILTER	="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?Select|Update.+?SET|Insert\\s+INTO.+?VALUES|(Select|Delete).+?FROM|(Create|Alter|Drop|TRUNCATE)\\s+(TABLE|DATABASE)" ;
	private $_COOKIE_FILTER	="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?Select|Update.+?SET|Insert\\s+INTO.+?VALUES|(Select|Delete).+?FROM|(Create|Alter|Drop|TRUNCATE)\\s+(TABLE|DATABASE)" ;
	
	private function  StopAttack($StrFiltKey,$StrFiltValue,$ArrFiltReq)
	{
		if(is_array($StrFiltValue))	{
			$StrFiltValue=implode($StrFiltValue);
		}
		if (preg_match("/".$ArrFiltReq."/is",$StrFiltValue)==1){
			echo "websec notice:Illegal operation!" ;
			Yii::app()->end();
		}
	}
	
	public function CheckRequest()
	{
		foreach($_GET as $key=> $value) {
			$this->StopAttack($key,$value,$this->_GET_FILTER);
		}
		foreach($_POST as $key=> $value){
			$this->StopAttack($key,$value,$this->_POST_FILTER);
		}
		foreach($_COOKIE as $key=> $value){
			$this->StopAttack($key,$value,$this->_COOKIE_FILTER);
		}
	
		$this->CheckUploadFiles();
	}
	
	private $_UPLOAD_FILE_EXTENSION_NAME=array("jpg","JPG","png","PNG","gif","GIF","bmp","BMP","jpeg","JPEG","tif","TIF","zip","7z","doc","pdf");
	
	private function CheckUploadFiles()
	{
		if(isset($_FILES) && is_array($_FILES) && count($_FILES)>0){
			foreach ($_FILES as $uploadedFile){
				if(empty($uploadedFile["name"])==false){
					$file_name=pathinfo($uploadedFile["name"]);
					$extname=strtolower($file_name["extension"]);
					if(in_array($extname, $this->_UPLOAD_FILE_EXTENSION_NAME)==false){
						echo "websec notice:Illegal file upload operation!" ;
						Yii::app()->end();
					}
				}
			}
		}
	}	
}