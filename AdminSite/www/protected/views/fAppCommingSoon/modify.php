<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写即将上线游戏信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>游戏名称</label> 
				<input type="text" maxlength="50" name="Name" value="<?php echo $AppComingSoon["Name"];?>" class="input-xlarge" required="true" autofocus="true" />
				
				<label>排序位置</label> 
				<input type="text" maxlength="50" name="SortIndex" value="<?php echo $AppComingSoon["SortIndex"];?>" class="input-xlarge" required="true" autofocus="true" />

				<label>LOGO图片</label>
				<input type="text" maxlength="50" name="LogoPicId" value="<?php echo $AppComingSoon["LogoPicId"];?>"  id="LogoPicId" onclick="javascript:selectPics('LogoPicId',false);" class="input-xlarge" autofocus="true" required="true" />
				
				<label>游戏概述</label> 
				<textarea id="Introduction" maxlength="300" name="Introduction" rows="4" style="width:600px;" class="input-xlarge" required="true" autofocus="true"><?php echo $AppComingSoon["Introduction"];?></textarea>
				
				<input type="hidden" name="submit" value="1" />
				<input type="hidden" name="AppId" id="AppId" value="" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary">
						<strong>提交</strong>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php $this->widget('application.widget.PicSelector'); ?>
<script type="text/javascript">
function selectPics(pvTxtId,CanMutilChoice)
{
	showPicSelector($("#AppId").val(),pvTxtId,$("#"+pvTxtId).val(),CanMutilChoice);
}
</script>