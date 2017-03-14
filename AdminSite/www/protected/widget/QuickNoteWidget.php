<?php

class QuickNoteWidget extends CWidget 
{
	public function run() 
	{
		$userId = Yii::app()->session ['uid'];
		$userInfo = UserInfo::getInstance ()->getUserInfo ( $userId );
		if (! $userInfo ['show_quicknote']) {
			return;
		}
		$note = QuickNote::getInstance ()->getQuickNoteRandom();
		$note_content = $note ['note_content'];
		echo "<div class=\"alert alert-info\">
	   		<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>$note_content</div>";
	}
}

