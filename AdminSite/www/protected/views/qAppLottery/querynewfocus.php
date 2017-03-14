<div class="btn-toolbar">
	<a href="/qAppLottery/index" class="btn btn-primary"><i class="icon-filter"></i>中奖查询</a>
	<a href="/qAppLottery/query" class="btn btn-primary"><i class="icon-filter"></i>剩余奖品查询</a>
	<a href="/qAppLottery/queryNewFocus" class="btn btn-primary"><i class="icon-filter"></i>新用户关注查询</a>
</div>
<div id="search" class="in collapse">
	<form class="form_search" action="/qAppLottery/index" method="POST" style="margin-bottom: 0px">
		<div style="display:none;float: left; margin-right: 5px; margin-left: 15px; margin-top: 25px;">
			<table class="tableApp" style="width: 815px;" border="0">
					<tr>
						<td style="width:120px;">查询时间：</td>
						<td><input type='text' maxlength='20' class='dateInputBind start' name="LotteryDt" value="<?php echo $LotteryDt; ?>"  /></td>
						<td style="width:120px;"></td>
						<td></td>
					</tr>
			</table>
		</div>
		<div class="btn-toolbar"
			style="padding-top: 25px; padding-bottom: 0px; margin-bottom: 0px">
			<input type="hidden" name="search" value="1">
			<button type="submit" class="btn btn-primary">查询</button>
		</div>
		<div style="clear: both;"></div>
	</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">礼券列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width: 100px;">手机号码</th>
					<th style="width: 150px;">绑定日期</th>
					<th style="width: 150px;">领取时间</th>
				</tr>
			</thead>
			<tbody>
<?php
$rowsHtmlTag = <<<EndOfRowTag
			<tr>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
			</tr>
EndOfRowTag;
			foreach ($focus_list as $item){
				$PhoneNo  = $item["PhoneNo"] ; 
				$BindDt  = $item["BindDt"] ; 
				$TransactionDt  = $item["TransactionDt"] ; 
				
				echo sprintf($rowsHtmlTag,
					$PhoneNo,$BindDt,$TransactionDt);
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
		// timeFormat: 'HH:mm:ss',
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
