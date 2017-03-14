<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写图片信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>图片使用游戏AppId</label> 
				<input type="text" maxlength="50" name="Scene" value="<?php echo $File["Scene"]; ?>" class="input-xlarge" required="true" autofocus="true" />
							
				<label>图片文件名</label> 
				<input type="text" maxlength="50" name="FileUrl" value="<?php echo $File["FileUrl"]; ?>" class="input-xlarge" required="true" autofocus="true" />
				
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
