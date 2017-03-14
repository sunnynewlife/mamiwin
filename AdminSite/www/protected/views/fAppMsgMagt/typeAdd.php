<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">消息频道信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">频道ID</td>
						<td><input type="text" maxlength="50" name="channelId"   value="<?php echo $ChannelId;?>" class="input-xlarge" required="true" autofocus="true"  readonly /></td>
					</tr>
					<tr>
						<td>类型名称*</td>
						<td><input type="text" maxlength="50" name="typeName" value=""  class="input-xlarge" autofocus="true" required="true" /></td>
					</tr>
				</table>
				
				<input type="hidden" name="submit" value="1" />
				
				<ul class="nav nav-tabs" style="border-bottom:0px;">
					<li style="padding-top:5px;"><a href="<?php echo $back_url;?>">放弃</a></li>
					<li> 
						<div class="btn-toolbar">
							<button type="submit" class="btn btn-primary">
								<strong>提交</strong>
							</button>
					
						</div>
					</li>
				</ul>
								
			</form>				
		</div>
	</div>
</div>