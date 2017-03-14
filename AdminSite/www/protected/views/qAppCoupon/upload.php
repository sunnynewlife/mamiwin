<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">礼券发送</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" enctype="multipart/form-data" onsubmit="return checkSubmit();">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:200px;">礼券ID</td>
						<td><?php echo $QCouponItem["IDX"];?></td>
						<td style="width:120px;"></td>
						<td></td>
					</tr>
					<tr>
						<td>礼券类型</td>
						<td><?php echo($QCouponItem["Type"]=="1"?"通用礼券":"专属礼券");?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>礼券适用范围</td>
						<td><?php echo($QCouponItem['AppNames']);?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>当前可发送礼券数</td>
						<td><?php echo($QCouponItem['Quantity']);?></td>
						<td></td><td></td>
					</tr>
					<tr>
						<td>发送目标用户</td>
						<td colspan=3>
							<select id="isNewBindPlayer" name="isNewBindPlayer" onchange="javascript:bindTypeChange();">
								<option value="1" <?php echo $isNewBindPlayer=="1"?" checked":""; ?>>指定时间内绑定的用户</option>
								<option value="2" <?php echo $isNewBindPlayer=="2"?" checked":""; ?>>导入目标用户列表发送</option>
							</select>	
						</td>
					</tr>
					<tr id="div_selBindDate" style="display:<?php echo $isNewBindPlayer=="1"?"":"none"; ?>;">
						<td></td>
						<td>
							<input type='text' maxlength='20' class='dateInputBind start' name="BindStartDt" id="BindStartDt" value="<?php echo($BindStartDt);?>" style="width:80px;"  />
							&nbsp;&nbsp;~&nbsp;&nbsp;
							<input type='text' maxlength='20' class='dateInputBind end' name="BindEndDt" id="BindEndDt" value="<?php echo($BindEndDt);?>" style="width:80px;"  />
						</td>
						<td colspan=2>
						</td>
					</tr>
					<tr id="div_uploadfile" style="display:<?php echo $isNewBindPlayer=="2"?"":"none"; ?>;">
						<td></td>
						<td><input type="file"  enctype="multipart/form-data"  id="CouponPhoneNo" name="CouponPhoneNo" class="input-xlarge" autofocus="true" ></td>
						<td colspan=2></td>
					</tr>
					<tr>
						<td></td>
						<td colspan=3>
							<input type="hidden" name="submit" value="1" />
							<input type="hidden" name="idx" value="<?php echo $idx;?>" />
							<div class="btn-toolbar">
								<button type="submit" class="btn btn-primary">
									<strong>立即发送</strong>
								</button>
							</div>
						</td>
					</tr>
				</table>
			</form>
				<table class="tableApp" style="width:815px;display:<?php echo $show_result=="1"?"":"none";?>;">
					<tr>
						<td style="width:120px; text-align:right;">发送结果提示</td>
						<td style="width:40px;"></td>
						<td>上传手机数: <font color=red><?php echo $file_count;?></font>个。</td>
					</tr>
					<tr>
						<td style="text-align:right;">导入后</td>
						<td></td>
						<td>成功发送:<font color=red><?php echo isset($insertCounts)?$insertCounts:"";?></font>个</td>
					</tr>
				</table>
				<table class="tableApp" style="width:815px;display:<?php echo $show_result=="2"?"":"none";?>;">
					<tr>
						<td style="width:120px; text-align:right;">发送结果提示</td>
						<td style="width:40px;"></td>
						<td>成功发送: <font color=red><?php echo $insertCounts;?></font>个。</td>
					</tr>
				</table>
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

function checkSubmit()
{
	if($("#isNewBindPlayer").val()=="1"){
		if($("#BindStartDt").val()==""){
			layer.alert("请填写目标用户的开始绑定时间！",8);
			return false;
		}
		if($("#BindEndDt").val()==""){
			layer.alert("请填写目标用户的结束绑定时间！",8);
			return false;
		}
	}else if($("#isNewBindPlayer").val()=="2"){
		if($("#CouponPhoneNo").val()==""){
			layer.alert("请选择导入的目标用户手机列表文本文件！",8);
			return false;
		}
	}
	return true;
}

function bindTypeChange()
{
	if($("#isNewBindPlayer").val()=="1"){
		$("#div_selBindDate").show();
		$("#div_uploadfile").hide();
	}else if($("#isNewBindPlayer").val()=="2"){
		$("#div_selBindDate").hide();
		$("#div_uploadfile").show();
	}	
}
</script>
