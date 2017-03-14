<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写游戏启动宣传图信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>帧数</label> 
				<input type="text" maxlength="50" name="frameId" value="<?php echo $frameId;?>" class="input-xlarge" required="true" autofocus="true" readonly />
							
				<label>图片</label> 
				<input type="text" maxlength="50" name="pic" id="pic" value="<?php echo $AdItem["pic"];?>" onclick="javascript:selectPics('pic');"  class="input-xlarge" required="true" autofocus="true" />

				<input type="hidden" name="AppId" value="" />
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
<?php $this->widget('application.widget.PicSelector'); ?>
<script type="text/javascript">
function selectPics(pvTxtId)
{
	showPicSelector($("#AppId").val(),pvTxtId,$("#"+pvTxtId).val(),false);
}
</script>