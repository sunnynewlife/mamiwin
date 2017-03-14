<div class="btn-toolbar">
	<a href="/qAppLottery/index" class="btn btn-primary"><i class="icon-filter"></i>中奖查询</a>
	<a href="/qAppLottery/query" class="btn btn-primary"><i class="icon-filter"></i>剩余奖品查询</a>
	<a href="/qAppLottery/queryNewFocus" class="btn btn-primary"><i class="icon-filter"></i>新用户关注查询</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width: 150px;">奖品名称</th>
					<th style="width: 150px;">奖品总数</th>
					<th style="width: 150px;">已领取数</th>
					<th style="width: 150px;">剩余数</th>
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
$total_Quantity = 0 ;
$total_DrawCount = 0 ;
$total_LeftCount = 0 ;
			foreach ($lottery_list as $item){
				$Award  = $item["Award"] ; 
				$Quantity  = $item["Quantity"] ; 
				$DrawCount  = $item["DrawCount"] ; 
				$LeftCount  = $Quantity - $DrawCount;
				$total_Quantity += $Quantity;
				$total_DrawCount += $DrawCount;
				$total_LeftCount += $LeftCount;
				echo sprintf($rowsHtmlTag,
					$Award,$Quantity,$DrawCount,$LeftCount);
			}
			?>
			<tr>
					<td>合计</td>
					<td><?php echo($total_Quantity);?></td>
					<td><?php echo($total_DrawCount);?></td>
					<td><?php echo($total_LeftCount);?></td>
					
			</tr>			
			</tbody>
		</table>
	</div>
</div>
