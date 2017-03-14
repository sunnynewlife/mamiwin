<?php
abstract class ModelBase 
{
	const DB_CFG_NODE_NAME="Admin";	
	private static $_instace = array ();	
	public static function instance($className = __CLASS__) 
	{
		if (isset ( self::$_instace [$className] )) {
			return self::$_instace [$className];
		} else {
			$model = self::$_instace [$className] = new $className ( null );
			return $model;
		}
	}
}