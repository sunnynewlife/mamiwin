<?php
LunaLoader::import("luna_lib.util.LunaMemcache");
LunaLoader::import("luna_core.LunaConfigMagt");

class EvnCacheController extends TableMagtController 
{
	public function actionIndex()
	{
		$info = Yii::app()->request->getParam("info",0);
		if($info==1){
			echo phpinfo();
		}else{
			$this->render("index",$this->renderData);
		}		
	}
	
	public function actionQuery()
	{
		$return=array("return_code" =>0,"data" =>"" );
		$value = Yii::app()->request->getParam("key",'');
		$cacheNodeName=Yii::app()->request->getParam("cacheNodeName",'');
		$cachePrefixKey=Yii::app()->request->getParam("cachePrefixKey",'');
		if(empty($value)==false){

			$cachedValue =LunaMemcache::GetInstance($cacheNodeName,$cachePrefixKey)->read($value);
			if(isset($cachedValue) &&  $cachedValue!=false){
				$return["data"]= print_r($cachedValue,true);// json_encode($cachedValue);
			}else{
				$return["data"]="";
			}			
		}		
		echo json_encode($return);
	}
	public function actionDelete()
	{
		$return=array("return_code" =>0,"data" =>"" );
		$value = Yii::app()->request->getParam("key",'');
		$cacheNodeName=Yii::app()->request->getParam("cacheNodeName",'');
		$cachePrefixKey=Yii::app()->request->getParam("cachePrefixKey",'');
		if(empty($value)==false){
			if(LunaMemcache::GetInstance($cacheNodeName,$cachePrefixKey)->delete($value)){
				$return["data"]="delete success";
			}
		}
		echo json_encode($return);		
	}
	
	public function actionQueryProfile()
	{
		$return=array("return_code" =>0,"data" =>"" );
		$cacheNodeName=Yii::app()->request->getParam("cacheNodeName",'');
		$cachePrefixKey=Yii::app()->request->getParam("cachePrefixKey",'');
		$value = Yii::app()->request->getParam("key",'');
		if(empty($value)){
			$return["data"]=print_r(LunaMemcache::GetInstance($cacheNodeName,$cachePrefixKey)->geStatusMessage(),true);
		}else{
			$return["data"]=print_r(LunaMemcache::GetInstance($cacheNodeName,$cachePrefixKey)->getExtendedStats($value,"0"),true);
		}
		echo json_encode($return);
	}
}

?>