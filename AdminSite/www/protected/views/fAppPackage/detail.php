<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">游戏安装包数据</a>
	<div id="search" class="in collapse">
		<form class="form_search"  method="POST" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">游戏APPID：</td>
						<td><input type='text' maxlength='20'  name="AppId"  value="<?php echo $AppId; ?>" readonly/></td>
						<td style="width:120px;">游戏名称</td>
						<td><input type='text' maxlength='20' name="AppName" value="<?php echo $AppName;?>" readonly /></td>
					</tr>
					<tr>
						<td>累计申请人数：</td>
						<td><input type='text' maxlength='20' name="AppName" value="<?php echo $PromoterCount;?>" readonly /></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>推广员账号：</td>
						<td><input type='text' maxlength='20' name="Phone" value="<?php echo $Phone; ?>" /></td>
						<td><button type="submit" class="btn btn-primary">查  询</button></td>
						<td>
<?php
						if(count($DetailList)>0){
							echo "<button type=\"button\" class=\"btn btn-primary\" onclick=\"exportCSV();\">导出</button>";
						} 
?>						
						</td>
					</tr>										
				</table>			
				<input type="hidden" name="search" value="1">
				<input type="hidden" name="export" value="0" id="export">
			</div>
			<div style="clear:both;"></div>
		</form>
	</div>
	
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:100px;">推广员账号</th>
					<th style="width:134px;">申请时间</th>
					<th style="width:100px;text-align:right;">累计登录人数</th>
					<th style="width:80px;">游戏版本</th>
					<th style="width:100px;text-align:right;">累计下载次数</th>
					<th style="width:100px;text-align:right;">累计充值人数</th>
					<th style="width:100px;text-align:right;">累计充值金额</th>
					<th style="width:105px;text-align:right;">累计获得返利</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td style="text-align:right;">%s</td>
	<td colspan=5>%s</td>
<tr>
EndOfRowTag;
$tableHtmlTag=<<<EndOfTableHtmlTag
<tr>
    <td style="width:80px;padding:0px;">%s</td>
	<td style="width:100px;text-align:right;padding:0px;">%s</td>
	<td style="width:100px;text-align:right;padding:0px;">%s</td>
	<td style="width:100px;text-align:right;padding:0px;">%s</td>
	<td style="width:105px;text-align:right;padding:0px;">%s</td>
</tr>
EndOfTableHtmlTag;

			foreach ($DetailList as $phone => $row){
				$rowsHtml="";
				foreach ($row["VersionList"] as $itemRow){
					$rowsHtml.=sprintf($tableHtmlTag,$itemRow["VersionName"],number_format($itemRow["DowloadCount"],0,".",","),
								number_format($itemRow["PayCount"],0,".",","),
								number_format($itemRow["GameAmount"],2,".",","),
								number_format($itemRow["PromoterAmount"],2,".",","));
				}
				$tableHtmlFrag=sprintf("<table style='width:550px;margin:0;padding:0;'class='tableApp'>%s</table>",$rowsHtml);
				echo sprintf($rowsHtmlTag,$phone,$row["CreateDt"],number_format($row["LoginCount"],0,".",","),$tableHtmlFrag);
			}
			?>
			</tbody>
		</table>
		<?php  echo $page;?>
	</div>
</div>
<script>
function exportCSV()
{
	$("#export").val(1);
	$(".form_search").submit();
	$("#export").val(0);
}
</script>