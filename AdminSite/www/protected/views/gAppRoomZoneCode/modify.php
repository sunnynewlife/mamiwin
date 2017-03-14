<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写聊天室区号</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>聊天室</label> 
				<input name="chat_room_name" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_chat_room_and_zone_code["chat_room_name"];?>" />
				<label>区号</label>
				<input name="zone_code" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_chat_room_and_zone_code["zone_code"];?>" />		
				
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
