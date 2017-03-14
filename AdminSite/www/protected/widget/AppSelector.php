<?php
class AppSelector extends CWidget
{
	public function run() 
	{
		$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
		
		$fAppData=new FAppData();
		$appInfos=$fAppData->getAppInfos();
		$renderData=array("AppInfo" => $appInfos);
        $this->render('appSelector',$renderData);        
    }
}

