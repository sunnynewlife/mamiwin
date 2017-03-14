<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">活动消息发送设置-发送设置</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="" enctype="multipart/form-data" onsubmit="return checkSendTime();">
				<table class="table table-striped" style="width:815px;">
					<tr><td>发送目标用户上传：（上传txt文档）</td></tr>
				</table>
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:160px;">选择文件</td>
						<td><input type="file"  enctype="multipart/form-data" name="PhoneFile" class="input-xlarge" autofocus="true" ></td>
					</tr>
					<tr>
						<td colspan=2>&nbsp;</td>
					</tr>
					<tr>
						<td colspan=2><?php echo empty($Information["PhoneList"])?"未上传":(sprintf("上传共有<font color=red>%s</font>个用户",$Information["PhoneListFileCounts"])); ?></td>
					</tr>
					<tr>
						<td colspan=2>&nbsp;</td>
					</tr>
					<tr>
						<td>发送手机号预览:</td>
						<td><textarea rows="3" style="width:300px;" class="input-xlarge" autofocus="true" readonly><?php echo $PhonePreview;?></textarea></td>
					</tr>
				</table>
				<br/>
				<table class="table table-striped" style="width:815px;">
					<tr><td>发送时间设置</td></tr>
				</table>
				<table class="tableApp" style="width:815px;">
					<tr>
						<td>
							<label for="sendNow">立即发送&nbsp;&nbsp;<input type="radio"  id="sendNow" name="sendTime" required="true" value="now" style="margin-top:-2px;"   <?php echo empty($Information["ScheduleTime"])?" checked=true ":" "?>  onclick="javascript:sendNowClick(true);"/></label>
						</td>
					</tr>
					<tr>
						<td colspan=2>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<label for="sendWill">定时发送&nbsp;&nbsp;<input type="radio"  id="sendWill" name="sendTime" required="true" value="will" style="margin-top:-2px;" <?php echo empty($Information["ScheduleTime"])?" ":" checked=true "?> onclick="javascript:sendNowClick(false);" /></label>
						</td>
					</tr>
					<tr id="trScheduleTime" <?php echo empty($Information["ScheduleTime"])?" style='display:none;' ":" "?>>
						<td>
							<input type="text" maxlength="50" id="scheduleTime" name="scheduleTime" value="<?php echo $Information["ScheduleTime"];?>" class="input-xlarge" autofocus="true"  />&nbsp;&nbsp;时间格式：<font color=red>2000-01-01 09:00:00</font>
						</td>
					</tr>
				</table>
				<br/>
								
				<input type="hidden" name="InformationId" id="InformationId" value="<?php echo $Information["InformationId"];?>" />
				<input type="hidden" name="SessionId" value="<?php echo $Information["SessionId"];?>" />
				
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="button" id="btnBack"  class="btn btn-primary" onclick="javascript:window.location='/fAppMsgHistory/modify?InformationId=<?php echo $Information["InformationId"];?>';">
						<strong>回到上一步</strong>
					</button>
					<button type="submit" class="btn btn-primary" id="btnSubmit" <?php echo $Information["State"]==1?" disabled=true ": " ";?>>
						<strong>保存发送设置</strong>
					</button>
					<button type="button" class="btn btn-primary" id="btnSendMsg" <?php echo $Information["State"]==1 || empty($Information["PhoneList"]) ?" disabled=true ": " ";?>  onclick="javascript:ClickSendMsg(<?php echo $Information["InformationId"];?>,0);">
						<strong>提交发送</strong>
					</button>
				</div>
				<br/>
				<label id="SentProgress"></label>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
function sendNowClick(isNow)
{
	if(isNow){
		$("#trScheduleTime").hide();
	}else{
		$("#trScheduleTime").show();
	}
}
function checkSendTime()
{
	var isOk=false;
	var msg="";
	if($("#sendNow").attr("checked")=="checked"){
		isOk=true;
	}else {
		var lvScheduleTime=$("#scheduleTime").val();
		if(lvScheduleTime==""){
			msg="请输入定时发送的时间。";
		}else {
			var scheduleDt=parseDate(lvScheduleTime);
			if(scheduleDt==NaN){
				msg="定时发送时间格式错误。";
			}else{
				var nowDt=new Date();
				if(scheduleDt<nowDt){
					msg="不能设置定时发送的时间为过去的时间。";
				}else{
					isOk=true;
				}
			}
		}
	}
	if(isOk==false){
		layer.alert(msg, 8);
	}
	return isOk;
}
function parseDate(str)
{
    return new Date(Date.parse(str.replace(/-/g,"/")));
}
function ClickSendMsg(InformationId,finished)
{
	$("#btnBack").attr("disabled",true);
	$("#btnSubmit").attr("disabled",true);
	$("#btnSendMsg").attr("disabled",true);
	$("#SentProgress").html("正在发送消息，请停留在此页面等待。。。");
	SendMsg(InformationId,finished);
}
function SendMsg(InformationId,finished)
{
	var InformationId=$("#InformationId").val();
	var TestUrl="/fAppMsgHistory/sendMsg";
	var TestData={"InformationId":InformationId,"finished":finished};
	$.ajax(
		{
			url:TestUrl,
			data:TestData,
			success: function(json){
						if(json.return_code==0){
							var msg="Total Count:"+json.total_num+" finished:"+json.finished;
							$("#SentProgress").html("已完成<font color=red>"+json.finished+"/"+json.total_num+"</font>发送，请停留在此页面等待");
							if(json.finished<json.total_num){
								SendMsg(InformationId,json.finished);
							}else{
								window.location="/fAppMsgHistory/index";
							}
						}else{
							//layer.alert(json.return_msg,8);
							sleep(3000);
							SendMsg(InformationId,finished);
						}
			         },
			timeout:5000,
			error:function(XMLHttpRequest, textStatus, errorThrown){
						if(textStatus!="success"){
							var errorMsg="textStatus:"+textStatus+" Message:"+errorThrown.message+" Description:"+errorThrown.description;
							//layer.alert(errorMsg,8);
							sleep(3000);
							SendMsg(InformationId,finished);							
						}						
					},
			dataType:"json"
		}
	);			
}
function sleep(sleepTime) 
{
    for(var start = Date.now(); Date.now() - start <= sleepTime; ) { }
}
</script>