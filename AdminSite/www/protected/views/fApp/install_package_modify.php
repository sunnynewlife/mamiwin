<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写游戏安装包名信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>游戏</label> 
				<td><input type="text" maxlength="50" name="AppName" id="AppName" value="<?php echo $AppName;?>" onclick="javascript:selectApp();" class="input-xlarge" autofocus="true"  required="true" /></td>
				
				<label>安装包名</label> 
				<input type="text" maxlength="200" name="InstallPackageName" id="InstallPackageName" value="<?php echo $InstallPackageName;?>"  class="input-xlarge"  style="width:500px;" required="true" autofocus="true" />
				

				<input type="hidden" name="NewAppId" id="NewAppId" value="<?php echo $AppId;?>" />
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
<?php $this->widget('application.widget.AppSelector'); ?>

<script type="text/javascript">
function selectApp()
{
	showAppSelector(saveAppCallback);	
}
function saveAppCallback(pvAppId,pvAppName,pvProrate)
{
	$("#NewAppId").val(pvAppId);	
	$("#AppName").val(pvAppName);
}
</script>