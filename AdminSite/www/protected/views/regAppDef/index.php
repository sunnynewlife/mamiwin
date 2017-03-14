<div class="btn-toolbar">
	<a href="/regAppDef/add" class="btn btn-primary"><i class="icon-plus"></i>增加应用接入定义</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" class="collapse">
<form class="form_search"  action="/regAppDef/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<input type="text" name="app_id" value="" placeholder="输入app_id" > 
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">注册应用接入定义列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>IDX</th>
					<th>AppID</th>
					<th>首页账号类型</th>
					<th>手机账号</th>
					<th>邮件账号</th>
					<th>个性账号</th>
					<th>需要实名</th>
					<th>参数可重设实名</th>
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
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>			
	<td>
		<a href="/regAppDef/modify?idx=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/regAppDef/del?idx=%s"></i></a>
	</td>
<tr>
EndOfRowTag;

			 	foreach ($reg_app_def as $item){

					$rowHtml=sprintf($rowsHtmlTag,$item["idx"],
						$item["app_id"],
						($item["home_type"]==1?"手机账号":($item["home_type"]==2?"邮件账号":($item["home_type"]==3?"个性账号":$item["home_type"]))),
						($item["account_type_phone"]==1?"有":"无"),
						($item["account_type_email"]==1?"有":"无"),
						($item["account_type_custom"]==1?"有":"无"),
						($item["display_real_name"]==1?"需要":"不需要"),
						($item["can_override_display_real_name"]==1?"可以":"不可以"),
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
