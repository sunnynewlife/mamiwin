<div class="btn-toolbar">
	<a href="/fApp/installPackageAdd" class="btn btn-primary"><i class="icon-plus"></i>增加游戏安装包名定义</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">游戏-游戏安装包名列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:120px">游戏ID</th>
					<th style="width:160px">游戏名称</th>
					<th>安装包名</th>
					<th width="80px">操作</th>
				</tr>
			</thead>
			<tbody>
<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>
		<a href="/fApp/installPackageModify?appId=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/fApp/installPackageDel?appId=%s"></i></a>
	</td>
<tr>
EndOfRowTag;
		foreach ($InstallPacakge as $appId => $packageName){
			echo sprintf($rowsHtmlTag,
					$appId,$AppNames[$appId],$packageName,$appId,$appId);
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
