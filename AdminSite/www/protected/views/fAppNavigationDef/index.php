<div class="btn-toolbar">
	<a href="/fAppNavigationDef/add" class="btn btn-primary"><i class="icon-plus"></i>新增类别</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">游戏分类设置</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:40px;">位置</th>
					<th style="width:180px;">图片</th>
					<th style="width:100px;">名称</th>
					<th>包含游戏</th>
					<th width="60px">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td><img style="width:180px;" src="%s"></td>
	<td>%s</td>
	<td>%s</td>			
	<td>
		<a href="/fAppNavigationDef/modify?RecId=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/fAppNavigationDef/del?RecId=%s"></i></a>
	</td>
<tr>
EndOfRowTag;
			foreach ($AppNavigationDef as $row){
				echo sprintf($rowsHtmlTag,
						$row["PositionIndex"],
						$row["DownloadUrl"],
						$row["CategoryName"],
						$row["GameNames"],
						$row["RecId"],
						$row["RecId"]
					);
			}
			?>
			</tbody>
		</table>
		<?php echo $page;?>
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
