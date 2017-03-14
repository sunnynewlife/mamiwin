<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填活动规则定义</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>规则名称</label> 
				<input type="text" name="name" value="" class="input-xlarge" required="true" autofocus="true">

				<label>规则类型</label> 
				<select name="type" class="input-xlarge">
					<option value="1" selected>白名单检查</option>
					<option value="2">游戏方接口检查</option>
					<option value="3">分享推荐类检查</option>
					<option value="4">签到类</option>
					<option value="5">自定义规则</option>
					<option value="6">无条件限制，点击即可领取</option>
				</select>
				
				<label>规则接口数据查询定义</label>
				<input type="text" name="rule_node_name" value="" class="input-xlarge">
				
				<label>规则数据</label>
				<input type="text" name="data" value="" class="input-xlarge">
				<label>不满足该条件,用户提示语</label>
				<input type="text" name="error_msg" value="" class="input-xlarge">
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