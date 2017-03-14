<?php

interface IReportPersist 
{
	function init($config);
	function save($reportMsg);
}

?>