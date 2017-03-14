<?php

LunaLoader::import("luna_lib.report.IReportPersist");

class LunaWebReportUDPPersist implements IReportPersist  
{
	private $_config;
	private $_serverIp;
	private $_port;
	
	public function init($config)
	{
		$this->_config=$config;
		$this->_serverIp=$this->_config["serverIp"];
		$this->_port=$this->_config["serverPort"];	
	}
	public function save($msg)
	{
		$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		if (!$socket){
			return false;
		}
		socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 0, "usec" => 100000));
		$result = socket_connect($socket, $this->_serverIp, $this->_port);
		if ($result && is_resource($socket)){
			socket_write($socket, $msg, strlen($msg));
		}
		socket_close($socket);		
	}
}

?>