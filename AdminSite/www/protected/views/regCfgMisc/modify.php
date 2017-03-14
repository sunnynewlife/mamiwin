<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写聊天室信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>配置项Key</label> 
				<input type="text" maxlength="50" name="cfg_key" value="<?php echo $reg_cfg_misc["cfg_key"];?>" class="input-xlarge" required="true" autofocus="true" />
				
				<label>配置项描述</label> 
				<input type="text" maxlength="100" name="cfg_desc" value="<?php echo $reg_cfg_misc["cfg_desc"];?>" class="input-xlarge" required="true" autofocus="true" />
				
				<label>配置项值</label> 
				<textarea id="cfg_value" maxlength="500" name="cfg_value" rows="8" style="width:800px;" class="input-xlarge" required="true" autofocus="true"><?php echo $reg_cfg_misc["cfg_value"];?></textarea>
			
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
