<?php

interface ILunaImgCodeDrawer {
	function init($configure,$width,$height);
	function getImgDatas($code);
	function getImgContentType();
}

?>