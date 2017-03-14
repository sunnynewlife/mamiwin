<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">风控批量冻结账号</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="" enctype="multipart/form-data">
				<label>选择包含需要冻结账号的文本文件</label> 
				<input type="file"  enctype="multipart/form-data" name="PhoneFile" class="input-xlarge" required="true" autofocus="true" >
				
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
<script>
<?php 
	if (isset($alert_message)) {
		echo sprintf("layer.alert('%s', 1);",$alert_message);
	}
?>
</script>