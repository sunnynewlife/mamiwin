<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">计算月度返税</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active in">
			<form id="tab" method="post" action="">
				<label>记税周期*</label>
				<input type="text" maxlength="50" name="Period" value="" class="input-xlarge" required="true" autofocus="true" placeholder="格式:年-月" />

				<label>推广员账号</label>
				<input type="text" maxlength="50" name="PhoneNo" value="" class="input-xlarge"  autofocus="true" />
				
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