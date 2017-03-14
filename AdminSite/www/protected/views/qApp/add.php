<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">微信游戏信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="/qApp/add" onsubmit="return checkSubmit();">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">游戏名称*</td>
						<td><input type="text" maxlength="50" name="AppName" id="AppName" value="" onclick="javascript:selectApp();" class="input-xlarge" autofocus="true"  required="true" /></td>
						<td style="width:100px;">基础返利*</td>
						<td><input type="text" maxlength="20" name="BaseProrate" id="BaseProrate"  value="" class="input-xlarge" autofocus="true" style="width:120px;" required /> %</td>
					</tr>
					<tr>
						<td>是否首发</td>
						<td>
							<select name="IsFirstPublish" class="input-xlarge">
								<option value="0" selected>非首发</option>
								<option value="1" >首发</option>
							</select>
						</td>
						<td>排列顺序</td>
						<td><input type="text" maxlength="50" name="AppOrder" id="AppOrder" value="" class="input-xSmall" autofocus="true" style="width:80px;" required="true" /></td>
					</tr>
					<tr>
						<td>是否独家</td>
						<td>
							<select name="IsSinglePublish" class="input-xlarge">
								<option value="0" selected>非独家</option>
								<option value="1" >独家</option>
							</select>
						</td>
						<td>显示其他标签</td>
						<td>
							<select name="IsOtherPublish" class="input-xlarge">
								<option value="0" selected>不显示</option>
								<option value="1" >显示</option>
							</select>
						</td>						
					</tr>					
					<tr>
						<td>限时截至时间</td>
						<td colspan=2>
							<input type='text' maxlength='50' class='dateInputBind start' name="PromoteStartDt" id="PromoteStartDt" value="" style="width:140px;" />
							~
							<input type='text' maxlength='50' class='dateInputBind end' name="PromoteDt" id="PromoteDt" value="" style="width:140px;" />
							限时高返:
						</td>
						<td><input type="text" maxlength="20" name="PromoteProrate" id="PromoteProrate"  value="" class="input-xlarge" autofocus="true" style="width:120px;" /> %</td>
					</tr>
					<tr>
						<td>上线状态</td>
						<td>
							<select name="Status" class="input-xlarge">
								<option value="1">在线</option>
								<option value="2" selected>白名单可见</option>
								<option value="3">下线</option>
							</select>
						</td>
						<td>测试白名单</td>
						<td><input type="text" maxlength="50000" name="AclList" value="" class="input-xlarge" autofocus="true" /></td>
					</tr>
					<tr>
						<td>活动一:</td>
						<td><input type='text' maxlength='100' class="input-xlarge" name="EvnTitle1" id="EvnTitle1" value="" placeholder="活动标题" /></td>
						<td colspan=2>
							<input type='text' maxlength='1000' class="input-xlarge" name="EvnUrl1" id="EvnUrl1" value=""  placeholder="活动地址"  style="width:370px;"  />
						</td>
					</tr>
					<tr>
						<td>活动二:</td>
						<td><input type='text' maxlength='100' class="input-xlarge" name="EvnTitle2" id="EvnTitle2" value="" placeholder="活动标题" /></td>
						<td colspan=2>
							<input type='text' maxlength='1000' class="input-xlarge" name="EvnUrl2" id="EvnUrl2" value=""  placeholder="活动地址"  style="width:370px;"  />
						</td>
					</tr>
					<tr>
						<td>活动三:</td>
						<td><input type='text' maxlength='100' class="input-xlarge" name="EvnTitle3" id="EvnTitle3" value="" placeholder="活动标题" /></td>
						<td colspan=2>
							<input type='text' maxlength='1000' class="input-xlarge" name="EvnUrl3" id="EvnUrl3" value=""  placeholder="活动地址"  style="width:370px;"  />
						</td>
					</tr>
					<tr>
						<td>活动四:</td>
						<td><input type='text' maxlength='100' class="input-xlarge" name="EvnTitle4" id="EvnTitle4" value="" placeholder="活动标题" /></td>
						<td colspan=2>
							<input type='text' maxlength='1000' class="input-xlarge" name="EvnUrl4" id="EvnUrl4" value=""  placeholder="活动地址"  style="width:370px;"  />
						</td>
					</tr>
					<tr>
						<td>活动五:</td>
						<td><input type='text' maxlength='100' class="input-xlarge" name="EvnTitle5" id="EvnTitle5" value="" placeholder="活动标题" /></td>
						<td colspan=2>
							<input type='text' maxlength='1000' class="input-xlarge" name="EvnUrl5" id="EvnUrl5" value=""  placeholder="活动地址"  style="width:370px;"  />
						</td>
					</tr>
				</table>
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
<?php $this->widget('application.widget.AppSelector'); ?>

<script type="text/javascript">
$(".dateInputBind").each(function(){
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

function selectApp()
{
	showAppSelector(saveAppCallback);	
}
function saveAppCallback(pvAppId,pvAppName,pvProrate)
{
	$("#AppId").val(pvAppId);	
	$("#AppName").val(pvAppName);
}

function parseDate(str)
{
    return new Date(Date.parse(str.replace(/-/g,"/")));
}

function checkSubmit()
{
	try{
		var lvBaseProrate=$("#BaseProrate").val();
		var iBaseProrate=parseInt(lvBaseProrate);
		if((""+iBaseProrate)!=lvBaseProrate){
			layer.alert("基础返利值有错误，请填写整形数，请检查！",8);
			return false;
		}
		if(iBaseProrate>=100 || iBaseProrate<=0){
			layer.alert("基础返利值有错误,范围在1~100之间，请检查！",8);
			return false;
		}
		var lvPromoteProrate=$("#PromoteProrate").val();
		if(lvPromoteProrate!=""){
			var iPromoteProrate=parseInt(lvPromoteProrate);
			if((""+iPromoteProrate)!=lvPromoteProrate){
				layer.alert("限时高返值有错误，请填写整形数，请检查！",8);
				return false;
			}
			if(iPromoteProrate>=100 || iPromoteProrate<iBaseProrate){
				layer.alert("限时高返值有错误,范围在 基础返利~100之间，请检查！",8);
				return false;
			}
			var lvPromoteStartDt=$("#PromoteStartDt").val();
			if(lvPromoteStartDt==""){
				layer.alert("限时高返必须设置开始时间，请检查！",8);
				return false;
			}
			var lvPromoteDt=$("#PromoteDt").val();
			if(lvPromoteDt==""){
				layer.alert("限时高返必须设置截至时间，请检查！",8);
				return false;
			}

			var startDt=parseDate(lvPromoteStartDt);
			var endDt=parseDate(lvPromoteDt);
			if(startDt>endDt){
				layer.alert("时间设置错误，结束时间早于开始时间。请检查！",8);
				return false;
			}
		}
	}catch(ex){
		alert(ex);
		return false;
	}
	return true;
}
</script>