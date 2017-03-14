<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写随机赠言信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>随机赠言</label> 
				<textarea name="random_words" rows="5" class="input-xlarge" required="true" autofocus="true"></textarea>
				
				<label>状态</label>
				<select name="is_valid" class="input-xlarge">
					<option value="1" selected>有效</option>
					<option value="0">无效</option>
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