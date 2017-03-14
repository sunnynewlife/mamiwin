<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">游戏安装包数据</a>
	<div id="search" class="in collapse">
		<form class="form_search"  method="POST" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">游戏APPID：</td>
						<td><input type='text' maxlength='20'  name="AppId" /></td>
						<td style="width:120px;">游戏名称</td>
						<td><input type='text' maxlength='20' name="AppName" /></td>
						<td><button type="submit" class="btn btn-primary">查  询</button></td>
					</tr>
				</table>			
				<input type="hidden" name="search" value="1" >
			</div>
			<div style="clear:both;"></div>
		</form>
	</div>
	
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>游戏APPID</th>
					<th>游戏名称</th>
					<th style="text-align:right">累计申请人数</th>
					<th style="text-align:right">累计下载次数</th>
					<th style="text-align:right">累计登录人数</th>
					<th style="text-align:right">累计充值人数</th>
					<th style="text-align:right">累计充值金额</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td style="text-align:right">%s</td>
	<td style="text-align:right">%s</td>
	<td style="text-align:right">%s</td>
	<td style="text-align:right">%s</td>
	<td style="text-align:right">%s</td>			
	<td>
		<a href="/fAppPackage/detail?AppId=%s" title="查看明细数据"><i class="icon-list-alt"></i></a>&nbsp;			
	</td>
<tr>
EndOfRowTag;
			 	foreach ($AppProfit as $item){
					$rowHtml=sprintf($rowsHtmlTag,
						$item["AppId"],
						$item["AppName"],
						empty($item["ProfitApply"])?"":number_format($item["ProfitApply"],0,".",","),
						empty($item["ProfitDownload"])?"":number_format($item["ProfitDownload"],0,".",","),
						empty($item["ProfitLoginCount"])?"":number_format($item["ProfitLoginCount"],0,".",","),
						empty($item["ProfitPayCount"])?"":number_format($item["ProfitPayCount"],0,".",","),
						empty($item["ProfitDeposit"])?"":number_format($item["ProfitDeposit"],2,".",","),
						$item["AppId"]);
					echo $rowHtml; 
				}
			?>
			</tbody>
		</table>
	</div>
</div>