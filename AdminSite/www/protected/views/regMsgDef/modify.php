<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写错误信息转换信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>错误码</label> 
				<input type="text" maxlength="20"  name="error_code" value="<?php echo $reg_cfg_message["error_code"];?>" class="input-xlarge" required="true" autofocus="true" />
				
				<label>错误信息</label>
				<input type="text" maxlength="200" name="error_msg" value="<?php echo $reg_cfg_message["error_msg"];?>" class="input-xlarge" required="true" autofocus="true" />
				
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
