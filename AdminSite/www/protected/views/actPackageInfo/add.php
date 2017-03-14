<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填活动礼包信息定义</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>礼包名称</label> 
				<input type="text" name="name" value="" class="input-xlarge" required="true" autofocus="true">

				<label>礼包类型</label> 
				<select name="type" class="input-xlarge">
					<option value="1" selected>游戏道具---统一道具网关</option>
					<option value="2">游戏道具---游戏接口</option>
					<option value="3">激活码----手机仓库</option>
					<option value="4">激活码----直接显示</option>
					<option value="7">游戏码----发送APPpush消息</option>
					<option value="5">抽奖类</option>
					<option value="6">无需礼包发放</option>
				</select>
				
				<label>礼包数据</label> 
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
				<textarea id="config_value" name="package_data" rows="8" style="width:800px;" class="input-xlarge" required="true" autofocus="true"></textarea>
				<label>APP消息title</label>
				<input type="text" name="push_title" value="" class="input-xlarge"  autofocus="true">
				<label>APP消息简要</label>
				<input type="text" name="msg_abstract" value="" class="input-xlarge"  autofocus="true">
				<label>APP消息html</label>
				<textarea type="text" name="push_msg" value="" class="input-xlarge"  autofocus="true" style="width:815px;"></textarea>
				<label>礼包显示图片</label> 
				<input type="text" name="icon" value="" class="input-xlarge" required="true" autofocus="true">

				<label>所属游戏代码</label> 
				<input type="text" name="game_code" value="" class="input-xlarge"  autofocus="true">
				
				<label>所属活动</label> 
				<input type="text" name="aid" value="" class="input-xlarge" required="true" autofocus="true" placeholder="请填入活动ID">
				
<!-- 				<label>礼包总量(运营配置项，仅页面显示用)</label>
				<input type="text" name="total_package_count" value="" class="input-xlarge" required="true" placeholder="请填入数值类型">
				 -->
				<label>礼包总量限制类型</label>
				<select name="limit_type" class="input-xlarge">
					<option value="1" selected>无限制</option>
					<option value="2">有限制</option>
				</select> 
				
				<label>礼包总量限制数量</label>
				<input type="text" name="limit_qty" value="" class="input-xlarge" required="true" placeholder="请填入数值类型">

				<label>用户领取限量限制类型</label>
				<select name="user_limit_type" class="input-xlarge">
					<option value="1" selected>无限制</option>
					<option value="2">有限制</option>
				</select> 
				
				<label>用户领取限量限制数量</label>
				<input type="text" name="user_limit_qty" value="" class="input-xlarge" required="true" placeholder="请填入数值类型">

				<label>限制在游戏区</label>
				<select name="area_range" class="input-xlarge">
					<option value="1" selected>无</option>
					<option value="2">有</option>
				</select> 
				
				<label>礼包时间类型</label>
				<select name="period_type" class="input-xlarge">
					<option value="1" selected>一次性</option>
					<option value="2">周期性-每小时</option>
					<option value="3">周期性-每天</option>
				</select> 
				
				<label>礼包时间定义<span class="label label-important">多对值使用符号 | 分割， 每对值使用半角逗号分割</span></label>
				<input type="text" name="period_range" value="" class="input-xlarge" required="true">

				<label>礼包状态</label>
				<select name="status" class="input-xlarge">
					<option value="1" selected>有效</option>
					<option value="2">无效</option>
				</select> 
				
				<label>礼包显示顺序</label>
				<input type="text" name="order_id" value="" class="input-xlarge" required="true" placeholder="请填入数值类型">
				
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
<?php 
	$pack_data=array(
			'礼包详情'=>array('宝石*10','神仙水*20'),
			'使用方法'=>array('1、进入游戏，点击“设置”按钮','2、进入设置面板后，点击“礼包码”按钮，输入礼包码即可'),
	);
?>
<script type="text/javascript">
var package_templet='{"package_details": ["宝石*10","神仙水*20"],"usage_method": ["1、进入游戏，点击“设置”按钮","2、进入设置面板后，点击“礼包码”按钮，输入礼包码即可"]}';
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