<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写任务信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>任务类型</label> 
				<select name="Task_Type">
					<option value="1">学习任务</option>
					<option value="2">陪伴任务</option>
				</select>						
				
				<label>任务标题</label> 
				<input type="text" maxlength="50" name="Task_Title" value="" class="input-xlarge" required="true" autofocus="true" />
								
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
