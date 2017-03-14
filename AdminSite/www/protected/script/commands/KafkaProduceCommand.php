<?php
/* 
 * 推送消息到Kafka 测试
 * 1. 用户登录和充值推送 
 */

defined('LUNA_SDK_PATH') || define('LUNA_SDK_PATH', dirname(__FILE__).'/../../../../../luna_sdk');
defined('LUNA_CONF_PATH') || define('LUNA_CONF_PATH', dirname(__FILE__).'/../../../../adminEvnCfgScriptNotify.xml');
require(LUNA_SDK_PATH . DIRECTORY_SEPARATOR . 'LUNA.php');
LunaLoader::$_FORCE_INCLUDE = true;

LunaLoader::import("luna_lib.util.LunaPdo");
LunaLoader::import("luna_lib.log.LunaLogger");

LunaLoader::import("luna_lib.Kafka.KafkaQueue");

LunaLoader::import("luna_lib.util.LunaKafka");

require_once(dirname(__FILE__) .'/../../models/FAppDepend.php');
require_once(dirname(__FILE__) . '/../../models/FAppData.php');

class KafkaProduceCommand extends ScriptBase
{
	public function init()
	{
	}

	public function run($args)
	{
		$messages=$args[0];
		if(empty($messages)){
			$messages="KafkaProduceCommand Test.";
		}
		$messages=str_replace(array("\n","\r"), "", $messages);
	
		$kafka=new LunaKafka();
		
		$result=$kafka->produceMessage($messages);
		
		var_dump($result);
	}
	
	public function run_old($args)
	{
		$messages=$args[0];
		if(empty($messages)){
			$messages="KafkaProduceCommand Test.";
		}
		$messages=str_replace(array("\n","\r"), "", $messages);
		
		$kafka=new KafkaQueue();
		$result=$kafka->sendMessageEx($messages);
		if($result==-1){
			echo  "Send kafka msg failure.";
		}else{
			echo  "Send kafka msg succuess,position offset is ".$result;
		}
	}
}
