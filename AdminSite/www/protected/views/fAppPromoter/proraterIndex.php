<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">推广员基础信息数据</a>
	<div id="search" class="in collapse">
		<form class="form_search"  method="POST" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
				
					<tr>
						<td style="width:120px;">开始时间：</td>
						<td><input type='text' maxlength='20' class='dateInputBind start' name="startDt" /></td>
						<td style="width:120px;">结束时间：</td>
						<td><input type='text' maxlength='20' class='dateInputBind end' name="endDt" /></td>
					</tr>
					<tr>
						<td>推广员账号：</td>
						<td><input type='text' maxlength='20'  name="Phone" /></td>
						<td colspan=2><button type="submit" class="btn btn-primary">查  询</button></td>
					</tr>
				</table>			
				<input type="hidden" name="search" value="1" >
			</div>
			<div style="clear:both;"></div>
		</form>
	</div>
	
	
	<div id="page-stats" class="block-body collapse in">
		<div style="float:left;margin-right:5px;margin-left:5px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:140px;">分红用户累计充值：</td>
						<td>9,000.00</td>
						<td style="width:140px;">累计获得返利</td>
						<td>1,200.00</td>
					</tr>
				</table>			
		</div>
		<div style="clear:both;"></div>

		<table class="table table-striped">
			<thead>
				<tr>
					<th>分红用户账号</th>
					<th>充值时间</th>
					<th>充值金额</th>
					<th>返利比例</th>
					<th>返利金额</th>
					<th>充值游戏</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
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