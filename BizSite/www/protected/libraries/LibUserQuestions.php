<?php
//用户评测题
class LibUserQuestions{
	/**
	 * 给用户分配评测题
	 * @return [type] [description]
	 */
	public static function distributeUserQuestsion($UserIDX,$Parent_Gender,$Parent_Marriage,$Child_Gender,$Child_Birthday){
		$mod_questions_set = ModEvaluationQuesitonsSet::getInstance();
		$Only_Children = 1 ;
		$Age = CommonHelper::getAgeByDate($Child_Birthday);
		$ret = $mod_questions_set->getEvaluationQuesitonsSetList('',$Child_Gender ,$Parent_Gender,$Parent_Marriage, $Age , $Only_Children);
		if(!empty($ret)){
			$ret = $mod_questions_set->getEvaluationQuesitonsSetList('',1,1,1, 0,1,1);
		}
		$Question_Set_IDX = $ret['IDX'];
		$mod_user_question = ModUserEvaluationQuesitons::getInstance();
		$ret_user_question = $mod_user_question->generateUserQuestion($UserIDX,$Question_Set_IDX);

		
	}


	/**
	 * 根据用户评测题结果分配任务
	 * @param  [type] $UserIDX          [description]
	 * @param  [type] $Question_Set_IDX [description]
	 * @return [type]                   [description]
	 */
	public static function distributeUserTask($UserIDX,$Question_Set_IDX){
		$mod_user_question = 
		$sql = ""

	}
}