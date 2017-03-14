<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">礼券信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="/qAppCoupon/add" onsubmit="return checkSubmit();">
				<table class="tableApp" style="width:815px;" border="0">
					<tr>
						<td style="width:150px;">礼券状态</td>
						<td style="width:80px;">
							<select name="Status" class="input-xlarge">
								<option value="2">白名单可见</option>
								<option value="1">在线</option>
								<option value="3">下线</option>
							</select>
						</td>
						<td style="width:200px;"></td>
						<td></td>
					</tr>
					<tr>
						<td>测试白名单</td>
						<td colspan=3>
							<textarea id="AclList" maxlength="50000" name="AclList" rows="4" style="width:649px;" class="input-xlarge" autofocus="true"></textarea>
						</td>
					</tr>
					<tr>
						<td>礼券类型&nbsp;<font color=red>*</font></td>
						<td>
							<select name="Type" id="Type" class="input-xlarge">
								<option value="1">通用礼券</option>
								<option value="2">专属礼券</option>
							</select>
						</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>礼券名称&nbsp;</td>
						<td><input type="text" maxlength="50" name="CouponName" id="CouponName" value="" class="input-xSmall" autofocus="true"  required="false" /></td>
						<td id="CouponName_errmsg"></td>
						<td></td>
					</tr>
					<tr id="div_appids" style="display:none">
						<td>礼券使用范围&nbsp;<font color=red>*</font></td>
						<td><input type="text" maxlength="50" name="AppName" id="AppName" value="" onclick="javascript:selectGames('AppName',true);" class="input-xlarge" autofocus="true"/></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>单笔充值金额&nbsp;<font color=red>*</font></td>
						<td><input type="text" maxlength="50" name="RecharegAmount" id="RecharegAmount" value="" class="input-xSmall" autofocus="true"  required="true" />元</td>
						<td id="RecharegAmount_errmsg">填写格式：X.00</td>
						<td></td>
					</tr>
					<tr>
						<td>礼券返现金额&nbsp;<font color=red>*</font></td>
						<td><input type="text" maxlength="50" name="ReturnAmount" id="ReturnAmount" value="" class="input-xSmall" autofocus="true"  required="true" />元</td>
						<td id="ReturnAmount_errmsg">填写格式：X.00</td>
						<td></td>
					</tr>
					<tr>
						<td>充值时间范围</td>
						<td>
							<input type='text' maxlength='20' class='dateInputBind start' name="PayStartDt" id="PayStartDt" value="" style="width:80px;" />
							&nbsp;&nbsp;~&nbsp;&nbsp;
							<input type='text' maxlength='20' class='dateInputBind end' name="PayEndDt" id="PayEndDt" value="" style="width:80px;"  />
						</td>
						<td colspan=2>不填表示所有充值有效</td>
					</tr>
					<tr>
						<td>礼券发送数据量上限</td>
						<td><input type="text" maxlength="50" name="Quantity" id="Quantity" value="" class="input-xSmall" autofocus="true"  required="true" /></td>
						<td id="Quantity_errmsg" style="display:none"><font color=red>只可填写大于0的整数</font></td>
						<td></td>
					</tr>
					<tr>
						<td>礼券有效期</td>
						<td>
							<input type='text' maxlength='20' class='dateInputBind start' name="startDt" id="startDt" value="" style="width:80px;"  />
							&nbsp;&nbsp;~&nbsp;&nbsp;
							<input type='text' maxlength='20' class='dateInputBind end' name="endDt" id="endDt" value="" style="width:80px;"  />
						</td>
						<td>不填永久有效</td>
						<td id="endDt_errmsg" style="display:none">结束时间不能早于当时时间</td>
					</tr>
					<tr>
						<td>新绑定用户自动发送</td>
						<td>
							<select name="IsNewBind" id="IsNewBind" class="input-xlarge">
								<option value="2">否</option>
								<option value="1">是</option>
							</select>
						</td>
						<td></td>
						<td></td>
					</tr>
<!-- IsNewBind = 1 则 BindStartDt 、BindEndDt 必填且，BindEndDt + 23:59:59 -->					
					<tr id="BindDt" style="display:none">
						<td>自动发送用户关注时间</td>
						<td>
							<input type='text' maxlength='20' class='dateInputBind start' name="BindStartDt" id="BindStartDt" value="" style="width:80px;"  />
							&nbsp;&nbsp;~&nbsp;&nbsp;
							<input type='text' maxlength='20' class='dateInputBind end' name="BindEndDt" id="BindEndDt" value="" style="width:80px;"  />
						</td>
						<td colspan=2>不填表示所有的用户</td>
					</tr>
					<tr>
						<td>礼券通知消息内容</td>
						<td><input type="text" maxlength="200" name="MsgFirst" id="MsgFirst" value="恭喜您收到了一张代金礼券！\n" class="input-xSmall" autofocus="true"  required="true" /></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>礼券通知消息备注</td>
						<td><input type="text" maxlength="200" name="MsgRemark" id="MsgRemark" value="\n温馨提示：一笔充值仅可兑换一张代金礼券。" class="input-xSmall" autofocus="true"  required="true" /></td>
						<td></td>
						<td></td>
					</tr>
				</table>
				<br/>
				
				<input type="hidden" name="submit" value="1" />
				<input type="hidden" name="AppId" Id="AppId" value="" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary">
						<strong>提交</strong>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php $this->widget('application.widget.GameSelector'); ?>

<script type="text/javascript">
$(document).ready(function(){
	$("#IsNewBind").change(function(){
		var selIsNewBind = $(this).children('option:selected').val();
		if(selIsNewBind == "1"){
			$("#BindDt").show();
		}else if(sel == "2"){
			$("#BindDt").hide();
		}
	});
	$("#Type").change(function(){
		var selType = $(this).children('option:selected').val();
		if(selType == "1"){
			$("#div_appids").hide();
			$("#AppName").attr("required",false);
		}else{
			$("#div_appids").show();			
			$("#AppName").attr("required",true);
		}

	});
})

function checkSubmit(){
	try{
		var selIsNewBind = $("#IsNewBind").children('option:selected').val();
		var BindStartDt = $("#BindStartDt").val();
		var BindEndDt = $("#BindEndDt").val();
		var Quantity = $("#Quantity").val();
		var Quantity_errmsg = $("#Quantity_errmsg").html();
		var RecharegAmount = $("#RecharegAmount").val();
		var RecharegAmount_errmsg = $("#RecharegAmount_errmsg").html();
		var ReturnAmount = $("#ReturnAmount").val();		
		var ReturnAmount_errmsg = $("#ReturnAmount_errmsg").html();

		if(selIsNewBind == "1"){
			
		}
		if(isNaN(RecharegAmount)){
			layer.alert(RecharegAmount_errmsg,8);
			return false;
		}
		if(isNaN(ReturnAmount)){
			layer.alert(ReturnAmount_errmsg,8);
			return false;
		}
		if(isNaN(Quantity) || Quantity < 0){
			layer.alert(Quantity_errmsg,8);
			// alert(Quantity_errmsg);
			$("#Quantity_errmsg").show();
			return false;
		}else{
			$("#Quantity_errmsg").hide();
		}

	}catch(ex){
		alert(ex);
		return false;
	}
	return true ;
}

$(".dateTimeInputBind").each(function(){
	$(this).datetimepicker({
		dateFormat: "yy-mm-dd",
		timeFormat: 'HH:mm:ss',
		stepHour: 1,        
		stepMinute: 1,        
		stepSecond: 3,
		defaultDate: "+0",
		dayNamesShort:['周日','周一', '周二', '周三', '周四', '周五', '周六'],
		dayNamesMin:['日','一', '二', '三', '四', '五', '六'],
		changeMonth: true,
		changeYear: true,
		monthNames:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
		monthNamesShort:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
		numberOfMonths: 1,
		yearRange: "-5:+5",
	});	
});		

$(".dateInputBind").each(function(){
	$(this).datepicker({
		dateFormat: "yy-mm-dd",
		defaultDate: "+0",
		dayNamesShort:['周日','周一', '周二', '周三', '周四', '周五', '周六'],
		dayNamesMin:['日','一', '二', '三', '四', '五', '六'],
		changeMonth: true,
		changeYear: true,
		monthNames:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
		monthNamesShort:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
		numberOfMonths: 1,
		yearRange: "-5:+5",
	});	
});

function selectGames(pvTxtId,CanMutilChoice)
{
	showGameSelector($("#AppId").val(),pvTxtId,$("#"+pvTxtId).val(),CanMutilChoice);
}

function selectApp()
{
	showAppSelector(saveAppCallback);	
}
function saveAppCallback(pvAppId,pvAppName,pvProrate)
{
	$("#AppId").val(pvAppId);	
	$("#AppName").val(pvAppName);
}
</script>