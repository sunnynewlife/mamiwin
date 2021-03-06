<?php
Class CommonHelper{
	//根据生日计算年龄
	static public function getAgeByDate($BirthDay){
		return 2 ;

	}

	static public function isdate($str,$format="Y-m-d"){  
		
		$strArr = explode("-",$str);  
		if(empty($strArr)){
			return false;
		}  
		
		foreach($strArr as $val){
			
			if(strlen($val)<2){
				$val="0".$val;}$newArr[]=$val;
			}  
		
			$str =implode("-",$newArr);   
			$unixTime=strtotime($str);   
			$checkDate= date($format,$unixTime);   
			
			if($checkDate==$str)   
				return true;   
			else   
				return false;
	}


	/**
     * [getDateDiff 计算二个时间的时间差]
     * @param  [type] $date1     [description]
     * @param  [type] $date2     [description]
     * @param  [type] $diff_type [时间间隔：y:年；m：月；d：日 ; h：小时；mi：分钟；s：秒]
     * @return [type]            [description]
     */
    public static function getDateDiff($date1,$date2,$diff_type = 'd'){
        $diff_divider = 24 * 60 * 60  ;  //时间间隔默认为天
        // if(is_int($date1) && is_int($date2)) {
        //     $d1 = $date1;
        //     $d2 = $date2;
        // }else{
        //     if(self::isdate($date1) == false || self::isdate($date2) == false){
        //         return false;
        //     }
        // }
            
        
        $d1 = strtotime($date1);
        $d2 = strtotime($date2);

        $diff = $d2 - $d1 ;
        switch ($diff_type) {
            case 'y':
                $diff_divider = 24 * 60 * 60 * 365 ; 
                break;
            case 'm':
                $diff_divider = 24 * 60 * 60 * 12 ; 
                break;
            case 'd':
                $diff_divider = 24 * 60 * 60 ; 
                break;
            case 'h':
                $diff_divider = 24 * 60  ; 
                break;
            case 'mi':
                $diff_divider = 60 ; 
                break;
            case 's':
                $diff_divider = 1 ; 
                break;            
            default:
                # code...
                break;
        }
        return ceil($diff / $diff_divider) ; 

    }


    //获取查询月份第一天的日期
    public static function getDateMonthFirstDay($query_date){
        $begin_date=date('Y-m-01', strtotime($query_date));
        return $begin_date;
    }


    //获取查询月份最后一天的日期
    public static function getDateMonthLastDay($query_date){
        $begin_date=date('Y-m-01', strtotime($query_date));
        $last_date = date('Y-m-d', strtotime("$begin_date +1 month -1 day")); 
        return $last_date;
    }

    
}