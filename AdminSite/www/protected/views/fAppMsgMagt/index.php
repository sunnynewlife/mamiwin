<div class="btn-toolbar">
	<a href="/fAppMsgMagt/channelAdd" class="btn btn-primary"><i class="icon-plus"></i>增加消息频道</a>
	<a href="/fAppMsgChannels/index" class="btn btn-primary"><i class="icon-resize-horizontal"></i>&nbsp;内部消息频道Mapping &nbsp;</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">分红消息频道、类型列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="50px">频道ID</th>
					<th width="100px">频道名称</th>
					<th width="110px">频道图标</th>
					<th width="60px">排列顺序</th>
					<th width="160px">频道描述</th>
					<th>消息类型列表</th>
					<th width="40px">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td><img style="width:90px" src="%s"></td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>
		<a href="/fAppMsgMagt/channelModify?channelId=%s" title="修改频道"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除频道"><i class="icon-remove" href="/fAppMsgMagt/channelDel?channelId=%s"></i></a>
	</td>
<tr>
EndOfRowTag;
$rowsHtmlTypeTag=<<<EndofRowsTypeTag
<tr>
	<td width="40px">
			<a href="/fAppMsgMagt/typeModify?typeId=%s&channelId=%s&name=%s" title="修改类型"><i class="icon-pencil"></i></a>&nbsp;
			<a data-toggle="modal" href="#myModal" title="删除类型"><i class="icon-remove" href="/fAppMsgMagt/typeDel?typeId=%s&channelId=%s"></i></a>
	</td>			
	<td width="30px">%s</td>				
	<td width="120px">%s</td>
</tr>
EndofRowsTypeTag;
			 	foreach ($Channels as $item){
					$typeHtmlFragment=sprintf("&nbsp;&nbsp;<a href=\"/fAppMsgMagt/typeAdd?channelId=%s\" title=\"增加消息类型\"><i class=\"icon-plus\"></i>&nbsp;消息消息类型</a>",$item["id"]);
					if(count($item["types"])>0){
						$typeHtmlFragment="<table class=\"table table-striped\" style=\"margin-top:0px;margin-bottom:0px;\">";
						$typeList=$item["types"]["messageTypeList"];
						foreach ($typeList as $typeItem){
							$typeHtmlFragment.=sprintf($rowsHtmlTypeTag,
									$typeItem["id"],
									$item["id"],
									urlencode($typeItem["name"]),
									$typeItem["id"],
									$item["id"],
									$typeItem["id"],
									$typeItem["name"]
									);
						}
						$typeHtmlFragment.=sprintf("<tr><td colspan=3><a href=\"/fAppMsgMagt/typeAdd?channelId=%s\" title=\"增加消息类型\"><i class=\"icon-plus\"></i>&nbsp;增加消息类型</a></td></tr>",$item["id"]);
						$typeHtmlFragment.="</table>";
					}
					$rowHtml=sprintf($rowsHtmlTag,
						$item["id"],
						$item["name"],
						$item["icon"],
						$item["SortOrder"],
						$item["DescContent"],
						$typeHtmlFragment,
						$item["id"],$item["id"]);
					echo $rowHtml; 
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
