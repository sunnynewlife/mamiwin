<?php

class LunaCfgPwd 
{
	const SECRET_KEY="L@gSd^Cula2$1";
	
	public static function getSecretKey($pwd)
	{
		return md5($pwd.self::SECRET_KEY);
	}
}

?>