<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填活动规则定义</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>规则名称</label> 
				<input type="text" name="name" value="<?php echo $act_rule["name"];?>" class="input-xlarge" required="true" autofocus="true">

				<label>规则类型</label> 
				<select name="type" class="input-xlarge">
					<option value="1" <?php echo $act_rule["type"]==1?"selected":"";?>>白名单检查</option>
					<option value="2" <?php echo $act_rule["type"]==2?"selected":"";?>>游戏方接口检查</option>
					<option value="3" <?php echo $act_rule["type"]==3?"selected":"";?>>分享推荐类检查</option>
					<option value="4" <?php echo $act_rule["type"]==4?"selected":"";?>>签到类</option>
					<option value="5" <?php echo $act_rule["type"]==5?"selected":"";?>>自定义规则</option>
					<option value="6" <?php echo $act_rule["type"]==6?"selected":"";?>>无条件限制，点击即可领取</option>
				</select>
				
				<label>规则接口数据查询定义</label>
				<input type="text" name="rule_node_name" value="<?php echo $act_rule["rule_node_name"];?>" class="">
				
				<label>规则数据</label>
				<input type="text" name="data"  class="input-xlarge" value='<?php echo $act_rule["data"];?>' >
				
				<label>不满足该条件,用户提示语</label>
				<input type="text" name="error_msg" value=<?php echo $act_rule["error_msg"];?> class="input-xlarge">
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