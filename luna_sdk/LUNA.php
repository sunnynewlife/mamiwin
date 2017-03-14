<?php
defined('LUNA_SDK_PATH') || define('LUNA_SDK_PATH', dirname(__FILE__));
require_once LUNA_SDK_PATH.DIRECTORY_SEPARATOR."core".DIRECTORY_SEPARATOR."LunaLoader.php";

spl_autoload_register(array('LunaLoader','autoload'));
LunaLoader::$enableIncludePath = false;

LunaLoader::setPathOfAlias('luna_core', LUNA_SDK_PATH . DIRECTORY_SEPARATOR . 'core');
LunaLoader::setPathOfAlias('luna_lib', LUNA_SDK_PATH . DIRECTORY_SEPARATOR . 'lib');
LunaLoader::setPathOfAlias('luna_util', LUNA_SDK_PATH . DIRECTORY_SEPARATOR . 'util');

//导出类
LunaLoader::import("luna_core.LunaConfigMagt",true);
LunaLoader::import("luna_lib.log.YIILogerPersist");
LunaLoader::import("luna_lib.log.YIILogerDbPersist");		
LunaLoader::import("luna_lib.session.LunaSession");				
LunaLoader::import("luna_lib.cfg.LunaCfgBaseController");

