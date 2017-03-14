<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">微信用户:<font color=red><?php echo $PhoneNo;?></font>&nbsp;参加的活动列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:200px;">游戏名称</th>
					<th>活动名称</th>
					<th style="width:135px;">参加时间</th>
					<th style="width:160px;">活动时间段</th>
					<th style="width:100px;">活动类型</th>
					<th style="width:40px;" >操作</th>
				</tr>
			</thead>
			<tbody>
<?php
$rowsHtmlTag = <<<EndOfRowTag
			<tr>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s-%s</td>
				<td>%s</td>
				<td><a href="/qAppEvn/query?idx=%s" title="查看"><i class="icon-list-alt"></i></a></td>
			</tr>
EndOfRowTag;
			foreach ($evn_list as $item){
				echo sprintf($rowsHtmlTag,$item["AppName"],$item["EvnName"],$item["CreateDt"],
				str_replace("-", ".", substr($item["EvnStart"], 0,10)),
				str_replace("-", ".", substr($item["EvnEnd"], 0,10)),
				$item["EvnType"]=="1"?"非阶梯式返利":"阶梯式返利",
				$item["EvnIdx"]);
			}
			?>
			</tbody>
		</table>
	</div>
</div>