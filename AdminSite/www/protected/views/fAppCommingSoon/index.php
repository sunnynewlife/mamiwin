<div class="btn-toolbar">
	<a href="/fAppCommingSoon/add" class="btn btn-primary"><i class="icon-plus"></i>新建即将上线</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" class="collapse">
<form class="form_search"  action="/fAppCommingSoon/index" method="POST" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<input type="text" name="Name" value="" placeholder="输入游戏名称" > 
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">即将上线游戏列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:180px;">图片Logo</th>
					<th style="width:60px;">游戏名称</th>
					<th style="width:280px;">游戏概述</th>
					<th style="width:60px;text-align:right;">排序位置</th>
					<th width="40px">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td><img style="width:180px;" src="%s"></td>
	<td>%s</td>
	<td>%s</td>
	<td style="text-align:right;">%s</td>
	<td>
		<a href="/fAppCommingSoon/modify?RecId=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/fAppCommingSoon/del?RecId=%s"></i></a>
	</td>
<tr>
EndOfRowTag;
			 	foreach ($AppComingSoon as $item){
					$rowHtml=sprintf($rowsHtmlTag,
						$item["DownloadUrl"],
						$item["Name"],
						$item["Introduction"],
						$item["SortIndex"],
						$item["RecId"],$item["RecId"]);
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
