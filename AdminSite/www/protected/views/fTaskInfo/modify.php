<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写游戏配置信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>活动ID</label> 
				<input  name="event_id" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_task_info['event_id'];?>"/>
				<br/>
				<label>任务名称</label> 
				<input  name="title" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_task_info['title'];?>"/>
				<br/>
				<label>任务介绍</label> 
				<input  name="info" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_task_info['info'];?>"/>
				<br/>
				<label>设置任务条件，json格式，具体格式参见文档</label> 
				<textarea name="task_condition" rows="5" class="input-xlarge" required="true" autofocus="true"><?php echo $tbl_task_info['task_condition'];?></textarea>
				<label>任务奖励ID</label> 
				<input  name="item_id" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_task_info['item_id'];?>"/>
				<label>奖励数量</label> 
				<input  name="item_number" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_task_info['item_number'];?>"/>
				<label>任务完成时间要求，默认无时间要求</label> 
				<input  name="end_time" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_task_info['end_time'];?>"/>
				<label>奖励倍数，默认1倍</label> 
				<input  name="multiple" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_task_info['multiple'];?>"/>
				<label>完成时间，默认0，如果有时间，则在此时间前完成则有倍数奖励</label> 
				<input  name="finish_time" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_task_info['finish_time'];?>"/>
				<label>开启多倍奖励时间点</label> 
				<input  name="min_time" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_task_info['min_time'];?>"/>
				<label>关闭多倍奖励时间点</label> 
				<input  name="max_time" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_task_info['max_time'];?>"/>
				<label>前置任务ID，默认为0</label> 
				<input  name="pre_task_id" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_task_info['pre_task_id'];?>"/>
				<label>后置任务ID，默认为0</label> 
				<input  name="ext_task_id" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_task_info['ext_task_id'];?>"/>
				<label>是否开启多倍奖励</label> 
				<select name="open_multiple">
					<option value="N" <?php echo $tbl_task_info["open_multiple"]=='N'?"selected":"";?> >不开启</option>
					<option value="Y" <?php echo $tbl_task_info["open_multiple"]=='Y'?"selected":"";?>>开启</option>
				</select>
				<label>任务状态</label> 
				<select name="status">
					<option value="0" <?php echo $tbl_task_info["status"]==0?"selected":"";?> >正常</option>
					<option value="1" <?php echo $tbl_task_info["status"]==1?"selected":"";?>>删除</option>
					<option value="2" <?php echo $tbl_task_info["status"]==2?"selected":"";?>>暂停</option>
				</select>
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary">
						<strong>提交</strong>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php $this->widget('application.widget.JsonEditor'); ?>
<script type="text/javascript">

var os_type="<?php echo Yii::app()->request->getParam('os_type',1);?>";
var url_type="<?php echo $t_activity_banner["url_type"];?>";
if(os_type==1&&url_type==2){
	jQuery("#act_android_ext").show();
}else if(os_type==2&&url_type==2){
	jQuery("#act_ios_ext").show();
}
jQuery("#url_type").bind("change",function(){
	
	var type=jQuery("#url_type").val();
	if(type==2){
		switch(os_type){
			case '1':
				jQuery("#act_android_ext").show();
				jQuery("#act_ios_ext").hide();
				
				break;
			case '2':
				jQuery("#act_ios_ext").show();
				jQuery("#act_android_ext").hide();
				break;
			default:
				jQuery("#act_ios_ext").hide();
				jQuery("#act_android_ext").hide();
				break;
		}
	}
});

</script>
