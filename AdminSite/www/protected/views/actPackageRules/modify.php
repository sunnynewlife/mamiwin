<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填活动礼包领取规则定义</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">

				<label>礼包</label> 
				<select name="pid" class="input-xlarge">
<?php
					foreach ($PACKAGE_MAP as $packInfo){
						$selectedStr= ($act_package_rule["pid"]==$packInfo["pid"]?"selected":"");
						echo sprintf("<option value='%s' %s >%s</option>",$packInfo["pid"],$selectedStr,$packInfo["name"]);
					}
?>								
				</select>
				
				<label>规则</label> 
				<select name="rid" class="input-xlarge">
<?php
					foreach ($RULE_MAP as $ruleInfo){
						$selectedStr= ($act_package_rule["rid"]==$ruleInfo["rid"]?"selected":"");
						echo sprintf("<option value='%s' %s >%s</option>",$ruleInfo["rid"],$selectedStr,$ruleInfo["name"]);
					}
?>				
				</select>
				<label>附加条件（签到类该值为连续签到次数）</label> 		
				<input type="text" name="conditionReturnValue" value="<?php echo $act_package_rule['conditionReturnValue'];?>" /> 
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