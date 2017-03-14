<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">推广员信息查询</a>
	<div id="search" class="in collapse">
		<form class="form_search"  method="POST" action="/fAppPromoter/query" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
				
					<tr>
						<td style="width:120px;">开始时间：</td>
						<td><input type='text' maxlength='20' class='dateInputBind start' name="startDt" value="<?php echo $startDt; ?>"  /></td>
						<td style="width:120px;">结束时间：</td>
						<td><input type='text' maxlength='20' class='dateInputBind end' name="endDt"  value="<?php echo $endDt;?>"   /></td>
					</tr>
					<tr>
						<td>推广员账号:</td>
						<td><input type='text' maxlength='20'  name="PromoterPhone"  value="<?php echo $PromoterPhone;?>" /></td>
						<td>账号注册来源:</td>
						<td><input type='text' maxlength='20'  name="Channel"  value="<?php echo $Channel;?>" /></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td><button type="submit" class="btn btn-primary">查  询</button></td>
						<td>
<?php
						if(count($PromoterList)>0){
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
					<th style="width:120px;">推广员账号</th>
					<th style="width:120px;">注册时间</th>
					<th style="width:160px;">支付宝账号</th>
					<th style="width:160px;">支付宝账号姓名</th>
					<th style="width:120px;">注册来源</th>
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
	<td>%s</td>
<tr>
EndOfRowTag;
		foreach ($PromoterList as $rowItem){
			echo sprintf($rowsHtmlTag,
					$rowItem["PhoneNo"],
					$rowItem["CreateDt"],
					$rowItem["AliPayNo"],
					$rowItem["AliPayName"],
					$rowItem["Channel"]
				);
		}
?>				
			</tbody>
		</table>
		<?php echo $page;?>
	</div>
</div>
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

function exportCSV()
{
	$("#export").val(1);
	$(".form_search").submit();
	$("#export").val(0);
}
</script>