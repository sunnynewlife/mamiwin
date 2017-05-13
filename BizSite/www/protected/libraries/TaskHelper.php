<?php
Class TaskHelper{

	/**
	 * 根据用户经验值积分，返回用户级别
	 * i.	初级家长（0-1199）
	 * ii.	中级家长（1200-2999）
	 * iii.	高级家长（3000-6999）
	 * iv.	超级家长（7000-）
	 * @param  [type] $UserExperiencePoint [description]
	 * @return [type]                      [description]
	 */
	public static function getUserLevelByExpPoints($UserExperiencePoint){
		$User_Level = 0 ;
		if($UserExperiencePoint < 1200){
			$User_Level = 1 ;
		}else if($UserExperiencePoint >= 1200 && $UserExperiencePoint < 3000){
			$User_Level = 2 ;
		}else if($UserExperiencePoint >= 3000 && $UserExperiencePoint < 7000){
			$User_Level = 3 ;
		}else if($UserExperiencePoint >= 7000 ){
			$User_Level = 4 ;
		}
		return $User_Level;

	}
}