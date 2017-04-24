<?php
//用户经验值
class LibUserExperience{

	public static function getValueByConfigKey($Config_Key){
		$mod_task_config = new ModTaskConfig();
		$ret_task_config = $mod_task_config->getConfigByKey($Config_Key);
		return $ret_task_config['Config_Value'];
	}

	//先判断是否已经有此项经验值，给用户增减经验值，同时记录日志
	public static function recordUserExperience($UserIDX,$Config_Key,$DWType=1,$Amount,$DWMemo){
		$mod_user_experience = new ModUserExpRevenue();
		if($Config_Key == 'SignIn_Exp_Point'){
			$Query_Date = date('Y-m-d');
			$ret_query = $mod_user_experience->queryUserExperience($UserIDX,$Config_Key,$Query_Date);
			if(count($ret_query)>0){
				return true;
			}
		}
		
		$ret_user_experience = $mod_user_experience->recordUserExpRevenue($UserIDX,$Config_Key,$DWType,$Amount,$DWMemo);
		return $ret_user_experience;
	}
}