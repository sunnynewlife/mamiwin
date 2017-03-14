<?php

LunaLoader::import("luna_lib.log.LunaLogger");
LunaLoader::import("luna_core.LunaConfigMagt");

//代码优化Magic Class,用于优化一次 Http Request 处理过程中，某个类、某个方法的多次访问
//仅支持简单类的构造,最多12个参数 

class LunaCodeProfile 
{
	
	private static  $_globalInstance;
	private static  $_instance=array();
	
	public static function GetInstance($proxClassName='')
	{
		if(empty($proxClassName)){
			if(self::$_globalInstance==null){
				self::$_globalInstance=new LunaCodeProfile();
			}
			return self::$_globalInstance;
		}else{
			if(isset(self::$_instance[$proxClassName])==false){
				self::$_instance[$proxClassName]=new LunaCodeProfile($proxClassName);
			}
			return self::$_instance[$proxClassName];
		}
	}
	
	
	private $_configs;

	public $_prox_class_name="";			//代理类的类名
	public $_cached_method=array();			//代里缓存方法名 以及结果
	private $_prox_class_instance=null;		//代理类的实例
	
	private function __construct($proxyClassName="")
	{
		$this->_configs=LunaConfigMagt::getInstance()->getLunaSDKConfigSection("CodeProfile");
		if(isset($this->_configs["defaultClass"])){
			$this->_prox_class_name=$this->_configs["defaultClass"];
		}
		if(empty($proxyClassName)==false){
			$this->_prox_class_name=$proxyClassName;
		}
		if(isset($this->_configs["profileMethods"])){
			foreach ($this->_configs["profileMethods"] as $className =>$proxClassMethods){
				if($className==$this->_prox_class_name){
					foreach ($proxClassMethods as $methodName){
						$this->_cached_method[$methodName]=array();
					}
				}
			}				
		}
	}
	
	public function __call($methodName,$arguments)
	{
		if(empty($methodName)==false){
			if($this->_prox_class_instance==null){
				$this->_prox_class_instance=new $this->_prox_class_name;
			}
			foreach ($this->_cached_method as $cachedMethodName => & $cachingCalled ){
				if($cachedMethodName==$methodName){
					$parameterKey=md5(print_r($arguments,true));
					foreach ($cachingCalled as $cacheKey => $cacheValue ){
						if($cacheKey==$parameterKey){
							return $cacheValue;
						}
					}
					$excuteValue=$this->execute($methodName, $arguments);
					$cachingCalled[$parameterKey]=$excuteValue;
					return $excuteValue;
				}
			}
			return $this->execute($methodName, $arguments);
		}
	}
	private function execute($methodName,$arguments)
	{
		try {
			if(isset($arguments)==false ||  count($arguments)==0){
				return $this->_prox_class_instance->$methodName();
			}
			if(count($arguments)==1){
				return $this->_prox_class_instance->$methodName($arguments[0]);
			}
			if(count($arguments)==2){
				return $this->_prox_class_instance->$methodName($arguments[0],$arguments[1]);
			}
			if(count($arguments)==3){
				return $this->_prox_class_instance->$methodName($arguments[0],$arguments[1],$arguments[2]);
			}
			if(count($arguments)==4){
				return $this->_prox_class_instance->$methodName($arguments[0],$arguments[1],$arguments[2],$arguments[3]);
			}
			if(count($arguments)==5){
				return $this->_prox_class_instance->$methodName($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4]);
			}
			if(count($arguments)==6){
				return $this->_prox_class_instance->$methodName($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4],$arguments[5]);
			}
			if(count($arguments)==7){
				return $this->_prox_class_instance->$methodName($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4],$arguments[5],$arguments[6]);
			}
			if(count($arguments)==8){
				return $this->_prox_class_instance->$methodName($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4],$arguments[5],$arguments[6],$arguments[7]);
			}
			if(count($arguments)==9){
				return $this->_prox_class_instance->$methodName($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4],$arguments[5],$arguments[6],$arguments[7],$arguments[8]);
			}
			if(count($arguments)==10){
				return $this->_prox_class_instance->$methodName($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4],$arguments[5],$arguments[6],$arguments[7],$arguments[8],$arguments[9]);
			}
			if(count($arguments)==11){
				return $this->_prox_class_instance->$methodName($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4],$arguments[5],$arguments[6],$arguments[7],$arguments[8],$arguments[9],$arguments[10]);
			}
			if(count($arguments)==12){
				return $this->_prox_class_instance->$methodName($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4],$arguments[5],$arguments[6],$arguments[7],$arguments[8],$arguments[9],$arguments[10],$arguments[11]);
			}			
		} catch (Exception $e) {
			$logMessage=sprintf("<LunaCodeProfile>\t<%s>\t<%s>\t<%s>\t<%s>",$this->_prox_class_name,$methodName,$e->getMessage(),print_r($arguments,true));
			LunaLogger::getInstance()->error($logMessage);
		}
	}	
}

