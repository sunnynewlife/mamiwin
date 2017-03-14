<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">充值查询</a>
	<div id="search" class="in collapse">
		<form class="form_search"  id="payListForm"  method="POST" style="margin-bottom:0px" action="/fAppPlayer/index">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
				
					<tr>
						<td style="width:120px;">开始时间：</td>
						<td><input type='text' maxlength='20' class='dateInputBind start' name="startDt" value="<?php echo $startDt; ?>" required /></td>
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
						<td>订单号：</td>
						<td colspan=3><input type='text' maxlength='100'  name="orderId" value="<?php echo $OrderId?>" style="width:615px;" /></td>
					</tr>
					<tr>
						<td>游戏AppID:</td>
						<td><input type='text' maxlength='20'  name="AppId" value="<?php echo $AppId;?>" /></td>
						<td><button type="submit" class="btn btn-primary">查  询</button></td>
						<td>
<?php
						if(count($PayList)>0){
							echo "<button type=\"button\" class=\"btn btn-primary\" onclick=\"exportCSV();\">导出</button>";
						} 
?>						
						
						</td>
					</tr>										
				</table>			
			</div>
			<div style="clear:both;"></div>
			<input type="hidden" name="search" value="1" >
			<input type="hidden" name="CheckingID" id="CheckingID" value="" >
			<input type="hidden" name="PayOperate" id="PayOperate" value="" >
			<input type="hidden" name="export" value="0" id="export">
		</form>
	</div>
	
	
	<div id="page-stats" class="block-body collapse in">
		<table class="tableApp" style="width:980px;">
			<tr>
				<td style="width:160px;">本页分红用户累计充值：</td>
				<td><?php echo $GameAmount;?></td>
				<td style="width:160px;">本页累计获得返利：</td>
				<td><?php echo $PromoterAmount;?></td>
			</tr>
		</table>
					
		<table class="table table-striped style="width:980px;">
			<thead>
				<tr>
					<th style="width:130px;">分红用户账号</th>
					<th style="width:140px;">充值时间</th>
					<th style="width:70px;text-align:right;">充值金额</th>
					<th style="width:120px;">充值游戏</th>
					<th style="width:30px;">返比</th>
					<th style="width:70px;text-align:right;">返利金额</th>
					<th style="width:90px;">返利归属</th>
					<th style="width:95px;">订单号</th>
					<th style="text-align:right;">操作</th>
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
	<td>%s</td>
	<td style="text-align:right;">%s</td>
	<td>%s</td>
	<td title="%s">%s</td>
	<td style="text-align:right;">%s</td>			
<tr>
EndOfRowTag;
		foreach ($PayList as $rowItem){
			$Amount=0.0;
			if(empty($rowItem["Amount"])==false){
				$Amount+=$rowItem["Amount"];
			}			
			if(empty($rowItem["TaxFee"])==false){
				$Amount+=$rowItem["TaxFee"];
			}
			$opHtml="";
			$opStatus=$rowItem["opStatus"];
			if($opStatus["showReturn"]==1){
				$opHtml="已作废";
			}else if($opStatus["canReturn"]==1 || $opStatus["canFrozen"]==1 || $opStatus["canUnFrozen"]==1){
				if($opStatus["canFrozen"]==1){
					$opHtml=sprintf("<a title=\"玩家正在申请退款，冻结推广员收益\" href=\"javascript:Frozen('%s')\"><i class=\"icon-ban-circle\"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a title=\"玩家已退款，收回推广员收益\" href=\"javascript:PayReturn('%s')\"><i class=\"icon-remove-sign\"></i></a>",$rowItem["CheckingID"],$rowItem["CheckingID"]);
				}else if ($opStatus["canUnFrozen"]==1){
					$opHtml=sprintf("<a title=\"玩家放弃退款，解冻推广员收益\" href=\"javascript:UnFrozen('%s')\"><font color=red><i class=\"icon-ban-circle\"></i></font></a>&nbsp;&nbsp;&nbsp;&nbsp;<a title=\"玩家已退款，收回推广员收益\" href=\"javascript:PayReturn('%s')\"><i class=\"icon-remove-sign\"></i></a>",$rowItem["CheckingID"],$rowItem["CheckingID"]);
				}
			}
			echo sprintf($rowsHtmlTag,
					$rowItem["PlayPhone"],
					$rowItem["TransactDt"],
					empty($rowItem["GameAmount"])?"0.00":number_format($rowItem["GameAmount"],2,".",","),
					$rowItem["AppName"],
					number_format(bcmul($rowItem["PromoterProrate"], "100"),0,"",",")."%",
					number_format($Amount,2,".",","),
					$rowItem["PhoneNo"],
					$rowItem["CheckingID"],"...".substr($rowItem["CheckingID"],-10),
					$opHtml
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
function Frozen(checkingId)
{
	bootbox.confirm('您确定要冻结这笔流水:'+checkingId+'吗？', 
			function(result) 
			{
				if(result){
					$("#PayOperate").val("frozen");
					$("#CheckingID").val(checkingId);
					$("#payListForm").submit();
				}
			});		
}
function UnFrozen(checkingId)
{
	bootbox.confirm('您确定要解冻结这笔流水:'+checkingId+'吗？', 
			function(result) 
			{
				if(result){
					$("#PayOperate").val("unfrozen");
					$("#CheckingID").val(checkingId);
					$("#payListForm").submit();
				}
			});		
}
function PayReturn(checkingId)
{
	bootbox.confirm('您确定要收回这笔流水:'+checkingId+'的推广员收益吗？', 
			function(result) 
			{
				if(result){
					$("#PayOperate").val("payreturn");
					$("#CheckingID").val(checkingId);
					$("#payListForm").submit();
				}
			});		
}
</script>