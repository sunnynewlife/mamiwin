<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写财富消息模板信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>消息类型ID</label> 
				<input type="text" maxlength="50" name="typeId" value="" class="input-xlarge" required="true" autofocus="true" />
				
				<label>消息标题</label> 
				<input type="text" maxlength="100" name="title" value="" class="input-xlarge" required="true" autofocus="true" />
				
				<label>模板内容</label>
				<textarea id="body" maxlength="500" name="body" rows="8" style="width:800px;" class="input-xlarge" required="true" autofocus="true"></textarea>
				
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