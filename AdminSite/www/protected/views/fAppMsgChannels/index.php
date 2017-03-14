<div class="btn-toolbar">
	<a href="/fAppMsgChannels/add" class="btn btn-primary"><i class="icon-plus"></i>增加消息频道Mapping</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">内部消息频道Mapping</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>主键ID</th>
					<th>消息Key</th>
					<th>消息描述</th>
					<th>消息渠道</th>
					<th>消息类型</th>
					<th width="80px">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td width="60px">%s</td>
	<td width="180px">%s</td>
	<td width="180px">%s</td>
	<td>%s</td>
	<td>%s</td>
	<td width="40px;">
		<a href="/fAppMsgChannels/modify?RecId=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/fAppMsgChannels/del?RecId=%s"></i></a>
	</td>
<tr>
EndOfRowTag;
				foreach ($MsgChannels as $item){
					echo sprintf($rowsHtmlTag,
						$item["RecId"],
						$item["typeKey"],
						$item["typeName"],
						$item["channel_name"],
						$item["type_name"],
						$item["RecId"],$item["RecId"]
					);
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
