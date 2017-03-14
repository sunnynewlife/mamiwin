<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">礼包信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">礼包状态</td>
						<td>
							<select name="Status" class="input-xlarge">
								<option value="1" <?php echo $QGiftItem["Status"]=="1"?" selected":"";?>>在线</option>
								<option value="2" <?php echo $QGiftItem["Status"]=="2"?" selected":"";?>>白名单可见</option>
								<option value="3" <?php echo $QGiftItem["Status"]=="3"?" selected":"";?>>下线</option>
							</select>
						</td>
						<td style="width:120px;">上架时间</td>
						<td><input type='text' name="OpenDt" value="<?php echo isset($QGiftItem["OpenDt"])?$QGiftItem["OpenDt"]:"";?>" maxlength='30' class='dateTimeInputBind' /></td>
					</tr>
					<tr>
						<td>测试白名单</td>
						<td colspan=3>
							<textarea id="AclList" maxlength="50000" name="AclList" rows="4" style="width:649px;" class="input-xlarge" autofocus="true"><?php echo isset($QGiftItem["AclList"])?$QGiftItem["AclList"]:"";?></textarea>
						</td>
					</tr>
					<tr>
						<td>是否热门</td>
						<td>
							<select name="Category" class="input-xlarge">
								<option value="1" <?php echo $QGiftItem["Category"]=="1"?" selected":"";?>>热门</option>
								<option value="2" <?php echo $QGiftItem["Category"]=="2"?" selected":"";?>>非热门</option>
							</select>
						</td>
						<td>排列顺序</td>
						<td><input type="text" maxlength="50" name="GiftOrder" id="GiftOrder" value="<?php echo $QGiftItem["GiftOrder"];?>" class="input-xSmall" autofocus="true" style="width:80px;" required="true" /></td>
					</tr>
					<tr>
						<td>礼包归属&nbsp;<font color=red>*</font></td>
						<td><input type="text" maxlength="50" name="AppName" id="AppName" value="<?php echo $QGiftItem["AppName"];?>" onclick="javascript:selectApp();" class="input-xlarge" autofocus="true"  required="true" /></td>
						<td>礼包标签:</td>
						<td>
							<label for="TagType1" style="display:inline;"><input type="checkbox" value="1" name="TagType1" id="TagType1" style="margin-top:-2px;" <?php echo $QGiftItem["TagType1"]=="1"?" checked":""; ?> />&nbsp;荐</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<label for="TagType2" style="display:inline;"><input type="checkbox" value="1" name="TagType2" id="TagType2" style="margin-top:-2px;" <?php echo $QGiftItem["TagType2"]=="1"?" checked":""; ?> />&nbsp;独</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<label for="TagType3" style="display:inline;"><input type="checkbox" value="1" name="TagType3" id="TagType3" style="margin-top:-2px;" <?php echo $QGiftItem["TagType3"]=="1"?" checked":""; ?> />&nbsp;新</label>
						</td>
					</tr>
					<tr>
						<td>礼包名称&nbsp;<font color=red>*</font></td>
						<td><input type="text" maxlength="20" name="Name" id="Name" value="<?php echo $QGiftItem["Name"];?>"  class="input-xlarge" autofocus="true"  required="true" /></td>
						<td>礼包图片&nbsp;<font color=red>*</font></td>
						<td><input type="text" maxlength="50" name="FileId" value="<?php echo $QGiftItem["FileId"];?>"  id="FileId" onclick="javascript:selectPics('FileId',false);" class="input-xlarge" autofocus="true" required="true"  style="width:206px;"/></td>
					</tr>
					<tr>
						<td>页面显示总量</td>
						<td><input type="text" maxlength="50" name="TotalCount" id="TotalCount" value="<?php echo $QGiftItem["TotalCount"];?>" class="input-xSmall" autofocus="true"  /></td>
						<td>页面显示剩余量</td>
						<td><input type="text" maxlength="50" name="RestCount" id="RestCount" value="<?php echo $QGiftItem["RestCount"];?>" class="input-xSmall" autofocus="true"  /></td>
					</tr>
					<tr>
						<td>礼包有效期</td>
						<td>
							<input type='text' maxlength='20' class='dateInputBind start' name="startDt" value="<?php echo isset($QGiftItem["StartDt"])?substr($QGiftItem["StartDt"],0,10):"";?>" style="width:80px;"  />
							&nbsp;&nbsp;~&nbsp;&nbsp;
							<input type='text' maxlength='20' class='dateInputBind end' name="endDt" value="<?php echo isset($QGiftItem["EndDt"])?substr($QGiftItem["EndDt"],0,10):"";?>" style="width:80px;"  />
						</td>
						<td colspan=2>不填永久有效</td>
					</tr>
					<tr>
						<td>礼包详情&nbsp;<font color=red>*</font></td>
						<td colspan="3"><textarea id="Content" maxlength="20000" name="Content" rows="8" style="width:649px;" class="input-xlarge" required="true" autofocus="true"><?php echo $QGiftItem["Content"];?></textarea></td>
					</tr>
					<tr>
						<td>礼包使用&nbsp;<font color=red>*</font></td>
						<td colspan="3"><textarea id="Guide" maxlength="20000" name="Guide" rows="8" style="width:649px;" class="input-xlarge" required="true" autofocus="true"><?php echo $QGiftItem["Guide"];?></textarea></td>
					</tr>
					<tr>
						<td></td>
						<td colspan=3>
							<input type="hidden" name="submit" value="1" />
							<input type="hidden" name="AppId" Id="AppId" value="<?php echo $QGiftItem["AppId"];?>" />
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
<?php $this->widget('application.widget.PicSelector'); ?>
<?php $this->widget('application.widget.AppSelector'); ?>

<script type="text/javascript">
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
</script>