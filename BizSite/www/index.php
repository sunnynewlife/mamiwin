<?php
define('YII_ENABLE_ERROR_HANDLER', false);
ini_set("display_errors", "On");
define('YII_DEBUG', true);

//ini_set('session.cookie_domain',".fuwuwin.com");

defined('LUNA_SDK_PATH') || define('LUNA_SDK_PATH', dirname(__FILE__).'/../../luna_sdk');
defined('LUNA_CONF_PATH') || define('LUNA_CONF_PATH', dirname(__FILE__).'/../evnSwitch.xml');

require(LUNA_SDK_PATH . DIRECTORY_SEPARATOR . 'LUNA.php');


$config = dirname(__FILE__).'/protected/config/main.php';
$yii = dirname(__FILE__).'/../framework/yii.php';
require_once($yii);

Yii::$enableIncludePath=false;

Yii::createWebApplication($config)->run();
