<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">大商户密码修改</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active in">
			<form id="tab" method="post" action="" onsubmit="return checkData();">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">商户名称*</td>
						<td><input  name="Name" class="input-xlarge" required="true" autofocus="true" value="<?php echo $Agency["Name"];?>" readonly /></td>
						<td style="width:120px;">商户编号</td>
						<td><input  name="Code" class="input-xlarge"  autofocus="true" value="<?php echo $Agency["Code"];?>" readonly /></td>
					</tr>
					<tr><td colspan=4>&nbsp;</td></tr>
					<tr>
						<td>登录账号名*</td>
						<td><input  name="LoginName" class="input-xlarge" required="true" autofocus="true" value="<?php echo $Agency["LoginName"];?>" readonly  /></td>
						<td>联系电话</td>
						<td><input  name="Telphone" class="input-xlarge"  autofocus="true" value="<?php echo $Agency["Telphone"];?>" readonly  /></td>
					</tr>
					<tr><td colspan=4>&nbsp;</td></tr>
					<tr>
						<td>新密码*</td>
						<td><input  name="LoginPwd" class="input-xlarge" required="true" autofocus="true" value="" /></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan=4 style="text-align:center;">
							<input type="hidden" name="submit" value="1" />
							<div class="btn-toolbar">
								<button type="submit" class="btn btn-primary">
									<strong>更新密码</strong>
								</button>
							</div>
						</td>
					</tr>
				</table>					
			</form>
		</div>
	</div>
</div>