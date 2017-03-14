<?php

interface ILunaLoggerPersist 
{
	function logMessage($message);
	function init($configs);
}

?>