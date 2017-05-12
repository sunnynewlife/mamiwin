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
	 * 获取用户当前轮次	 * 
	 * @return [type] [description]
	 */
	public static function getUserTaskCurrentTurn($UserIDX){
		

		
	}


}