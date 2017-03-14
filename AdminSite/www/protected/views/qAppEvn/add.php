<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">活动信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="/qAppEvn/add" onsubmit="return checkSubmit();">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">活动名称*</td>
						<td><input type="text" maxlength="20" name="EvnName" id="EvnName" value="" class="input-xlarge" required="true" autofocus="true" /></td>
						<td style="width:100px;">活动图片*</td>
						<td><input type="text" maxlength="50" name="FileId" value=""  id="FileId" onclick="javascript:selectPics('FileId',false);" class="input-xlarge" autofocus="true" required="true" /></td>
					</tr>
					<tr>
						<td>游戏名称*</td>
						<td><input type="text" maxlength="50" name="AppName" id="AppName" value="" onclick="javascript:selectApp();" class="input-xlarge" autofocus="true"  required="true" /></td>
						<td>基础返利*</td>
						<td><input type="text" maxlength="20" name="BaseProrate" id="BaseProrate"  value="" class="input-xlarge" autofocus="true" required /> %</td>
					</tr>
					<tr>
						<td>活动类型*</td>
						<td>
							<select name="EvnType"  id="EvnType" class="input-xlarge"  onchange="javascript:changeEvnType();">
								<option value="1">非阶梯式返利</option>
								<option value="2">阶梯式返利</option>
							</select>	
						</td>
						<td>活动对象</td>
						<td>
							<select name="EvnJoinType" class="input-xlarge">
								<option value="1">全平台用户</option>
								<option value="2">报名用户 </option>
							</select>	
						</td>
					</tr>
					<tr>
						<td>活动时间*</td>
						<td>
							<input type='text' maxlength='20' class='dateInputBind start' name="startDt" id="startDt" value="" style="width:80px;" required="true" />
							&nbsp;&nbsp;~&nbsp;&nbsp;
							<input type='text' maxlength='20' class='dateInputBind end' name="endDt" id="endDt" value="" style="width:80px;" required="true" />
						</td>
						<td>排列顺序</td>
						<td><input type="text" maxlength="50" name="EvnOrder" id="EvnOrder" value="" class="input-xSmall" autofocus="true" style="width:80px;" required="true" /></td>
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
						<td>活动概述*</td>
						<td colspan="3"><input type="text" maxlength="100" name="EvnIntro" value="" class="input-xlarge" autofocus="true" style="width:600px;" required="true" /></td>
					</tr>
					<tr>
						<td>活动描述*</td>
						<td colspan="3"><textarea id="EvnContent" maxlength="20000" name="EvnContent" rows="8" style="width:600px;" class="input-xlarge" required="true" autofocus="true"></textarea></td>
					</tr>
					
				</table>
			
				<table class="table table-striped" style="width:950px;">
					<tr><td>活动条件配置</td></tr>
				</table>
				<table class="tableApp" style="width:950px;">
					<tr>
						<td style="width:120px;">人数随机累计</td>
						<td>
							<input type="text" maxlength="50" name="EvnRandMin" id="EvnRandMin" value="" class="input-xSmall" autofocus="true" style="width:80px;" />
							&nbsp;&nbsp;&nbsp;&nbsp;~&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" maxlength="50" name="EvnRandMax" id="EvnRandMax" value="" class="input-xSmall" autofocus="true" style="width:80px;" />
						</td>
						<td colspan=2>随机数可不填。填写后每增加一个参与的用户，系统自动累加一个填写的随机数</td>
					</tr>
				</table>
				<br/>
				
				<table class="tableApp" style="width:950px;" id="simpleEvn">
					<tr>
						<td style="width:120px;">人数要求</td>
						<td><input type="text" maxlength="50" name="EvnQty" id="EvnQty" value="" class="input-xlarge" autofocus="true" style="width:140px;" /></td>
						<td style="width:100px;">活动返利</td>
						<td><input type="text" maxlength="50" name="Prorate" id="Prorate" value="" class="input-xlarge" autofocus="true" style="width:140px;" />&nbsp;&nbsp;%</td>
					</tr>
				</table>
				
				<table class="tableApp" style="width:950px;display:none;" id="complexEvn">
					<tr>
						<td colspan=5 style="background:darkgray;text-align:center;">阶梯返利配置<input type="button" value="增加" onclick="javascript:addProrateRow();" style="float:right;" /></td>
					</tr>
					<tr>
						<td style="width:60px;">序号</td>
						<td style="width:180px;">要求人数</td>
						<td style="width:180px;">返利（%）</td>
						<td style="width:120px;">操作</td>
						<td>&nbsp;</td>
					</tr>
				</table>

				<br/>
				
				<input type="hidden" name="submit" value="1" />
				<input type="hidden" name="AppId" Id="AppId" value="" />
				<input type="hidden" name="DynProrateCfg" Id="DynProrateCfg" value="" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary">
						<strong>提交</strong>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php $this->widget('application.widget.PicSelector'); ?>
<?php $this->widget('application.widget.AppSelector'); ?>

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

function selectApp()
{
	showAppSelector(saveAppCallback);	
}
function saveAppCallback(pvAppId,pvAppName,pvProrate)
{
	$("#AppId").val(pvAppId);	
	$("#AppName").val(pvAppName);
}
function selectPics(pvTxtId,CanMutilChoice)
{
	showPicSelector("",pvTxtId,$("#"+pvTxtId).val(),CanMutilChoice);
}
function changeEvnType()
{
	var lvEvnType=$("#EvnType").val();
	if(lvEvnType=="1"){
		$("#simpleEvn").show();
		$("#complexEvn").hide();
	}
	else {
		$("#complexEvn").show();
		$("#simpleEvn").hide();
	}
}
function addProrateRow()
{
	var currentId=(new Date().getTime());
	var rowCount=document.getElementById("complexEvn").rows.length;
	var lvNoId=1+rowCount-2;
	var newRow = "<tr id='tr_"+currentId+"'><td>"+lvNoId+"</td><td><input type='text' maxlength='50' value='' class='input-xlarge' autofocus='true' style='width:140px;' /></td><td><input type='text' maxlength='50' value='' class='input-xlarge' autofocus='true' style='width:140px;' /></td><td><a href='javascript:delProrateRow("+currentId+");'>删除</a></td><td>&nbsp;</td></tr>";
	$("#complexEvn tr:last").after(newRow);
}
function delProrateRow(pvId)
{
	$("#tr_"+pvId).remove();
	var dynProrateRow=document.getElementById("complexEvn").rows;
	var rowCount=dynProrateRow.length;
	for(var i=2;i<rowCount;i++){
		var lvNoId=i-1;
		dynProrateRow[i].cells[0].innerHTML=lvNoId;
	}
}
function parseDate(str)
{
    return new Date(Date.parse(str.replace(/-/g,"/")));
}

function checkSubmit()
{
	try{
		var lvEvnType=$("#EvnType").val();
		
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
		
		var startDt=parseDate($("#startDt").val());
		var endDt=parseDate($("#endDt").val());
		if(startDt>endDt){
			layer.alert("时间设置错误，结束时间早于开始时间。请检查！",8);
			return false;
		}
		
		if(lvEvnType=="1"){
			if($("#EvnQty").val()==""){
				layer.alert("请填写活动条件配置的人数要求！",8);
				return false;
			}
			var lvEvnQty=$("#EvnQty").val();
			var iEvnQty=parseInt(lvEvnQty);
			if((""+iEvnQty)!=lvEvnQty){
				layer.alert("人数要求值有错误，请填写整形数，请检查！",8);
				return false;
			}
			if(iEvnQty<=0){
				layer.alert("人数要求值有错误,范围应当大于0，请检查！",8);
				return false;
			}
			
			if($("#Prorate").val()==""){
				layer.alert("请填写活动条件配置的活动返利！",8);
				return false;
			}
			var lvProrate=$("#Prorate").val();
			var iProrate=parseInt(lvProrate);
			if((""+iProrate)!=lvProrate){
				layer.alert("返利值有错误，请填写整形数，请检查！",8);
				return false;
			}
			if(iProrate>=100 || iProrate<=0){
				layer.alert("返利值有错误,范围在1~100之间，请检查！",8);
				return false;
			}
		}
		else{
			
			var dynProrateRow=document.getElementById("complexEvn").rows;
			var rowCount=dynProrateRow.length;
			if(rowCount<=2){
				layer.alert("阶梯式返利未配置，请至少填写1个返利配置！",8);
				return false;
			}
			var postDynProrateCfgValue="";
			for(var i=2;i<rowCount;i++){
				var dynEvnQty=$(dynProrateRow[i].cells[1]).find("input").val();
				var dynProrate=$(dynProrateRow[i].cells[2]).find("input").val();
				if(dynEvnQty==""){
					layer.alert("请填写阶梯式返利的人数要求！",8);
					return false;
				}
				var iEvnQty=parseInt(dynEvnQty);
				if((""+iEvnQty)!=dynEvnQty){
					layer.alert("人数要求值有错误，请填写整形数，请检查！",8);
					return false;
				}
				if(iEvnQty<=0){
					layer.alert("人数要求值有错误,范围应当大于0，请检查！",8);
					return false;
				}
				if(dynProrate==""){
					layer.alert("请填写阶梯式返利的活动返利！",8);
					return false;
				}
				var iProrate=parseInt(dynProrate);
				if((""+iProrate)!=dynProrate){
					layer.alert("返利值有错误，请填写整形数，请检查！",8);
					return false;
				}
				if(iProrate>=100 || iProrate<=0){
					layer.alert("返利值有错误,范围在1~100之间，请检查！",8);
					return false;
				}
				postDynProrateCfgValue=postDynProrateCfgValue+"|"+dynEvnQty+","+dynProrate;
			}
			$("#DynProrateCfg").val(postDynProrateCfgValue);
		}
	}catch(ex){
		alert(ex);
		return false;
	}
	return true;
}
</script>