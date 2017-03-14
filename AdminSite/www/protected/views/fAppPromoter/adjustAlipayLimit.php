<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写提现额度</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>推广员</label> 
				<input type="text" maxlength="50" name="PhoneNo" value="<?php echo $Promoter["PhoneNo"];?>" class="input-xlarge" required="true" autofocus="true" readonly />
				
				<label>提现额度</label> 
				<input type="text" maxlength="100" name="MaxAliPayAmount" value="<?php echo $Promoter["MaxAliPayAmount"];?>" class="input-xlarge" required="true" autofocus="true" />
				
				<label>提现周期</label> 
				<input type="text" maxlength="100" name="MinPayReturn" value="<?php echo $Promoter["MinPayReturn"];?>" class="input-xlarge" required="true" autofocus="true" />
				

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
