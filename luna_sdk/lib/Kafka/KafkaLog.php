<?php
LunaLoader::import("luna_lib.log.LunaLogger");

class KafkaLog
{
    public static function log($message, $level = LOG_DEBUG)
    {
    	switch ($level){
    		case LOG_DEBUG:
    			LunaLogger::getInstance()->debug($message);
    			break;
    		case LOG_ERR:
    			LunaLogger::getInstance()->error($message);
    			break;
    		default:
    			LunaLogger::getInstance()->info($message);
    	}
    }
}
