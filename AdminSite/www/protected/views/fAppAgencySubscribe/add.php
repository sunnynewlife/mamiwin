<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写大商户数据订阅配置</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>商户</label> 
				<select name="SubscriberId"  class="input-xlarge">
					<?php 
						foreach ($subscribers as $itemRow) {
							echo sprintf("<option value=\"%s\" %s>%s</option>",$itemRow["LoginName"],($subscribeInfo["LoginName"]==$itemRow["LoginName"]?" selected ":""),$itemRow["Name"]);
						}
					?>
				</select>
				
				<label>配置名称-系统</label> 
				<input type="text" maxlength="100" name="SystemName" value="<?php echo $subscribeInfo["System"];?>" class="input-xlarge" required="true" autofocus="true" />
				
				<label>配置名称-接口</label> 
				<input type="text" maxlength="100" name="InterfaceName" value="<?php echo $subscribeInfo["Interface"];?>" class="input-xlarge" required="true" autofocus="true" />
				
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