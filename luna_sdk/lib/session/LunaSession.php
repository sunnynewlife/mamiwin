<?php

LunaLoader::import("luna_core.LunaConfigMagt");
LunaLoader::import("luna_lib.util.LunaMemcache");

class LunaSession extends CHttpSession
{
	const CACHE_KEY_PREFIX='LunaSession.';
	private $_cache;

	public function init()
	{
		$this->_cache=LunaMemcache::GetInstance("LunaSession"); 
		parent::init();
	}
	public function getUseCustomStorage()
	{
		return true;
	}
	public function readSession($id)
	{
		$data=$this->_cache->read($this->calculateKey($id));
		return ($data===false?'':$data);
	}

	public function writeSession($id,$data)
	{
		return $this->_cache->write($this->calculateKey($id),$data,$this->getTimeout());
	}
	public function destroySession($id)
	{
	    return $this->_cache->delete($this->calculateKey($id));
	}
	protected function calculateKey($id)
	{
	    return self::CACHE_KEY_PREFIX.$id;
	}
}
