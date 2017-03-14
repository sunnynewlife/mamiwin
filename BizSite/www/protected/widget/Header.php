<?php
LunaLoader::import("luna_lib.util.LunaMemcache");

class Header extends CWidget
{
	public function run() 
	{
		$header_session_name="header.info";
		$renderData=array(
			"Title"	=>	"",
			"Url"	=>	"",
		);
		if(isset(Yii::app()->session[$header_session_name]) && is_array(Yii::app()->session[$header_session_name])){
			$renderData=Yii::app()->session["$header_session_name"];
		}
        $this->render('header',$renderData);        
    }
}

