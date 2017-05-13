<?php
//用户任务
class LibUserTasks{
	/**
	 * 用户测评完毕后，根据测评结果 分配任务 给用户
	 * @param  [type] $UserIDX [description]
	 * @return [type]          [description]
	 */
	public static function generateUserTaskEva($UserIDX,$Question_Set_IDX){
		$mod_user_eva_questions = new ModUserEvaluationQuesitons();
		$ret_user_eva_questions_result = $mod_user_eva_questions->calUserUserEvaluationResult($UserIDX,$Question_Set_IDX);
		
		$mod_user_eva_result = new getUserEvaluationResultList();
		$ret_user_eva_result = $mod_user_eva_result->getUserEvaluationResultList($UserIDX,$Question_Set_IDX);
		if(!empty($ret_user_eva_result)){
			
		}
	}

	/**
	 * 根据任务类型 随机分配任务 
	 * 如果任务类型为空，学习任务 、陪伴任务 ，都要分配
	 * @param  [type] $UserIDX   [description]
	 * @param  [type] $Task_Type [description]
	 * @param  [type] $Next_Turn [description]
	 * @return [type]            [description]
	 */
	public static function generateUserTaskRandom($UserIDX,$Task_Type,$Turn){
		// 
		
		$mod_task_config = new ModTaskConfig();
		$Task_1_Qty = $mod_task_config->getConfigByKey('BasicInfo_Task_1_Qty')['Config_Value'];
		$Task_2_Qty = $mod_task_config->getConfigByKey('BasicInfo_Task_2_Qty')['Config_Value'];
		$Task_1_Qty = empty($Task_1_Qty) ? 3 : $Task_1_Qty; 
		$Task_2_Qty = empty($Task_2_Qty) ? 3 : $Task_2_Qty; 
		$mod_user_task = new ModUserTask();
		if($Task_Type == 1 ){
			$ret_user_task = $mod_user_task->generateUserTaskRandom($UserIDX,$Task_Type,$Turn ,$Task_1_Qty );	
		}else if($Task_Type == 2 ){
			$ret_user_task = $mod_user_task->generateUserTaskRandom($UserIDX,$Task_Type,$Turn ,$Task_2_Qty );	
		}else{
			$ret_user_task = $mod_user_task->generateUserTaskRandom($UserIDX,1,$Turn ,$Task_1_Qty );
			$ret_user_task = $mod_user_task->generateUserTaskRandom($UserIDX,2,$Turn, $Task_2_Qty );
		}
		return $ret_user_task;
		
	}

	/**
	 * 获取用户当前轮次	 * 
	 * @return [type] [description]
	 */
	public static function getUserTaskCurrentTurn($UserIDX){
		

		
	}


}