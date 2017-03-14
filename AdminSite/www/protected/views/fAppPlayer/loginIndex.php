<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">分红用户登录查询</a>
	<div id="search" class="in collapse">
		<form class="form_search"  method="POST" style="margin-bottom:0px" action="/fAppPlayer/loginIndex" >
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
				
					<tr>
						<td style="width:120px;">开始时间：</td>
						<td><input type='text' maxlength='20' class='dateInputBind start' name="startDt" value="<?php echo $startDt; ?>" required  /></td>
						<td style="width:120px;">结束时间：</td>
						<td><input type='text' maxlength='20' class='dateInputBind end' name="endDt"  value="<?php echo $endDt;?>" required  /></td>
					</tr>
					<tr>
						<td>分红用户账号：</td>
						<td><input type='text' maxlength='20'  name="Phone" value="<?php echo $Phone;?>" /></td>
						<td>推广员账号:</td>
						<td><input type='text' maxlength='20'  name="PromoterPhone"  value="<?php echo $PromoterPhone;?>" /></td>						
					</tr>
					<tr>
						<td>游戏:</td>
						<td>
							<select name="AppId">
								<option value="" <?php echo empty($AppId)?" selected ":"";?>>全部游戏</option>
							<?php
								foreach ($GameList as $row){
									echo sprintf("<option value=\"%s\" %s>%s</option>",$row["AppId"], ($row["AppId"]==$AppId?" selected ":""), $row["AppName"]);
								} 
							?>														
							</select>						
						</td>
						<td colspan=2>
							<button type="submit" class="btn btn-primary">查  询</button>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
								if(count($LoginList)>0){
									echo "<button type=\"button\" class=\"btn btn-primary\" onclick=\"exportCSV();\">导出</button>";
								} 
?>						
						</td>
					</tr>										
				</table>			
			</div>
			<div style="clear:both;"></div>
			<input type="hidden" name="search" value="1" >
			<input type="hidden" name="export" value="0" id="export">
		</form>
	</div>
	
	
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:130px;">分红用户账</th>
					<th style="width:90px;">所属推广员</th>
					<th style="width:140px;">登录游戏</th>
					<th style="width:80px;">登录时间</th>
				</tr>
			</thead>
			<tbody>
<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
<tr>
EndOfRowTag;
			foreach ($LoginList as $rowItem){
				echo sprintf($rowsHtmlTag,
						$rowItem["PlayerPhoneNo"],
						$rowItem["PhoneNo"],
						$rowItem["AppName"],
						$rowItem["loginDt"]);
			}
?>				
			</tbody>
		</table>
		<?php echo $page;?>
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

function exportCSV()
{
	$("#export").val(1);
	$(".form_search").submit();
	$("#export").val(0);
}
</script>