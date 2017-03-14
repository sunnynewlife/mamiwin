<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写图片信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="" enctype="multipart/form-data">
				<label>图片使用游戏AppId</label> 
				<input type="text" maxlength="50" name="Scene" value="" class="input-xlarge" required="true" autofocus="true" />
				
				<label>选择图片</label> 
				<input type="file"  enctype="multipart/form-data" name="GamePic" class="input-xlarge" required="true" autofocus="true" >
				
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