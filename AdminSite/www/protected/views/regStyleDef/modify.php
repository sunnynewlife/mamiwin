<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写样式定义信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>样式ID</label> 
				<input type="text" maxlength="20" name="css_id" value="<?php echo $reg_style_def["css_id"];?>" class="input-xlarge" required="true" autofocus="true" />
				
				<label>样式描述</label> 
				<input type="text" maxlength="50" name="name" value="<?php echo $reg_style_def["name"];?>" class="input-xlarge" required="true" autofocus="true" />
				
				<label>启用状态</label>
				<select name="status" class="input-xlarge">
					<option value="1" <?php echo ($reg_style_def["status"]==1?"selected":"");?> >有效</option>
					<option value="0" <?php echo ($reg_style_def["status"]==0?"selected":"");?> >无效</option>
				</select>		
				
				<label>样式内容</label> 
				<textarea id="content" maxlength="20000" name="content" rows="8" style="width:800px;" class="input-xlarge" required="true" autofocus="true"><?php echo $reg_style_def["content"];?></textarea>
			
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
