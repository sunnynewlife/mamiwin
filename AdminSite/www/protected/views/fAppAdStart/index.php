<div class="btn-toolbar">
	<a href="/fAppAdStart/add" class="btn btn-primary"><i class="icon-plus"></i>增加启动图片项</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">广告配置-游戏启动宣传图</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>帧数</th>
					<th>图片</th>
					<th width="80px">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td width="30px">%s</td>
	<td width="280px"><img style="width:180px;" src="%s"></td>
	<td>
		<a href="/fAppAdStart/modify?frameId=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/fAppAdStart/del?frameId=%s"></i></a>
	</td>
<tr>
EndOfRowTag;
			$frameId=1;
			foreach ($AdList as $item){
				echo sprintf($rowsHtmlTag,
					$frameId,
					$item["pic"],
					$frameId,$frameId	
				);
				$frameId++;
			}
			?>
			</tbody>
		</table>
	</div>
</div>

<script>
	$('.icon-remove').click(
		function() 
		{
			var href=$(this).attr('href');
			bootbox.confirm('确定要这样做吗？', 
					function(result) 
					{
						if(result){
							location.replace(href);
						}
					});		
		});
</script>
