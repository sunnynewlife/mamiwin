<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写开服时间信息</a></li>
	</ul>
	
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="/area/modify">
				<label>活动 Code</label> 
				<input type="text" maxlength="20"  name="evn_code" value="<?php echo $area["evn_code"];?>" class="input-xlarge" required="true" autofocus="true" />
				
				<label>开服区域名</label>
				<input type="text" maxlength="20" name="area_name" value="<?php echo $area["area_name"];?>" class="input-xlarge" required="true" autofocus="true" />
				 
				<label>区域ID</label>
				<input type="text" name="area_id" maxlength="10"  value="<?php echo $area["area_id"];?>" class="input-xlarge" required="true" autofocus="true" />
				  
				 
				<label>开服时间</label> 
				<input type="text" id="start_date" name="start_time" value="<?php echo $area["start_time"];?>" readonly="readonly" />

				<label>天数</label> 
				<input type="text" maxlength="5" id="within_days" name="within_days" value="<?php echo $area["within_days"];?>" class="input-xlarge" required="true"  autofocus="true"  onkeyup="this.value=this.value.replace(/[^\d]/g,'')" />
				
				<input type="hidden" name="id" value="<?php echo $area["id"];?>" />
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
<script>

jQuery(function () {
	var myDate = new Date();
    // 时间设置
    jQuery('#start_date').datetimepicker({
        timeFormat: "HH:mm",
        dateFormat: "yy-mm-dd"
    });

});
</script>