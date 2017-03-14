<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写应用接入定义信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>App ID</label> 
				<input type="text" maxlength="50" name="app_id" value="" class="input-xlarge" required="true" autofocus="true" />
				
				<label>首页账号类型</label>
				<select name="home_type" class="input-xlarge">
					<option value="1">手机账号</option>
					<option value="2">邮件账号</option>
					<option value="3">个性账号</option>
				</select>		
				
				<label>手机账号</label>
				<select name="account_type_phone" class="input-xlarge">
					<option value="1">有</option>
					<option value="0">无</option>
				</select>		
				
				<label>邮件账号</label>
				<select name="account_type_email" class="input-xlarge">
					<option value="1">有</option>
					<option value="0">无</option>
				</select>
				
				<label>个性账号</label>
				<select name="account_type_custom" class="input-xlarge">
					<option value="1">有</option>
					<option value="0">无</option>
				</select>

				<label>实名注册</label>
				<select name="display_real_name" class="input-xlarge">
					<option value="1">需要</option>
					<option value="0">不需要</option>
				</select>

				<label>参数可重指定实名注册</label>
				<select name="can_override_display_real_name" class="input-xlarge">
					<option value="1">可以</option>
					<option value="0">不可以</option>
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