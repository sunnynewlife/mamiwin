<?php

interface IReportMsgFormat 
{
	function init($config);
	function format($params);
}

?>