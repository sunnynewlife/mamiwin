<?php


class ActInfoController extends TableMagtController 
{
	public function init()
	{
		$this->_PDO_NODE_NAME="EvnPlatformCfg";
	}
	public function actionIndex()
	{
		$name 		= Yii::app()->request->getParam('name','');
		$game_code 	= Yii::app()->request->getParam('game_code','');
		$type 		= Yii::app()->request->getParam('type','');
		$status 	= Yii::app()->request->getParam('status','');
		
		$page_no  = Yii::app()->request->getParam('page_no',1);
		$search  = Yii::app()->request->getParam('search','');
		$page_size = 10;
		$page_no=$page_no<1?1:$page_no;
		$info =ActInfo::getInstance()->getActInfoCount($name,$game_code,$type,$status);
		$row_count = $info['num'];
		$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
		$total_page=$total_page<1?1:$total_page;
		$page_no=$page_no>($total_page)?($total_page):$page_no;
		$start = ($page_no - 1) * $page_size;
		
		$page_str=Util::showPager("/actInfo/index".($search==1?"?name=$name&search=1&game_code=$game_code&type=$type&status=$status":""),$page_no,$page_size,$row_count);
		$actList = ActInfo::getInstance()->getActInfo($name,$game_code,$type,$status,$page_size,$start);
		if($actList==false){
			$actList=array();
		}
		
		$this->renderData['act_info_list']		= $actList;
		$this->renderData['page']				= $page_str;
		$this->renderData['page_no'] 			= $page_no;
		$this->render('index',$this->renderData);		
	}
	
	public function actionAdd()
	{
		$columnNames=array("name","game_code","gameid","type","limit_type","limit_qty","user_limit_type","user_limit_qty","area_range",
				"period_type","period_range","status","act_desc","element_ui_desc","url","os_type","account_type");
		$this->_actionAdd("act_info", "活动信息定义", "/actInfo/index", $columnNames,"add");
	}	
	
	public function actionModify()
	{
		$columnNames=array("name","game_code","gameid","type","limit_type","limit_qty","user_limit_type","user_limit_qty","area_range",
				"period_type","period_range","status","act_desc","element_ui_desc","url","os_type","account_type");
		$this->_actionModify("act_info", "aid", "活动信息定义", "/actInfo/index", "act_info", $columnNames,"modify");
	}
	
	public function actionDel()
	{
		$this->_actionDel("act_info", "aid", "活动信息定义", "/actInfo/index");
	}
}

?>