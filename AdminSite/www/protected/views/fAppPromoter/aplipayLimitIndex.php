<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">推广员提现额度数据查询</a>
	<div id="search" class="in collapse">
		<form class="form_search"  method="POST" action="/fAppPromoter/alipayLimitIndex" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp" style="width:660px;">
					<tr>
						<td style="width:120px;">推广员账号：</td>
						<td><input type='text' maxlength='20'  name="PhoneNo" /></td>
						<td><button type="submit" class="btn btn-primary">查  询</button></td>
						<td>
				<?php
					if(count($Promoter)>0){ 
				?>
					<button type="button" class="btn btn-primary" onclick="javascript:exportCSV();">导出到CSV</button>
				<?php
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
		<table class="table table-striped" style="width:660px;">
			<thead>
				<tr>
					<th style="width:160px;">推广员账号</th>
					<th style="width:100px;text-align:right;">当前账户余额</th>
					<th style="width:100px;text-align:right;">当前提现额度</th>
					<th style="width:100px;text-align:right;">提现周期</th>
					<th style="width:70px;text-align:center;">操作</th>
				</tr>
			</thead>
			<tbody>
<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td style="text-align:right;">%s</td>
	<td style="text-align:right;">%s</td>
	<td style="text-align:right;">%s</td>
	<td style="text-align:center;">
		<a href="/fAppPromoter/adjustAlipayLimit?PromoterId=%s" title="调整提现额度"><i class="icon-pencil"></i></a>&nbsp;
	</td>
<tr>
EndOfRowTag;
			foreach ($Promoter as $item){
				$rowHtml=sprintf($rowsHtmlTag,
						$item["PhoneNo"],
						empty($item["Amount"])?"0":number_format($item["Amount"],2,".",","),
						empty($item["MaxAliPayAmount"])?"":number_format($item["MaxAliPayAmount"],2,".",","),
						$item["MinPayReturn"],
						$item["PromoterId"]
					);
				echo $rowHtml;
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