<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">微信用户:<font color=red><?php echo $PhoneNo;?></font>&nbsp;领取的礼包列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:200px;">游戏名称</th>
					<th>礼包名称</th>
					<th style="width:135px;">领取时间</th>
					<th style="width:200px;">礼包码</th>
				</tr>
			</thead>
			<tbody>
<?php
$rowsHtmlTag = <<<EndOfRowTag
			<tr>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
			</tr>
EndOfRowTag;
			foreach ($gift_list as $item){
				echo sprintf($rowsHtmlTag,$item["AppName"],$item["Name"],$item["UpdateDt"],$item["Code"]);
			}
			?>
			</tbody>
		</table>
	</div>
</div>