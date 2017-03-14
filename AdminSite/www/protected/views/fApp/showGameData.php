<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">游戏分红数据</a>
	<div id="search" class="in collapse">
		<form class="form_search"  method="POST" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px;margin-left:5px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">游戏名称：</td>
						<td><?php echo $GameSummary["AppName"]; ?></td>
						<td style="width:120px;">累计收入</td>
						<td><?php echo number_format($GameSummary["Deposit"],2,".",","); ?></td>
					</tr>
					<tr>
						<td>安装包申请量：</td>
						<td><?php echo number_format($GameSummary["AppPackageCounts"],0,".",","); ?></td>
						<td>累计消费人数:</td>
						<td><?php echo number_format($GameSummary["AppDepositPlayCount"],0,".",","); ?></td>
					</tr>
					<tr>
						<td colspan=4>&nbsp;</td>
					</tr>
					<tr>
						<td>开始时间：</td>
						<td><input type='text' maxlength='20' class='dateInputBind start' name="startDt" /></td>
						<td>结束时间：</td>
						<td><input type='text' maxlength='20' class='dateInputBind end' name="endDt" /></td>
					</tr>
					<tr>
						<td colspan=4 style="padding-left:390px;"><button type="submit" class="btn btn-primary">查  询</button></td>
					</tr>					
				</table>			
				<input type="hidden" name="search" value="1" >
				<input type="hidden" name="AppId" value="" >
			</div>
			<div style="clear:both;"></div>
		</form>
	</div>
	
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:140px;" >开始时间</th>
					<th style="width:140px;">结束时间</th>
					<th style="width:70px;text-align:right;">返利比例</th>
					<th style="width:40px;">状态</th>
					<th style="width:90px;text-align:right;">累计消费人数</th>
					<th style="width:90px;text-align:right;">累计返利人数</th>
					<th style="width:100px;text-align:right;">累计消费</th>
					<th style="text-align:right;">累计返利</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td style="text-align:right;">%s</td>
	<td>%s</td>
	<td style="text-align:right;">%s</td>
	<td style="text-align:right;">%s</td>
	<td style="text-align:right;">%s</td>			
	<td style="text-align:right;">%s</td>
<tr>
EndOfRowTag;
			 	foreach ($GameSummary["AppProfitDetail"] as $item){
					$profit=$item["profit"];
					$rowHtml=sprintf($rowsHtmlTag,
						date_format($item["startDt"],"Y-m-d H:i:s"),
						date_format($item["endDt"],"Y-m-d H:i:s"),
						empty($item["PromoterProrate"])?"":(number_format($item["PromoterProrate"]*100,0,".","")."%"),
						$item["status"],
						empty($profit["play_count"])?"":number_format($profit["play_count"],0,".",","),
						empty($profit["promoter_count"])?"":number_format($profit["promoter_count"],0,".",","),
						empty($profit["deposit"])?"":number_format($profit["deposit"],2,".",","),
						empty($profit["withdraw"])?"":number_format($profit["withdraw"],2,".",","));
					echo $rowHtml; 
				}
			?>
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
