<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">游戏注册到打包平台</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">游戏名称*</td>
						<td><input type="text" maxlength="50" name="AppName" id="AppName" value="<?php echo $App["AppName"];?>" class="input-xlarge" required="true" autofocus="true" readonly /></td>
						<td style="width:100px;">游戏APPID*</td>
						<td><input type="text" maxlength="50" name="AppId" readonly id="AppId" value="<?php echo $App["AppId"];?>" class="input-xlarge" required="true" autofocus="true" /></td>
					</tr>
					<tr>
						<td>打包文件名前缀*</td>
						<td><input type="text" maxlength="50" name="PackagePrefixName" value="<?php echo $App["PackagePrefixName"]; ?>"  class="input-xlarge" autofocus="true" required="true" readonly /></td>
						<td></td>
						<td></td>
					</tr>
				</table>
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary">
						<strong>注册游戏</strong>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
