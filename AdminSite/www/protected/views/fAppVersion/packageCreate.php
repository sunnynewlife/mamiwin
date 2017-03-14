<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">游戏版本发布到打包平台</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">游戏名称*</td>
						<td><input type="text" maxlength="50" name="AppName" id="AppName" value="<?php echo $AppVersion["AppName"];?>" class="input-xlarge" required="true" autofocus="true" readonly /></td>
						<td style="width:100px;">游戏APPID*</td>
						<td><input type="text" maxlength="50" name="AppId" readonly id="AppId" value="<?php echo $AppVersion["AppId"];?>" class="input-xlarge" required="true" autofocus="true" /></td>
					</tr>
					<tr>
						<td>发布版本* </td>
						<td><input type="text" maxlength="50" name="VersionName" value="<?php echo $AppVersion["VersionName"]; ?>"  class="input-xlarge" autofocus="true" required="true" readonly /></td>
						<td>版本ID* </td>
						<td><input type="text" maxlength="50" name="AppVersionId" value="<?php echo $AppVersion["AppVersionId"]; ?>"  class="input-xlarge" autofocus="true" required="true" readonly /></td>
					</tr>
				</table>
				<input type="hidden" name="submit" value="1" />
				<ul class="nav nav-tabs" style="border-bottom:0px;">
					<li style="padding-top:5px;"><a href="<?php echo $back_url;?>">放弃</a></li>
					<li> 
						<div class="btn-toolbar">
							<button type="submit" class="btn btn-primary">
								<strong>游戏版本发布到打包平台</strong>
							</button>
					
						</div>
					</li>
				</ul>
			</form>
		</div>
	</div>
</div>
