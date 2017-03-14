<div class="btn-toolbar">
	<a href="/fAppAdGameList/add" class="btn btn-primary"><i class="icon-plus"></i>创建走马灯</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">广告配置-游戏列表上走马灯</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>帧数</th>
					<th>图片</th>
					<th>跳转链接</th>
					<th>需要身份</th>
					<th>IP白名单</th>
					<th width="50px">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td width="30px">%s</td>
	<td width="180px"><img style="width:180px;" src="%s"></td>
	<td width="240px">%s</td>
	<td width="60px">%s</td>
	<td width="130px">%s</td>
	<td>
		<a href="/fAppAdGameList/modify?frameId=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/fAppAdGameList/del?frameId=%s"></i></a>
		%s
	</td>
<tr>
EndOfRowTag;
			$frameId=1;
			$moveHtml="";
			foreach ($AdList as $item){
				if($frameId==1){
					$moveHtml="&nbsp;<a href=\"/fAppAdGameList/move?direct=down&frameId=1\" title=\"下移\"><i class=\"icon-arrow-down\"></i></a>";
				}else if($frameId==count($AdList)){
					$moveHtml="&nbsp;<a href=\"/fAppAdGameList/move?direct=up&frameId=".$frameId."\" title=\"上移\"><i class=\"icon-arrow-up\"></i></a>";
				}else{
					$moveHtml="&nbsp;<a href=\"/fAppAdGameList/move?direct=down&frameId=".$frameId."\" title=\"下移\"><i class=\"icon-arrow-down\"></i></a>";
					$moveHtml.="&nbsp;<a href=\"/fAppAdGameList/move?direct=up&frameId=".$frameId."\" title=\"上移\"><i class=\"icon-arrow-up\"></i></a>";
				}
				echo sprintf($rowsHtmlTag,
					$frameId,
					$item["pic"],
					$item["url"],
					$item["needUserId"]==1?"需要":"不需要",
					isset($item["ip"])?$item["ip"]:"",
					$frameId,$frameId,
					$moveHtml	
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
