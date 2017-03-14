<div class="btn-toolbar">
	<a href="/regStyleDef/add" class="btn btn-primary"><i class="icon-plus"></i>增加样式定义</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" class="collapse">
<form class="form_search"  action="/regAppDef/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<input type="text" name="css_id" value="" placeholder="输入样式css_id" > 
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">注册样式定义列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>IDX</th>
					<th>样式ID</th>
					<th>样式描述</th>
					<th>启用状态</th>
					<th>样式内容</th>
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
	<td>%s</td>
	<td>
		<a href="/regStyleDef/modify?idx=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/regStyleDef/del?idx=%s"></i></a>
	</td>
<tr>
EndOfRowTag;

				$shownStyleContent_Len=80;
			 	foreach ($reg_style_def as $item){
					$styleContent=$item["content"];
					if(strlen($styleContent)>$shownStyleContent_Len){
						$styleContent=substr($styleContent, 0,$shownStyleContent_Len)."...";
					}
					$rowHtml=sprintf($rowsHtmlTag,$item["idx"],
						$item["css_id"],
						$item["name"],
						($item["status"]==1?"有效":"无效"),
						$styleContent,
						$item["idx"],$item["idx"]);
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
