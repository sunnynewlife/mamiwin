
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">素材配置项列表</a>
	<div id="search" class="in collapse">
		
	</div>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="60px;">ID</th>
					<th width="40px;">配置项Key</th>
					<th width="60px;">配置项Value</th>
					<th width="200px;">配置项说明</th>
					<th width="60px">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>	
	<td>%s</td>	
	<td>
		<a href="/taskConfig/modify?IDX=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
	</td>
<tr>
EndOfRowTag;
			$titleLen=20;
			foreach ($Task_Config as $item){
				$Confg_Key=$item["Confg_Key"];
				$Config_Value=$item["Config_Value"];
				$Config_Remark=$item["Config_Remark"];
				
				
				echo sprintf($rowsHtmlTag,
					$item["IDX"],
					$Confg_Key,
					$Config_Value,
					$Config_Remark,
					$item["IDX"]
				);
			}
			?>
			</tbody>
		</table>
		<?php echo $page;?>
	</div>
</div>
<script type="text/javascript">

	$('.icon-remove').click(
		function() 
		{
			var href=$(this).attr('href');
			bootbox.confirm('确定要删除这个素材吗？', 
					function(result) 
					{
						if(result){
							location.replace(href);
						}
					});		
		});
</script>
