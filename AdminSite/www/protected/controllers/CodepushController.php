<?php

/*

目录配置：

/codepush/sourceindex			Git 项目资源配置列表（Git发布管理 首页）
/codepush/sourceadd				Git 项目资源配置增加
/codepush/sourceupdate			Git 项目资源配置修改
/codepush/publishindex			Git 打包列表
/codepush/publishadd			Git 打包
/codepush/publishdetail			Git 打包详情


发布数据库结构SQL创建脚本

项目GIT对应关系表：myadmin_codepush_source
项目GIT发布表：myadmin_codepush_publish

CREATE TABLE `myadmin_codepush_source` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `service_name` varchar(100) NOT NULL DEFAULT '',
  `service` varchar(256) NOT NULL DEFAULT '',
  `service_repo` varchar(256) NOT NULL DEFAULT '',
  `source` varchar(256) NOT NULL DEFAULT '',
  `source_repo` varchar(256) NOT NULL DEFAULT '',
  `prefix_tag` varchar(256) NOT NULL DEFAULT '',
  `is_sdk` int(11) NOT NULL DEFAULT '0',
  `sdk` varchar(256) NOT NULL DEFAULT '',
  `sdk_repo` varchar(256) NOT NULL DEFAULT '',
  `is_valid` int(11) NOT NULL DEFAULT '0',
  `add_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `update_time_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `opt_user` varchar(256) NOT NULL DEFAULT '',
  `opt_username` varchar(256) NOT NULL DEFAULT '',
  `opt_ip` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

CREATE TABLE `myadmin_codepush_publish` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `source_id` int(10) unsigned NOT NULL DEFAULT '0',
  `service` varchar(256) NOT NULL DEFAULT '',
  `hash_main` varchar(100) NOT NULL DEFAULT '',
  `hash_sdk` varchar(100) NOT NULL DEFAULT '',
  `tag` varchar(100) NOT NULL DEFAULT '',
  `status` varchar(50) NOT NULL DEFAULT 'ready',
  `is_locked` int(11) NOT NULL DEFAULT '0',
  `is_valid` int(11) NOT NULL DEFAULT '0',
  `add_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `update_time_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `commit` longtext NOT NULL,
  `result` longtext NOT NULL,
  `git_diff` longtext NOT NULL,
  `git_result` longtext NOT NULL,
  `opt_user` varchar(256) NOT NULL DEFAULT '',
  `opt_username` varchar(256) NOT NULL DEFAULT '',
  `opt_ip` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

*/

LunaLoader::import("luna_lib.util.LunaWebUtil");

class CodepushController extends Controller 
{	
    public function actionSourceindex()
    {
    	$where = array();
    	$page_no  = Yii::app()->request->getParam('page_no',1);
    	$search  = Yii::app()->request->getParam('search','');
    	
    	$page_size = 100;
    	$page_no=$page_no<1?1:$page_no;
    	
    	$info = CodePush::getInstance()->countCodePushSource();
    	$row_count = $info['num'];
    	$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
    	$total_page=$total_page<1?1:$total_page;
    	$page_no=$page_no>($total_page)?($total_page):$page_no;
    	$start = ($page_no - 1) * $page_size;
    
    	
    	$page_str=Util::showPager("/codepush/index",$page_no,$page_size,$row_count);
    	$sources = CodePush::getInstance()->loadCodePushSource($start,$page_size);

    	$this->renderData['sources']= $sources;
    	$this->renderData['page']= $page_str;
    	$this->renderData['page_no'] = $page_no;
    	$this->renderData['current_user_id'] = $_SESSION['uid'];
    	$this->renderData['user_group'] = $_SESSION['user_group'];
    	$this->render('sourceindex',$this->renderData);
    }
    
    public function actionPublishindex()
    {
    	$where = array();
    	$page_no  = Yii::app()->request->getParam('page_no',1);
    	$search  = Yii::app()->request->getParam('search','');
    	
    	$page_size = 10;
    	$page_no=$page_no<1?1:$page_no;
    	
    	$info = CodePush::getInstance()->countCodePushPublish();
    	$row_count = $info['num'];
    	$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
    	$total_page=$total_page<1?1:$total_page;
    	$page_no=$page_no>($total_page)?($total_page):$page_no;
    	$start = ($page_no - 1) * $page_size;
    
    	
    	$page_str=Util::showPager("/codepush/index",$page_no,$page_size,$row_count);
    	$publishs = CodePush::getInstance()->loadCodePushPublish($start,$page_size);

    	$this->renderData['publishs']= $publishs;
    	$this->renderData['page']= $page_str;
    	$this->renderData['page_no'] = $page_no;
    	$this->renderData['current_user_id'] = $_SESSION['uid'];
    	$this->renderData['user_group'] = $_SESSION['user_group'];
    	$this->render('publishindex',$this->renderData);
    }
	
    public function actionPublishadd()
    {
    	$submit= Yii::app()->request->getParam('submit');
		$data["opt_user"] = $_SESSION['uid'];
		$data["opt_username"] = $_SESSION['userName'];
		$data["opt_ip"] = LunaWebUtil::getClientIp();
		$data["source_id"] = trim(Yii::app ()->request->getParam('source_id'));
		$data["hash_main"] = trim(Yii::app ()->request->getParam('hash_main'));
		$data["hash_sdk"] = trim(Yii::app ()->request->getParam('hash_sdk'));
		$data["commit"] = trim(Yii::app ()->request->getParam('commit'));
		$data["add_time"] = date('Y-m-d H:i:s');
		$data["update_time"] = date('Y-m-d H:i:s');
		$data["is_valid"] = 1; //有效
		
		//hash值只获取前7位
		$data["hash_main"] = mb_substr($data["hash_main"], 0, 7);
		$data["hash_sdk"] = mb_substr($data["hash_sdk"], 0, 7);
		
		//通过ID获取source值
		$ret_source = CodePush::getInstance()->getCodePushSource($data["source_id"]);
		if($ret_source === false) {
			$service = '项目配置已删除或没找到';
			$source = '';
		} else {
			$service = $ret_source['service'];
			$source = $ret_source['source'];
		}
		$data["service"] = $service;
		
    	if ($submit && $ret_source !== false)
    	{
			//更新数据
			if (CodePush::getInstance()->insertCodePushPublish($data))
			{
				$this->exitWithSuccess("打包成功 (".$data["service"].")", '/codepush/publishindex');
			}else{
    			$this->exitWithError("打包失败", '/codepush/publishindex');
    		}
    		return;
    	} else {
			$this->renderData['service']= htmlentities($service,ENT_COMPAT,'UTF-8');
			$this->renderData['source']= htmlentities($source,ENT_COMPAT,'UTF-8');
		}
    	$this->render('publishadd', $this->renderData);
    }
	
    public function actionSourceadd()
    {
    	$submit= Yii::app()->request->getParam('submit');
		$data["opt_user"] = $_SESSION['uid'];
		$data["opt_username"] = $_SESSION['userName'];
		$data["opt_ip"] = LunaWebUtil::getClientIp();
		$data["service_name"] = trim(Yii::app ()->request->getParam('service_name'));
		$data["service"] = trim(Yii::app ()->request->getParam('service'));
		$data["service_repo"] = trim(Yii::app ()->request->getParam('service_repo'));
		$data["source"] = trim(Yii::app ()->request->getParam('source'));
		$data["source_repo"] = trim(Yii::app ()->request->getParam('source_repo'));
		$data["sdk"] = trim(Yii::app ()->request->getParam('sdk'));
		$data["sdk_repo"] = trim(Yii::app ()->request->getParam('sdk_repo'));
		$data["add_time"] = date('Y-m-d H:i:s');
		$data["update_time"] = date('Y-m-d H:i:s');
		$data["is_valid"] = 1; //有效
		$data["is_sdk"] = 0;
		
		if($data["sdk"]) { $data["is_sdk"] = 1; }
		
    	if ($submit)
    	{
			//更新数据
			if (CodePush::getInstance()->insertCodePushSource($data))
			{
				$this->exitWithSuccess("项目配置添加 成功 (".$data["service"].")", '/codepush/sourceindex');
			}else{
    			$this->exitWithError("项目配置添加 失败", '/codepush/sourceindex');
    		}
    		return;
    	} else {

		}
    	$this->render('sourceadd', $this->renderData);
    }
	
    public function actionSourceupdate()
    {
    	$submit= Yii::app()->request->getParam('submit');
		$source_id = Yii::app()->request->getParam('source_id');
    	if(!$source_id)
    	{
    		$this->exitWithError("参数错误", "/codepush/sourceindex");
    	}
		
		$data["opt_user"] = $_SESSION['uid'];
		$data["opt_username"] = $_SESSION['userName'];
		$data["opt_ip"] = LunaWebUtil::getClientIp();
		$data["service_name"] = trim(Yii::app ()->request->getParam('service_name'));
		$data["service"] = trim(Yii::app ()->request->getParam('service'));
		$data["service_repo"] = trim(Yii::app ()->request->getParam('service_repo'));
		$data["source"] = trim(Yii::app ()->request->getParam('source'));
		$data["source_repo"] = trim(Yii::app ()->request->getParam('source_repo'));
		$data["sdk"] = trim(Yii::app ()->request->getParam('sdk'));
		$data["sdk_repo"] = trim(Yii::app ()->request->getParam('sdk_repo'));
		$data["add_time"] = date('Y-m-d H:i:s');
		$data["update_time"] = date('Y-m-d H:i:s');
		$data["is_valid"] = 1; //有效
		$data["is_sdk"] = 0;
		
		if($data["sdk"]) { $data["is_sdk"] = 1; }
		
    	if ($submit && $source_id)
    	{
			//更新数据
			if (CodePush::getInstance()->updateCodePushSource($data, $source_id))
			{
				$this->exitWithSuccess("项目配置修改 成功 (".$data["service"].")", '/codepush/sourceindex');
			}else{
    			$this->exitWithError("项目配置修改 失败", '/codepush/sourceindex');
    		}
    		return;
    	} else {
			//显示之前的数据
			$this->renderData['source']= CodePush::getInstance()->getCodePushSource($source_id);
		}
    	$this->render('sourceupdate', $this->renderData);
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
