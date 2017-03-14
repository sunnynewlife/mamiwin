<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写走马灯信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>跳转链接</label> 
				<input type="text" maxlength="500" name="url" class="input-xlarge" autofocus="true" />
				
				<label>图片</label> 
				<input type="text" maxlength="50" name="pic" id="pic" onclick="javascript:selectPics('pic');"  class="input-xlarge" required="true" autofocus="true" />
				
				<label>白名单</label> 
				<input type="text" maxlength="5000" name="acl" class="input-xlarge"  autofocus="true" />
				
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