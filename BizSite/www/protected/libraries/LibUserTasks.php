<?php
//用户任务
class LibUserTasks{
	/**
	 * 用户测评完毕后，根据测评结果 分配任务 给用户
	 * @param  [type] $UserIDX [description]
	 * @return [type]          [description]
	 */
	public static function generateUserTaskEva($UserIDX,$Question_Set_IDX){
		// 待分配任务数量
		$All_Task_Qty	= 20 ;
		$All_Task_1_Qty	= 10;	//学习任务
		$All_Task_2_Qty	= 5;	//陪伴任务

		//查询当前轮次
		$mod_user_task = new ModUserTask();
		$Turn = $mod_user_task->getCurrentUserTaskTurn($UserIDX) ;
		// 统计数据记录 		
		$mod_user_eva_result = new ModUserEvaluationResult();
		$ret_user_eva_questions_result = $mod_user_eva_result->calUserUserEvaluationResult($UserIDX,$Question_Set_IDX,$Turn);
		
		// $mod_user_eva_result = new ModUserEvaluationResult();
		$ret_user_eva_result = $mod_user_eva_result->getUserEvaluationResultList($UserIDX,$Question_Set_IDX,$Turn);
		if(!empty($ret_user_eva_result)){

			$sum_scores = 0 ;
			foreach ($ret_user_eva_result as $key => $value) {
				$Ability_Type_IDX 	= $value['Ability_Type_IDX'];
				$Ability_Score 		= $value['Ability_Score'];
				$sum_scores += $Ability_Score;
			}

			foreach ($ret_user_eva_result as $key => $value) {
				$Ability_Type_IDX 	= $value['Ability_Type_IDX'];
				$Ability_Score 		= $value['Ability_Score'];
				$Ability_Percet 	= $Ability_Score / $sum_scores ;
				$Ability_Task_Qty	= round($All_Task_Qty * $Ability_Percet);
				$Ability_Task1_Qty	= round($All_Task_1_Qty * $Ability_Percet);
				$Ability_Task2_Qty	= round($All_Task_2_Qty * $Ability_Percet);
				$Ability_Task2_Qty = $Ability_Task_Qty - $Ability_Task1_Qty ; 
				// echo($Ability_Type_IDX.",".$Ability_Score.",".$Ability_Percet.",");
				// echo($Ability_Task_Qty.",".$Ability_Task1_Qty.",".$Ability_Task2_Qty);
				// echo("<br>");
				// 分配任务
				$ret_user_task1 = $mod_user_task->generateUserTaskForEva($UserIDX,1,$Turn+1 ,$Ability_Task1_Qty );	
				$ret_user_task2 = $mod_user_task->generateUserTaskForEva($UserIDX,2,$Turn+1 ,$Ability_Task2_Qty );	
			}
			
		}
	}

	/**
	 * 根据用户测试结果，分配不同数量的智能属性的任务给用户
	 * @param  [type] $UserIDX           [description]
	 * @param  [type] $UserAbilityResult [description]
	 * @return [type]                    [description]
	 */
	public static function calUserTaskAbilityQty($UserIDX,$UserAbilityResult){
		$mod_ability_type = new ModAbilityType();
		$ret_ability_type = $mod_ability_type->getAbilityTypeList();
		if(!empty($ret_ability_type)){
			foreach ($ret_ability_type as $key => $value) {
				
			}
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