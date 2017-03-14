<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写游戏配置信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<h2>基本信息</h2>
				<label>产品ID</label> 
				<input  name="ProductId" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $gapp_log_key["ProductId"];?>"/>
				<br/>
				<label>分配key</label> 
				<input  name="des_key" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $gapp_log_key["des_key"];?>"/>
				<br/>
				<label>游戏介绍</label> 
				<textarea name="Product_intr" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $gapp_log_key["Product_intr"];?>"><?php echo $gapp_log_key["Product_intr"];?></textarea>
				<br/>
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary" onclick="doConvert();">
						<strong>提交</strong>
					</button>
				</div>
			
			</form>
		</div>
	</div>
</div>
