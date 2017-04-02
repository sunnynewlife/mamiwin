<?php
LunaLoader::import("luna_lib.util.LunaPdo");
//评测试题集
class 	EvaluationQuestionsSetData
{
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}
	
	/**
	 * 获取所有的评测试题集
	 * @return [type] [description]
	 */
	public function getEvaluationQuestionsSetList()
	{
		$sql="select * from  Evaluation_Questions_Set ";
		$params=array();
		$EvQuestion=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		return (isset($EvQuestion) && is_array($EvQuestion) && count($EvQuestion)>0)?$EvQuestion:array();		
	}
	
}
