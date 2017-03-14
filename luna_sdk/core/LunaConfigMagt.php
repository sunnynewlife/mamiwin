<?php

class LunaConfigMagt 
{
	private static $_instance = null;
	
	public static function getInstance() 
	{
		if (!isset(self::$_instance)) {
			self::$_instance = new self();
			self::$_instance->loadConfiguartions();
		}		
		return self::$_instance;
	}
	
	public function reload()
	{
		return $this->loadConfiguartions();
	} 

	public function getAppConfig()
	{
		return $this->_configs["AppSection"];
	}
	public function getAppid(){
		return $this->_configs["AppSection"]['appid'];
	}
	public function getLunaSDKConfigSection($sectionName)
	{
		return $this->_configs["LunaSection"][$sectionName];
	}
	public function getXmlFileName()
	{
		return $this->_xml_file_name;
	}
	
	private $_configs=array();	
	private $_xml_file_name="";
	private function loadConfiguartions()
	{
		libxml_disable_entity_loader(false);
		$this->_xml_file_name=LUNA_CONF_PATH;
		$xml = new DOMDocument();
		$xml->load(LUNA_CONF_PATH);
		$rootNodes=$xml->getElementsByTagName("LunaConfigMagt");
		if($rootNodes!=null && $rootNodes->length==1)
		{
			$this->parserXml2Array($rootNodes->item(0));
		}
		else 
		{
			$rootNodes=$xml->getElementsByTagName("LunaConfigEvnSwitch");
			if($rootNodes!=null && $rootNodes->length==1)
			{
				$this->_configs=array();
				$this->parserXml2Array($rootNodes->item(0));
				$evnConfFileName=$this->getEvnConfigurationFileName();
				if(isset($evnConfFileName) && empty($evnConfFileName)==false){
					$xmlConfEvnName=sprintf("%s/%s",dirname(LUNA_CONF_PATH),$evnConfFileName);
					$xml = new DOMDocument();
					$xml->load($xmlConfEvnName);
					$rootNodes=$xml->getElementsByTagName("LunaConfigMagt");
					if($rootNodes!=null && $rootNodes->length==1)
					{
						$this->_xml_file_name=$xmlConfEvnName;
						$this->parserXml2Array($rootNodes->item(0));
					}
				}
			}
		}		
	}
	private function getEvnConfigurationFileName()
	{
		if(count($this->_configs)>0 && isset($this->_configs["SwitchConf"]))
		{
			$switchCfg=$this->_configs["SwitchConf"];
			$defaultXml			=$switchCfg["defaultXml"];
			$enableEvnSwitch	=$switchCfg["enableEvnSwitch"];
			if(strtolower($enableEvnSwitch)=="true"){
				$hostName=php_uname("n");
				$ip=gethostbyname($hostName);
				if(isset($_SERVER) && isset($_SERVER["LOCAL_ADDR"])){
					$ip=$_SERVER["LOCAL_ADDR"];
				}
				foreach ($switchCfg["EvnSwitch"] as $fileName => $condEvn){
					if(isset($condEvn["hostName"])){
						$evnHosts=explode(",", $condEvn["hostName"]);
						foreach ($evnHosts as $evnHost){
							if(empty($evnHost) ==false && ($hostName==$evnHost || strpos("A".$hostName,$evnHost))!==false){
								return $fileName;
							}
						}
					}
					if(isset($condEvn["ip"])){
						$evnIps=explode(",", $condEvn["ip"]);
						foreach ($evnIps as $evnIp){
							if(empty($evnIp) ==false && ($ip==$evnIp || strpos("A".$ip,$evnIp))!=false){
								return $fileName;
							}								
						}						
					}						
				}
			}
			return $defaultXml;
		}		
	}
	
	private function parserXml2Array($xmlNode)
	{
		foreach($xmlNode->childNodes as $node)
		{		
			if($node->nodeType==XML_ELEMENT_NODE){
				$arrKey=$node->getAttribute("key");
				if($node->tagName=="array"){
					$nodeArray=$this->getNodeArray($node);
					$this->_configs[$arrKey]=$nodeArray;
				} else if($node->tagName=="string" || $node->tagName=="int"){
					$this->_configs[$key]=$node->getAttribute("value");
				}	 
			}
		} 	
	}
	private function getNodeArray($value)
	{
		$retArray=array();
		foreach($value->childNodes as $node)
		{
			if($node->nodeType==XML_ELEMENT_NODE){
				$arrKey=$node->getAttribute("key");
				if($node->tagName=="array"){
					$nodeArray=$this->getNodeArray($node);
					$retArray[$arrKey]=$nodeArray;
				} else if($node->tagName=="string" || $node->tagName=="int"){
					$retArray[$arrKey]=$node->getAttribute("value");
				}
			}
		}
		return 	$retArray;
	}
}

?>