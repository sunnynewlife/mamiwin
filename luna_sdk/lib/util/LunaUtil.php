<?php

class LunaUtil 
{
	//低于5.4 版本的 json_encode 函数的替代，对于中文不转换成 \u 形式表示
	public static function json_encodeEx($data)
	{
		return urldecode(json_encode(self::urlEncodeExt($data)));
	}
	
	private static function urlEncodeExt($data)
	{
		if(is_array($data)) {
			foreach($data as $key=>$value) {
				$data[strval($key)] = self::urlEncodeExt($value);
			}
		} else {
			if (is_string($data)) {
				$data = is_numeric($data) ? strval($data) : urlencode($data);
			}
		}
		return $data;
	}
	
	public static function json_decodeEx($str)
	{
		return self::urlDecodeExt(json_decode($str,true));
	}
	
	private static function urlDecodeExt($data)
	{
		if(is_array($data)) {
			foreach($data as $key=>$value) {
				$data[strval($key)] = self::urlDecodeExt($value);
			}
		} else {
			if (is_string($data)) {
				$data = is_numeric($data) ? strval($data) : urldecode($data);
			}
		}
		return $data;
	}
	
	
    /**
     * return ts level
     * 
     * excellent (0-0.02)
     * good (0.02-0.2)
     * average (0.2-0.5)
     * fair (0.5-1.0)
     * poor (1.0-infinite)
     */
    public static function getTsLevel($ts)
    {
        if($ts <= 0.02)
        {
            return "excellent"; //优秀
        }
        else if($ts <= 0.2)
        {
            return "good"; //良好
        }
        else if($ts <= 0.5)
        {
            return "average"; //中等
        }
        else if($ts <= 1.0)
        {
            return "fair"; //及格
        }
        else
        {
            return "poor"; //差
        }
	}
	
	//数组排序，支持2级字段
	public static function sort_array(&$arr,$primarySortKey,$primarySortDirection,$secondarySortKey="",$secondarySortDirection="")
	{
		self::$_PRIMARY_FIELD=$primarySortKey;
		self::$_PRIMARY_DIRECTION=$primarySortDirection;
		self::$_SECONDARY_FIELD=$secondarySortKey;
		self::$_SECONDARY_DIRECTION=$secondarySortDirection;
		if($primarySortDirection=="desc" || $primarySortDirection=="asc"){
			usort($arr, "LunaUtil::sort_".$primarySortDirection);
		}
	}
	
	private static $_PRIMARY_FIELD="";
	private static $_PRIMARY_DIRECTION="";
	private static $_SECONDARY_FIELD="";
	private static $_SECONDARY_DIRECTION="";
	
	//降序
	private static function sort_desc($a,$b)
	{
		if(isset($a[self::$_PRIMARY_FIELD]) && isset($b[self::$_PRIMARY_FIELD])){
			if($a[self::$_PRIMARY_FIELD]>$b[self::$_PRIMARY_FIELD]){
				return -1;
			} else if($a[self::$_PRIMARY_FIELD]<$b[self::$_PRIMARY_FIELD]){
				return 1;
			} else {
				if(empty(self::$_SECONDARY_FIELD)==false && isset($a[self::$_SECONDARY_FIELD]) && isset($b[self::$_SECONDARY_FIELD])){
					if($a[self::$_SECONDARY_FIELD]>$b[self::$_SECONDARY_FIELD]){
						return self::$_SECONDARY_DIRECTION=="desc"?-1:1;
					} else if($a[self::$_SECONDARY_FIELD]<$b[self::$_SECONDARY_FIELD]){
						return self::$_SECONDARY_DIRECTION=="desc"?1:-1;
					} else {
						return 0;
					}
				}
			}
		}
		return 0;
	}
	
	//升序
	private static function sort_asc($a,$b)
	{
		if(isset($a[self::$_PRIMARY_FIELD]) && isset($b[self::$_PRIMARY_FIELD])){
			if($a[self::$_PRIMARY_FIELD]>$b[self::$_PRIMARY_FIELD]){
				return 1;
			} else if($a[self::$_PRIMARY_FIELD]<$b[self::$_PRIMARY_FIELD]){
				return -1;
			} else {
				if(empty(self::$_SECONDARY_FIELD)==false && isset($a[self::$_SECONDARY_FIELD]) && isset($b[self::$_SECONDARY_FIELD])){
					if($a[self::$_SECONDARY_FIELD]>$b[self::$_SECONDARY_FIELD]){
						return self::$_SECONDARY_DIRECTION=="desc"?-1:1;
					} else if($a[self::$_SECONDARY_FIELD]<$b[self::$_SECONDARY_FIELD]){
						return self::$_SECONDARY_DIRECTION=="desc"?1:-1;
					} else {
						return 0;
					}
				}
			}
		}
		return 0;
	}	
}
