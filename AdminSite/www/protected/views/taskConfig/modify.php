<?php if (isset($alert_message)) echo $alert_message;?>

<link href="/static/summernote/bootstrap.css" rel="stylesheet">
<script src="/static/summernote/jquery.js"></script> 
<script src="/static/summernote/bootstrap.js"></script> 


<div class="well">

	<div id="myTabContent">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="" enctype="multipart/form-data">
				<br/>
				<label>配置项Key</label> 
				<input type="text" maxlength="50" name="Confg_Key" value="<?php echo $TaskConfig["Confg_Key"];?>" class="input-xlarge" required="true" readonly="true" />
				<br/>
				<label>配置项Value</label> 
				<input type="text" maxlength="50" name="Config_Value" value="<?php echo $TaskConfig["Config_Value"];?>" class="input-xlarge" required="true" autofocus="true" />
				<br/>
				<label>配置项说明</label> 
				<input type="text" maxlength="200" name="Config_Remark" value="<?php echo $TaskConfig["Config_Remark"];?>" class="input-xlarge" required="true" autofocus="true" />
				<br/>
								
				<input type="hidden" name="submit" value="1" />
				
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary" onclick="return checkUserInput();">
						<strong>提交</strong>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
