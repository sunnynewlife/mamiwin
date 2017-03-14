<?php
LunaLoader::import("luna_core.LunaConfigMagt");
LunaLoader::import("luna_lib.log.LunaLogger");

class LunaMemcache 
{
	private $_configs;
	
	private $_host;
	private $_conn;
	private $_prefixKey;
	
	private static $_instance=array();
	private static $_globalInstance;	
	
	public static function GetInstance($cfgNodeName='',$prefixKey='')
	{
		if(empty($cfgNodeName)){
			if(self::$_globalInstance==null){
				self::$_globalInstance=new LunaMemcache('',$prefixKey);
			}
			return self::$_globalInstance;
		}else{
			if(isset(self::$_instance[$cfgNodeName])==false){
				self::$_instance[$cfgNodeName]=new LunaMemcache($cfgNodeName,$prefixKey);
			}
			return self::$_instance[$cfgNodeName];			
		}		
	}
	
	private function __construct($cfgNodeName='',$prefixKey='')
	{
		$this->_configs=LunaConfigMagt::getInstance()->getLunaSDKConfigSection("Memcache");
		$globalCfg=$this->_configs["global"];
		$this->_prefixKey=$globalCfg["prefixKey"];
		foreach ($globalCfg["host"] as $hostCfg){
			$this->_host[]=array("ip" =>$hostCfg["ip"],"port" => $hostCfg["port"]);
		}
		if(empty($cfgNodeName)==false && isset($this->_configs["special"])){
			foreach ($this->_configs["special"] as $nodeName => $nodeCfg){
				if($nodeName==$cfgNodeName){
					if(isset($nodeCfg["prefixKey"])){
						$this->_prefixKey=$nodeCfg["prefixKey"];
					}
					if(isset($nodeCfg["host"])){
						$newHost=array();
						foreach ($nodeCfg["host"] as $nodeHostCfg){
							$newHost[]=array("ip" =>$nodeHostCfg["ip"],"port" => $nodeHostCfg["port"]);
						}
						if(count($newHost)>0){
							$this->_host=$newHost;
						}
					}						
					break;					
				}				
			}
		}
		if(empty($prefixKey)==false){
			$this->_prefixKey=$prefixKey;
		}
		ini_set('memcache.hash_strategy', 'consistent');
		ini_set('memcache.hash_function', 'fnv');

		$this->_conn = new Memcache();
		foreach ($this->_host as $hostCfg){
			$ret = $this->_conn->addServer($hostCfg['ip'], $hostCfg['port'], false, 1, 3, -1);
			if($ret === false) {
				$logMsg=sprintf("<LunaMemcache>\tConnect failure:%s:%s",$hostCfg['host'],$hostCfg['port']);
				LunaLogger::getInstance()->error($logMsg);
			}
		}
		$this->_conn->setCompressThreshold(20000, 0.2);		
	}
	/**
	* 	批量获取 以prefixKey 为前缀的 memcache value 
	*	慎用！！！！！！
	*/
	function getMemcacheAllKeys($prefixKey,$op='') {
		$mc_keys=array();
		$serach_keys=array();
	    $allSlabs = $this->_conn->getExtendedStats('slabs');
	    foreach($allSlabs as $server => $slabs) {
	        foreach($slabs AS $slabId => $slabMeta) {
	           $cdump = $this->_conn->getExtendedStats('cachedump',(int)$slabId);
	            foreach($cdump AS $keys => $arrVal) {
	                if (!is_array($arrVal)) continue;
	                foreach($arrVal AS $k => $v) {                 
	                	$mc_keys[]=$k;
	                }
	           }
	        }
	    }   
	    array_unique($mc_keys);
	    foreach($mc_keys as $s ){
	    	if (strpos( $s , $prefixKey ) === 0 ){
	    		$serach_keys[]=$s;
	    		if($op=='delete'){
	    			self::delete($s);
	    		}
	    	}
	    		
	    }
	    return $serach_keys;
	}
	private function _getCacheKey($key)
	{
		if (is_array($key)) {
			$cacheKey=array();
			foreach ($key as $v) {
				$cacheKey[] = $this->_prefixKey.$v;
			}
			return $cacheKey;
		} else {
			return $this->_prefixKey.$key;
		}
	}
	
	public function read($key)
	{
		if(!$key) {
			return false;
		}			
		$cacheKey=$this->_getCacheKey($key);
		return $this->_conn->get($cacheKey);
	}
	public function write($key, $val, $expire = 0)
	{
		if(!$key) {
			return false;
		}
		$cacheKey=$this->_getCacheKey($key);
		return $this->_conn->set($cacheKey, $val, 0, $expire);
	}
	public function increment($key, $value=1)
	{
		if(!$key) {
			return false;
		}
		$cacheKey=$this->_getCacheKey($key);
		return $this->_conn->increment($cacheKey, $value);
	}
	public function decrement($key, $value=1)
	{
		if(!$key) {
			return false;
		}
		$cacheKey=$this->_getCacheKey($key);
		return $this->_conn->decrement($cacheKey, $value);
	}	
	public function delete($key)
	{
		if(!$key) {
			return false;
		}
		$cacheKey=$this->_getCacheKey($key);
		return $this->_conn->delete($cacheKey);
	}	
}

