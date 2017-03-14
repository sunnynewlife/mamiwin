<?php
Yii::import('model.UserGroup');
class Group extends CWidget
{
	public $current_user_group = 0;
	public function run() {
		$groups = UserGroup::getInstance()->getGroups();
		if (trim(Yii::app()->request->getParam('user_group')))
		 	 $this->current_user_group = Yii::app()->request->getParam('user_group');
		 
		
		$data['groups'] = $groups;
		$data['current_user_group'] = $this->current_user_group;//isset($_SESSION['user_group']) ? $_SESSION['user_group'] : null;
       	$this->render('userGroup',$data);        
    }
}

