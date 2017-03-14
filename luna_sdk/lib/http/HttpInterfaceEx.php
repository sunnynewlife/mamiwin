<?php
LunaLoader::import("luna_lib.http.HttpInterface");

class  HttpInterfaceEx extends HttpInterface
{
	public function __construct($ExtralSystemName,$InterfaceName,$overrideKey="",$domainKey="")
	{
		parent::__construct($ExtralSystemName,$InterfaceName);
		
		if(isset($this->_config[$ExtralSystemName])){
			$SystemConfig=$this->_config[$ExtralSystemName];
			if(isset($SystemConfig["OverrideDomain"])){
				$this->_override_domain=$SystemConfig["OverrideDomain"];
				
				if(isset($this->_override_domain) && is_array($this->_override_domain) &&
						empty($overrideKey)==false && empty($domainKey)==false &&
						 isset($this->_override_domain[$overrideKey])){
					$domains=$this->_override_domain[$overrideKey];
					if(isset($domains[$domainKey])){
						$this->_domain=$domains[$domainKey];
					}
				}				
			}
		}
	}
}

?>