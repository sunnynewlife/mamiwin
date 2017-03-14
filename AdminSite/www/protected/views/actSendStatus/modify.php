<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填活动领取量定义</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
			
				<label>活动ID</label> 
				<input type="text" name="aid" value="<?php echo $act_send_status["aid"];?>" class="input-xlarge" required="true" autofocus="true">
			
				<label>游戏区ID</label> 
				<input type="text" name="areaid" value="<?php echo $act_send_status["areaid"];?>" class="input-xlarge"  autofocus="true">
				
				<label>活动领取日期</label> 
				<input type="text" name="date" value="<?php echo $act_send_status["date"];?>" class="input-xlarge" required="true" autofocus="true">

				<label>所有礼包已领取数量</label> 
				<input type="text" name="sendNum" value="<?php echo $act_send_status["sendNum"];?>" class="input-xlarge" required="true" autofocus="true" placeholder="请输入数值类型">
				
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