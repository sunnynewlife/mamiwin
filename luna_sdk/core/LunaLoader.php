<?php

class LunaLoader 
{
	
	public  static $enableIncludePath=true;
	private static $_classMap = array();
	private static $_aliases = array();
	private static $_imports = array(); 		
	private static $_includePaths;
	
	public  static $_FORCE_INCLUDE=false;
	
	public static function setPathOfAlias($alias, $path) 
	{
		if (empty($path)) {
			unset(self::$_aliases[$alias]);
		} else {
			self::$_aliases[$alias] = rtrim($path,'\\/');
		}
	}
	
	public static function getPathOfAlias($alias) 
	{
		if (isset(self::$_aliases[$alias])) {
			return self::$_aliases[$alias];
		} elseif (($pos=strpos($alias,'.'))!==false) {
			$rootAlias = substr($alias,0,$pos);
			if (isset(self::$_aliases[$rootAlias])) {
				return self::$_aliases[$alias] = rtrim(
						self::$_aliases[$rootAlias] . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, substr($alias,$pos+1)),
						'*' . DIRECTORY_SEPARATOR);
			}
		}
		return false;
	}
	
	public static function import($alias, $force_include = false) 
	{
		if(self::$_FORCE_INCLUDE){
			$force_include=self::$_FORCE_INCLUDE;
		}
		if (isset(self::$_imports[$alias])) {		
			return self::$_imports[$alias];
		}
		if (class_exists($alias, false) || interface_exists($alias, false)) {
			return self::$_imports[$alias] = $alias;
		}
		if (($pos=strrpos($alias,'.'))===false && $force_include && self::autoload($alias)) { 
			return  self::$_imports[$alias]=$alias;
		}
	
		$className = (string)substr($alias, $pos+1);
		$isClass = $className!=='*';
		if ($isClass && (class_exists($className, false) || interface_exists($className, false))) {
			return self::$_imports[$alias]=$className;
		}

		if (($path=self::getPathOfAlias($alias)) !== false) {
			if ($isClass) {
				if ($force_include) {
					if(is_file($path.'.php')) {
						include_once($path.'.php');
					} else {
						throw new Exception("Alias {$alias} is invalid");
					}
					self::$_imports[$alias] = $className;
				} else {
					self::$_classMap[$className] = $path.'.php';
				}
				return $className;
			} else {
				if (self::$_includePaths===null) {
					self::$_includePaths = array_unique(explode(PATH_SEPARATOR, get_include_path()));
					if (($pos=array_search('.',self::$_includePaths,true))!==false) {
						unset(self::$_includePaths[$pos]);
					}
				}
				array_unshift(self::$_includePaths, $path);
				if (self::$enableIncludePath && set_include_path('.'.PATH_SEPARATOR . implode(PATH_SEPARATOR, self::$_includePaths))===false) {
					self::$enableIncludePath = false;
				}
				return self::$_imports[$alias] = $path;
			}
		}
	}
	
	public static function autoload($className) {
		if (isset(self::$_classMap[$className])) {
			require(self::$_classMap[$className]);
			return true;
		}
		
		if (self::$enableIncludePath===false) {
			if (is_array(self::$_includePaths)) {
				foreach (self::$_includePaths as $path) {
					$classFile = $path.DIRECTORY_SEPARATOR.$className.'.php';
					if (is_file($classFile)) {
						require($classFile);
						break;
					}
				}
			}
		} else {
			require($className.'.php');
		}	
		return class_exists($className, false) || interface_exists($className, false);
	}
}
?>