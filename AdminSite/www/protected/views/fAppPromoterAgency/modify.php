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
						<td><input  name="Name" class="input-xlarge" required="true" autofocus="true" value="<?php echo $Agency["Name"];?>"/></td>
						<td style="width:120px;">商户编号</td>
						<td><input  name="Code" class="input-xlarge"  autofocus="true" value="<?php echo $Agency["Code"];?>"/></td>
					</tr>
					<tr><td colspan=4>&nbsp;</td></tr>
					<tr>
						<td>登录账号名*</td>
						<td><input  name="LoginName" class="input-xlarge" required="true" autofocus="true" value="<?php echo $Agency["LoginName"];?>"/></td>
						<td>联系电话</td>
						<td><input  name="Telphone" class="input-xlarge"  autofocus="true" value="<?php echo $Agency["Telphone"];?>"/></td>
					</tr>
					<tr><td colspan=4>&nbsp;</td></tr>
					<tr>
						<td colspan=4 style="background-color: #f9f9f9;">合作期限:</td>
					</tr>
					<tr><td colspan=4>&nbsp;</td></tr>
					<tr>
						<td>开始日期*(含当天)</td>
						<td><input  name="BeginDt" class="dateInputBind start"  autofocus="true" required="true"  value="<?php echo substr($Agency["BeginDt"],0,10);?>"/></td>
						<td>结束日期*(含当天)</td>
						<td><input  name="EndDt" class="dateInputBind end"  autofocus="true" required="true"  value="<?php echo substr($Agency["EndDt"],0,10);?>"/></td>
					</tr>
					<tr><td colspan=4>&nbsp;</td></tr>
					<tr>
						<td>渠道类型:</td>
						<td colspan=3>
							<?php  echo $Agency["ChannelType"]==1?"批次号":"手机号";?>
						</td>
					</tr>
					<tr><td colspan=4>&nbsp;</td></tr>
<?php
						if($Agency["ChannelType"]==1) {  
?>
					<tr id="trBatchNum">
						<td>批次号前缀*</td>
						<td><input  name="PromoterPrefixName"  id="PromoterPrefixName" class="input-xlarge"  autofocus="true"  value="<?php echo $Agency["PromoterPrefixName"];?>" required="true" /></td>
						<td>批次号最大数量*</td>
						<td><input  name="MaxPromoterNum"  id="MaxPromoterNum" class="input-xlarge"  autofocus="true"  value="<?php echo $Agency["MaxPromoterNum"];?>" required="true" /></td>
					</tr>
					<tr><td colspan=4>&nbsp;</td></tr>	
					<tr>
						<td>渠道账号:</td>
						<td colspan=3>
							<table>
								<thead>
									<tr style="background-color: #f9f9f9;font-bold:normal;">
										<th style="width:60px">序号</th>
										<th style="width:120px">推广账号</th>
										<th style="width:200px">创建日期</th>
									</tr>
								</thead>
								<tbody>
<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
<tr>
EndOfRowTag;
									$index=1;
									foreach ($Promoter as $row ){
										echo sprintf($rowsHtmlTag,$index++,$row["PhoneNo"],$row["CreateDt"]);										
									} 
?>								
								</tbody>
							</table>
						</td>
					</tr>
<?php
						}else { 
									$phone=array();
									foreach ($Promoter as $row ){
										$phone[]=$row["PhoneNo"];
									}
									$phoneTxt=implode(",", $phone);
?>					
					<tr id="trPhoneList">
						<td>渠道账号:</td>
						<td colspan=3>
							<textarea maxlength="30000" id="PhoneList" name="PhoneList" rows="5" style="width:600px;" class="input-xlarge" autofocus="true"><?php echo $phoneTxt;?></textarea>
						</td>
					</tr>	
<?php 					
						}
?>					
					<tr>
						<td colspan=4 style="text-align:center;">
							<input type="hidden" name="submit" value="1" />
							<input type="hidden" name="ChannelType" value="<?php echo $Agency["ChannelType"];?>" />
							<div class="btn-toolbar">
								<button type="submit" class="btn btn-primary">
									<strong>更新</strong>
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
</script>