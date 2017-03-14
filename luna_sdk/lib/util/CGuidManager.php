<?php
class CGuidManager
{
    public static function GetGuid()
    {
        list($usec, $sec) = explode(" ", microtime());
        $guid = date("mdHis", $sec) . self::GetStrUsec($usec) . self::GetRandomString(4);
        return $guid;
    }
    
    public static function GetFullGuid()
    {
    	list($usec, $sec) = explode(" ", microtime());
    	$guid = date("YmdHis", $sec) . self::GetStrUsec($usec) . self::GetRandomString(10);
    	return $guid;    	 
    }

    public static function GetStrUsec($usec)
    {
        $strTemp = (string)((float)$usec*100000000);
        while(strlen($strTemp)<8)
        {
            $strTemp = '0'.$strTemp;
        }
        return $strTemp;
    }

    public static function GetRandomString($len=10)
    {

        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9",
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);

        $output = "";
        for ($i=0; $i<$len; $i++)
        {
            $output .= $chars[mt_rand(0, $charsLen)];
        }

        return $output;
    }
}