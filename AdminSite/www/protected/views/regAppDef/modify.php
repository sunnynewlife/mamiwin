<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写应用接入定义信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>App ID</label> 
				<input type="text" maxlength="50" name="app_id" value="<?php echo $reg_app_def["app_id"];?>" class="input-xlarge" required="true" autofocus="true" />
				
				<label>首页账号类型</label>
				<select name="home_type" class="input-xlarge">
					<option value="1" <?php echo $reg_app_def["home_type"]==1?" selected ":" " ;?>>手机账号</option>
					<option value="2" <?php echo $reg_app_def["home_type"]==2?" selected ":" " ;?>>邮件账号</option>
					<option value="3" <?php echo $reg_app_def["home_type"]==3?" selected ":" " ;?>>个性账号</option>
				</select>		
				
				<label>手机账号</label>
				<select name="account_type_phone" class="input-xlarge">
					<option value="1" <?php echo $reg_app_def["account_type_phone"]==1?" selected ":" " ;?>>有</option>
					<option value="0" <?php echo $reg_app_def["account_type_phone"]==0?" selected ":" " ;?>>无</option>
				</select>		
				
				<label>邮件账号</label>
				<select name="account_type_email" class="input-xlarge">
					<option value="1" <?php echo $reg_app_def["account_type_email"]==1?" selected ":" " ;?>>有</option>
					<option value="0" <?php echo $reg_app_def["account_type_email"]==0?" selected ":" " ;?>>无</option>
				</select>
				
				<label>个性账号</label>
				<select name="account_type_custom" class="input-xlarge">
					<option value="1" <?php echo $reg_app_def["account_type_custom"]==1?" selected ":" " ;?>>有</option>
					<option value="0" <?php echo $reg_app_def["account_type_custom"]==0?" selected ":" " ;?>>无</option>
				</select>

				<label>实名注册</label>
				<select name="display_real_name" class="input-xlarge">
					<option value="1" <?php echo $reg_app_def["display_real_name"]==1?" selected ":" " ;?>>需要</option>
					<option value="0" <?php echo $reg_app_def["display_real_name"]==0?" selected ":" " ;?>>不需要</option>
				</select>

				<label>参数可重指定实名注册</label>
				<select name="can_override_display_real_name" class="input-xlarge">
					<option value="1" <?php echo $reg_app_def["can_override_display_real_name"]==1?" selected ":" " ;?>>可以</option>
					<option value="0" <?php echo $reg_app_def["can_override_display_real_name"]==0?" selected ":" " ;?>>不可以</option>
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
