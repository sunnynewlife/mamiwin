<?php
/* 
 * 推送消息到Kafka 测试
 * 1. 用户登录和充值推送 
 */

defined('LUNA_SDK_PATH') || define('LUNA_SDK_PATH', dirname(__FILE__).'/../../../../../luna_sdk');
defined('LUNA_CONF_PATH') || define('LUNA_CONF_PATH', dirname(__FILE__).'/../../../../adminEvnCfgScriptNotify.xml');
require(LUNA_SDK_PATH . DIRECTORY_SEPARATOR . 'LUNA.php');
LunaLoader::$_FORCE_INCLUDE = true;

LunaLoader::import("luna_lib.util.LunaKafka");

class KafkaConsumeCommand extends ScriptBase
{
	public function init()
	{
	}

	public function run($args)
	{
		$offset=1;
		if(is_array($args) && count($args)>0){
			$offset=$args[0];
		}
		
		$kafka=new LunaKafka();
		
		$result=$kafka->consumeMessage($offset);
		
		var_dump($result);
	}
}
