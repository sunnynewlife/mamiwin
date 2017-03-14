<?php
class Framework7Header extends CWidget
{
	public function run()
	{
		$iosDeviceAgent=array("ios","iphone","ipod");
		$user_agent=strtolower(" ".Yii::app()->request->userAgent);
		$is_ios_device=false;
		foreach ($iosDeviceAgent as $agent_key ){
			if(stripos($user_agent, $agent_key)){
				$is_ios_device=true;
				break;
			}
		}
		$data=array(
			"device_type"	=>	$is_ios_device?"ios":"material",
		);
		$this->render('Framework7Header',$data);
	}
}