<div class="btn-toolbar">
	<a href="/gAppRoomAttributes/add" class="btn btn-primary"><i class="icon-plus"></i>增加聊天室属性</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" class="collapse">
<form class="form_search"  action="/gAppRoomAttributes/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<input type="text" name="chat_room_name" value="" placeholder="输入聊天室名称" > 
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">聊天室属性列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>游戏ID</th>
					<th>聊天室ID</th>
					<th>聊天室名字</th>
					<th>大图片</th>
					<th>小图片</th>
					<th>排序</th>
					<th>上架状态</th>
					<th>推荐</th>
					<th>开放状态</th>
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
	<td>%s</td>
	<td><img style="width:180px;" src=%s></td>
	<td><img style="width:90px" src=%s></td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>
		<a href="/gAppRoomAttributes/modify?id=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/gAppRoomAttributes/del?id=%s"></i></a>
	</td>
<tr>
EndOfRowTag;

			 	foreach ($t_chat_room_attributes as $item){

					$rowHtml=sprintf($rowsHtmlTag,$item["id"],
						$item["game_id"],
						$item["chat_room_id"],
						$item["chat_room_name"],
						$item["cover_url"],
						$item["cover_url_small"],
						$item["sort_id"],
						$item["status"]?"上架":"下架",
						$item["is_recommend"]?"推荐":"不推荐",
						$item["is_open"]?"开放":"关闭",
						$item["id"],$item["id"]);
					echo $rowHtml; 
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
