<?php

 class ThreadId {
    private static $_threadId = '';
    
    public static function getThreadId()
    {
        if(self::$_threadId=='')
        {
            $guid = self::guid();
            self::$_threadId = self::shorturlonly($guid);
        }
        return self::$_threadId;
    }
    
    
    public static function guid(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                    .substr($charid, 0, 8).$hyphen
                    .substr($charid, 8, 4).$hyphen
                    .substr($charid,12, 4).$hyphen
                    .substr($charid,16, 4).$hyphen
                    .substr($charid,20,12)
                    .chr(125);// "}"
            return $uuid;
        }
    }
   
    public static function shorturlonly($input) {
        $urls = self::shorturl($input);
        return $urls[0];
    }
   
    public static function shorturl($input) {
		$base32 = array (
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
            'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
            'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
            'y', 'z', '0', '1', '2', '3', '4', '5',
            '6', '7', '8', '9', 'A', 'B', 'C', 'D',
            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
            'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Y', 'Z'
        );
        $hex = md5($input); 
        $hexLen = strlen($hex);
        $subHexLen = $hexLen / 8;
        
        $output = array();
        for ($i = 0; $i < $subHexLen; $i++) {
            $subHex = substr ($hex, $i * 8, 8);
            $int = 0x3FFFFFFF & hexdec($subHex);
            $out = '';
            for ($j = 0; $j < 6; $j++) {
            	$val = 0x0000003D & $int;
                $out .= $base32[$val]; 
                $int = $int >> 5; 
            }
            $output[] = $out;
        }
        return $output;
    }
 }