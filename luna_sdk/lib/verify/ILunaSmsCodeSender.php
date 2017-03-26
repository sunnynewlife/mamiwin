<?php

interface ILunaSmsCodeSender {
	function init($configure);
	function sendSmsCode($code);
}

