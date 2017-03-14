<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写游戏活动接入资料</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>活动接入码(event_code,请先申请备案)</label> 
				<input type="text" name="event_code" value="" class="input-xlarge" required="true" autofocus="true">
				
				<label>活动状态</label>
				<select name="event_status" class="input-xlarge">
					<option value="1" selected>有效</option>
					<option value="2">无效</option>
				</select>
				 
				<label>用户身份</label> 
				<select name="auth_type" class="input-xlarge">
					<option value="1">G家</option>
					<option value="2">透传</option>
					<option value="3" selected>游戏接口验证</option>
					<option value="4" selected>debug游戏接口验证</option>
				</select> 
				
				<label>身份获取配置节点名称</label>
				<input type="text" name="auth_type_cfg_name" value="" class="input-xlarge">
				
				<label>活动项列表<span class="label label-important">多个活动向ID之间使用半角逗号分隔</span></label>
				<input type="text" name="aid_list" value="" class="input-xlarge">
				
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