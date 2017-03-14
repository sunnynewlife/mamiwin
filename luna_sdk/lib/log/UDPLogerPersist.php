<?php

LunaLoader::import("luna_lib.log.ILunaLoggerPersist");

class UDPLogerPersist implements ILunaLoggerPersist  {
	
	private $_logServerIp;
	private $_logServerPort;
	
	function logMessage($message)
	{
		$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		if (!$socket) {
			return false;
		}
		socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 0, "usec" => 100000));
		$result = socket_connect($socket,$this->_logServerIp,$this->_logServerPort);
		if ($result && is_resource($socket)) {
			socket_write($socket, $message, strlen($message));
		}
		socket_close($socket);
	}
	function init($configs)
	{		
		$this->_logServerIp=$configs["serverIp"];
		$this->_logServerPort=$configs["serverPort"];
	}	
}

?>