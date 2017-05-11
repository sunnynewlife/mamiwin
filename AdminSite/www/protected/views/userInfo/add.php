<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写用户信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<table>
					<tr>						
						<td >用户手机</td>
						<td colspan=6>
							<input type="text" maxlength="50" name="LoginName" value="" class="input-xlarge" required="true" autofocus="true" style="width:660px;" />
						</td>
						<td style="width:100px;"></td>
						<td>													
						</td>											
					</tr>
					<tr>						
						<td >密码</td>
						<td colspan=2>
							<input type="text" maxlength="50" name="LoginPwd" value="" class="input-xlarge" required="true" autofocus="true" style="width:450px;" />
						</td>
																
					</tr>
								
				</table>

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
