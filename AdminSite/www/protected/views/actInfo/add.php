<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填活动信息定义</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>活动名</label> 
				<input type="text" name="name" value="" class="input-xlarge" required="true" autofocus="true">
				
				<label>游戏代码</label>
				<input type="text" name="game_code" value="" class="input-xlarge">
				<label>游戏ID</label>
				<input type="text" name="gameid" value="" class="input-xlarge">
				<label>活动类型</label> 
				<select name="type" class="input-xlarge">
					<option value="1" selected>领取类</option>
					<option value="2">抽奖类</option>
					<option value="3">签到类</option>
				</select>
				<label>账号类型</label> 
				<select name="account_type" class="input-xlarge">
					<option value="0" >不需要</option>
					<option value="1">盛大通行证</option>
					<option value="2">手机账号</option>
				</select>
				<label>平台类型</label> 
				<select name="os_type" class="input-xlarge">
					<option value="1" selected>Android端</option>
					<option value="2">Ios端</option>
				</select>
				<label>活动总量限制类型</label>
				<select name="limit_type" class="input-xlarge">
					<option value="1" selected>无限制</option>
					<option value="2">有限制</option>
				</select> 
				
				<label>活动总量限制数量</label>
				<input type="text" name="limit_qty" value="" class="input-xlarge" required="true" placeholder="请填入活动总量限制数量,无限制请填写0">

				<label>用户领取限量限制类型</label>
				<select name="user_limit_type" class="input-xlarge">
					<option value="1" selected>无限制</option>
					<option value="2">有限制</option>
				</select> 
				
				<label>用户领取限量限制数量</label>
				<input type="text" name="user_limit_qty" value="" class="input-xlarge" required="true" placeholder="请用户领取限量限制数量,无限制请填写0">

				<label>限制在游戏区</label>
				<select name="area_range" class="input-xlarge">
					<option value="1" selected>无</option>
					<option value="2">有</option>
				</select> 
				
				<label>活动时间类型</label>
				<select name="period_type" class="input-xlarge">
					<option value="1" selected>一次性</option>
					<option value="2">周期性-每小时</option>
					<option value="3">周期性-每天</option>
					<option value="4">时间段</option>
				</select> 
				
				<label>活动时间定义<span class="label label-important">多对值使用符号 | 分割， 每对值使用半角逗号分割</span></label>
				<input type="text" name="period_range" value="" class="input-xlarge" required="true">

				<label>活动状态</label>
				<select name="status" class="input-xlarge">
					<option value="1" selected>有效</option>
					<option value="2">无效</option>
				</select> 
				
				<label>活动简介信息</label>
				<table class="table table-striped" style="width:815px;">
					<tr>
						<td style="width:80px;">配置内容项</td>
						<td style="width:60px;">格式:</td>
						<td style="width:15px;padding-right:0px;">
							<input type="checkbox" value="1" name="jsonFormater" id="jsonFormater" onchange="javascript:jsonFormatChange();">
						</td>
						<td style="padding-left:4px;width:120px;">
							<label for="jsonFormater">json格式</label>
						</td>
						<td>
							<a href="javascript:doJsonEdit();" id="btnJsonEdit" style="display:none;">编辑Json格式配置内容 </a>
						</td>
					</tr>
					
				</table>
				<textarea id="config_value" name="act_desc" rows="8" style="width:800px;" class="input-xlarge" required="true" autofocus="true"></textarea>
				<label>活动UI元素文字定义</label>
				<textarea name="element_ui_desc" rows="3" class="input-xlarge"></textarea>
				<label>活动链接地址</label> 
				<input type="text" name="url" value="" class="input-xlarge" required="true" autofocus="true">
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
<?php $this->widget('application.widget.JsonEditor'); ?>
<script type="text/javascript">
var package_templet='{"usage_method": ["1、进入游戏，点击“设置”按钮","2、进入设置面板后，点击“礼包码”按钮，输入礼包码即可"]}';
$("#config_value").val(package_templet);
function jsonFormatChange()
{
	if($("#jsonFormater").attr("checked")=="checked"){
		$("#btnJsonEdit").show();
	}else{
		$("#mask").hide();
		$("#btnJsonEdit").hide();
	}
}

function doJsonEdit()
{
	showJsonEditor($("#config_value").val());   
}
</script>