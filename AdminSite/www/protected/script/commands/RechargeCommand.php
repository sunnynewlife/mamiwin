<?php
error_reporting(0);
/* 
 * 2014-11-06 13:54 
 * 1.每日 22:00 统计当日充值数据，低于阀值，短信报警 相关运营 
 * 
 * yiic.bat help test
 * yiic.bat test
 */

defined('LUNA_SDK_PATH') || define('LUNA_SDK_PATH', dirname(__FILE__).'/../../../../../luna_sdk');
defined('LUNA_CONF_PATH') || define('LUNA_CONF_PATH', dirname(__FILE__).'/../../../../adminEvnCfgScript.xml');
require(LUNA_SDK_PATH . DIRECTORY_SEPARATOR . 'LUNA.php');
LunaLoader::$_FORCE_INCLUDE = true;
LunaLoader::import("luna_lib.util.LunaPdo");
LunaLoader::import("luna_lib.log.LunaLogger");
LunaLoader::import("luna_core.LunaConfigMagt");

require_once(dirname(__FILE__).'/../../../../../luna_sdk/lib/util/CGuidManager.php');
require_once(dirname(__FILE__) . '/../../models/FAppData.php');
require_once(dirname(__FILE__) . '/../../modules/ModuleTrans.php');

require_once(dirname(__FILE__) . '/../../config/ConfAlipay.php');
require_once(dirname(__FILE__) . '/../../libraries/LibAlipay.php');
require_once(dirname(__FILE__) . '/../lib/alipay_submit.class.php');


class RechargeCommand extends ScriptBase
{
	public function run($args)
    {
        //根据传入参数进行调用不同脚本
        if(!empty($args)){
            $arg = $args[0];
            switch ($arg){
                case "ALERT":                 //充值报警
                    $this->RechargeAlert();
                    break;
                case "TEST" :               //测试
                    $this->test();
                    break;
                default :
                    echo("wrong args.");
                    break;
            }            
        }
    }


    public function test(){

    }


    //充值金额阀值报警 ，短信通知相关运营 
    public function RechargeAlert(){
    	$ret = ModuleTrans::RechargeAlert();
    	echo($ret);
        die();
    }	

}