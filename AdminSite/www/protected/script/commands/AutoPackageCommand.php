<?php
/* 
 * 检查渠道包库存量，低于阀值提交打包任务 
 */

defined('LUNA_SDK_PATH') || define('LUNA_SDK_PATH', dirname(__FILE__).'/../../../../../luna_sdk');
defined('LUNA_CONF_PATH') || define('LUNA_CONF_PATH', dirname(__FILE__).'/../../../../adminEvnCfgScript.xml');
require(LUNA_SDK_PATH . DIRECTORY_SEPARATOR . 'LUNA.php');
LunaLoader::$_FORCE_INCLUDE = true;

LunaLoader::import("luna_lib.util.LunaPdo");
LunaLoader::import("luna_lib.log.LunaLogger");

require_once(dirname(__FILE__) .'/../../models/FAppDepend.php');
require_once(dirname(__FILE__) . '/../../models/FAppData.php');

require_once(dirname(__FILE__) . '/../../modules/AutoPacking.php');

class AutoPackageCommand extends ScriptBase
{
	public function init()
	{
	}
	
	public function run($args)
	{
		$packingTask=new AutoPacking();
		$packingTask->run();
	}
}
