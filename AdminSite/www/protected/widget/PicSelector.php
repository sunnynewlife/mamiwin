<?php
class PicSelector extends CWidget
{
	public function run() 
	{
		$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
		
		$fAppData=new FAppData();
		$files=$fAppData->getFiles();
		$App=$fAppData->getApp();

		if(isset($files)  && is_array($files) ){
			$dataCount=count($files);
			for($index=0;$index<$dataCount;$index++){
				$files[$index]["SceneName"]=$this->getGameNameByAppId($files[$index]["Scene"], $App);
				$files[$index]["DownloadUrl"]=sprintf("%s%s",$appConfig["FApp_game_domain"],$files[$index]["FileUrl"]);
			}
		}
		$renderData=array("file" => $files,"App"=>$App);
        $this->render('picSelector',$renderData);        
    }
    private function getGameNameByAppId($appId,$appDef)
    {
    	foreach ($appDef as $item){
    		if($item["AppId"]==$appId){
    			return $item["AppName"];
    		}
    	}
    	return $appId;
    }    
   
}

