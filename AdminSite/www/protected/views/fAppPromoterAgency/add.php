<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写大商户信息</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active in">
			<form id="tab" method="post" action="" onsubmit="return checkData();">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">商户名称*</td>
						<td><input  name="Name" class="input-xlarge" required="true" autofocus="true" value=""/></td>
						<td style="width:120px;">商户编号</td>
						<td><input  name="Code" class="input-xlarge"  autofocus="true" value=""/></td>
					</tr>
					<tr><td colspan=4>&nbsp;</td></tr>
					<tr>
						<td>登录账号名*</td>
						<td><input  name="LoginName" class="input-xlarge" required="true" autofocus="true" value=""/></td>
						<td>登录密码*</td>
						<td><input  name="LoginPwd" class="input-xlarge" required="true" autofocus="true" value=""/></td>
					</tr>
					<tr><td colspan=4>&nbsp;</td></tr>
					<tr>
						<td>联系电话</td>
						<td colspan=3><input  name="Telphone" class="input-xlarge"  autofocus="true" value=""/></td>
					</tr>
					<tr><td colspan=4>&nbsp;</td></tr>
					<tr>
						<td colspan=4 style=" background-color: #f9f9f9;">合作期限:</td>
					</tr>
					<tr><td colspan=4>&nbsp;</td></tr>
					<tr>
						<td>开始日期*(含当天)</td>
						<td><input  name="BeginDt" class="dateInputBind start"  autofocus="true" required="true"  value=""/></td>
						<td>结束日期*(含当天)</td>
						<td><input  name="EndDt" class="dateInputBind end"  autofocus="true" required="true"  value=""/></td>
					</tr>
					<tr><td colspan=4>&nbsp;</td></tr>
					<tr>
						<td>渠道类型:</td>
						<td>
							<label for="ConentTypeUrl">批次号&nbsp;&nbsp;<input type="radio"  id="ConentTypeUrl" name="ChannelType" required="true" value="1" style="margin-top:-2px;" checked=true onclick="javascript:showTr('trBatchNum','trPhoneList');"/></label>
						</td>
						<td>							
							<label for="ConentTypeText">手机号&nbsp;&nbsp;<input type="radio"  id="ConentTypeText" name="ChannelType" required="true" value="2" style="margin-top:-2px;"onclick="javascript:showTr('trPhoneList','trBatchNum');" /></label>							
						</td>
						<td></td>
					</tr>
					<tr><td colspan=4>&nbsp;</td></tr>
					<tr id="trBatchNum">
						<td>批次号前缀*</td>
						<td><input  name="PromoterPrefixName"  id="PromoterPrefixName" class="input-xlarge"  autofocus="true"  value=""/></td>
						<td>批次号最大数量*</td>
						<td><input  name="MaxPromoterNum"  id="MaxPromoterNum" class="input-xlarge"  autofocus="true"  value=""/></td>
					</tr>	
					<tr id="trPhoneList" style="display:none;">
						<td></td>
						<td colspan=3>
							<textarea maxlength="30000" id="PhoneList" name="PhoneList" rows="5" style="width:600px;" class="input-xlarge" autofocus="true"></textarea>
						</td>
					</tr>	
					<tr>
						<td colspan=4 style="text-align:center;">
							<input type="hidden" name="submit" value="1" />
							<div class="btn-toolbar">
								<button type="submit" class="btn btn-primary">
									<strong>提交</strong>
								</button>
							</div>
						</td>
					</tr>
				</table>					
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
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
function showTr(pvShown,pvHidden)
{
	$("#"+pvHidden).hide();
	$("#"+pvShown).show();
}
function checkData()
{
	if($("#ConentTypeUrl").attr("checked")=="checked"){
		var lvPromoterPrefixName=$("#PromoterPrefixName").val();
		var lvMaxPromoterNum=$("#MaxPromoterNum").val();
		if(lvPromoterPrefixName=="" || lvMaxPromoterNum=="" ){
			layer.alert("请确认输入批次号前缀和最大数量。", 8);
			return false;
		}
	}
	return true;
}
</script>