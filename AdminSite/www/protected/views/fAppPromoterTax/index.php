<div class="btn-toolbar">
	<a href="/fAppPromoterTax/calc" class="btn btn-primary"><i class="icon-calendar"></i>&nbsp;计算月度返税</a>
</div>

<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">推广员月底返税计算</a>
	<div id="search" class="in collapse">
		<form class="form_search"  method="POST" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td>推广员账号:</td>
						<td><input type='text' maxlength='20'  name="PhoneNo"  value="<?php echo $PhoneNo;?>" /></td>
						<td>记税周期:</td>
						<td><input type='text' maxlength='20'  name="Period" value="<?php echo $Period;?>" /></td>
					</tr>
					<tr>
						<td colspan=4 style="padding-left:300px;">
							<button type="submit" class="btn btn-primary">查  询</button>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
						if(count($TaxList)>0){
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
					<th style="width:100px;">推广员账号</th>
					<th style="width:80px;">记税周期</th>
					<th style="width:110px;text-align:right;">不记税收入</th>
					<th style="width:110px;text-align:right;">记税收入</th>
					<th style="width:100px;text-align:right;">应执行税率</th>
					<th style="width:100px;text-align:right;">应纳税总额</th>
					<th style="width:100px;text-align:right;">预交税总额</th>
					<th style="text-align:right;">应返还金额</th>
				</tr>
			</thead>
			<tbody>
<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td style="text-align:right;">%s</td>
	<td style="text-align:right;">%s</td>
	<td style="text-align:right;">%s</td>
	<td style="text-align:right;">%s</td>
	<td style="text-align:right;">%s</td>			
	<td style="text-align:right;">%s</td>			
<tr>
EndOfRowTag;

		foreach ($TaxList as $rowItem){
			echo sprintf($rowsHtmlTag,
					$rowItem["PhoneNo"],
					$rowItem["TaxPeriod"],
					$rowItem["AmountNoTax"],
					number_format($rowItem["AmountTax"],2,".",","),
					$rowItem["TaxPercent"],
					number_format($rowItem["TaxFee"],2,".",","),
					number_format($rowItem["PreTaxFee"],2,".",","),
					number_format($rowItem["Refund"],2,".",",")
				);
		}		
?>				
			</tbody>
		</table>
		<?php echo $page;?>
	</div>
</div>
<script type="text/javascript">
function exportCSV()
{
	$("#export").val(1);
	$(".form_search").submit();
	$("#export").val(0);
}
</script>