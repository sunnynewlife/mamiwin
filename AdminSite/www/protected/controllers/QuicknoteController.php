<?php

class QuicknoteController extends Controller 
{	
    public function actionIndex()
    {
    	$where = array();
    	$page_no  = Yii::app()->request->getParam('page_no',1);
    	$search  = Yii::app()->request->getParam('search','');
    	
    	$page_size = 10;
    	$page_no=$page_no<1?1:$page_no;
    	
    	$info = QuickNote::getInstance()->getQuickNoteCount();
    	$row_count = $info['num'];
    	$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
    	$total_page=$total_page<1?1:$total_page;
    	$page_no=$page_no>($total_page)?($total_page):$page_no;
    	$start = ($page_no - 1) * $page_size;
    
    	
    	$page_str=Util::showPager("/quicknote/index",$page_no,$page_size,$row_count);
    	$notes = QuickNote::getInstance()->getQuickNotes($start,$page_size);

    	$this->renderData['notes']= $notes;
    	$this->renderData['page']= $page_str;
    	$this->renderData['page_no'] = $page_no;
    	$this->renderData['current_user_id'] = $_SESSION['uid'];
    	$this->renderData['user_group'] = $_SESSION['user_group'];
    	$this->render('index',$this->renderData);
    }
    
    
    public function actionAdd()
    {
    	$submit= Yii::app()->request->getParam('submit');
    	if ($submit)
    	{
			$data["note_content"] = trim(Yii::app ()->request->getParam ( 'note_content' ));
			$data["note_content"] = strip_tags($data["note_content"]);
			$data["owner_id"] = $_SESSION['uid'];
			if (QuickNote::getInstance()->insertQuickNote($data))
			{
				$this->exitWithSuccess("添加便签成功", '/quicknote/index');
			}else{
    			$this->exitWithError("添加便签失败", '/quicknote/index');
    		}
    		return;
    	}
    	$this->render('add');
    }
    
    /**
     * 修改
     */
    public function actionModify()
    {
    	$submit= Yii::app()->request->getParam('submit');
    	$note_id = Yii::app()->request->getParam('note_id');
    	if(!$note_id)
    	{
    		$this->exitWithError("参数错误","/quicknote/index");
    	}
    	if ($submit && $note_id)
    	{
    		$data["note_content"] = trim(Yii::app ()->request->getParam ( 'note_content' ));
    		$data["note_content"] = strip_tags($data["note_content"]);
    		if (QuickNote::getInstance()->updateQuickNote($data,$note_id))
    		{    			
    			$this->exitWithSuccess("修改便签成功", '/quicknote/modify?note_id='.$note_id);
    		}else{
    			$this->alert('error',"修改便签失败");
    		}
    	}

    	$this->renderData['note'] = QuickNote::getInstance()->getQuickNote($note_id);
    	$this->renderData['note_id'] = $note_id;
    	$this->render('modify',$this->renderData);
    }
    
    /**
     * 删除用户
     */
    public function actionDel()
    {
    	$note_id = Yii::app()->request->getParam('note_id');
    	$ret = QuickNote::getInstance()->delQuickNote($note_id);
    	if ($ret)
    	{
    		$this->exitWithSuccess("删除便签成功", '/quicknote/index');
    	}else {
    		$this->exitWithError("删除便签失败", '/quicknote/index');
    	}
    }
}
?>