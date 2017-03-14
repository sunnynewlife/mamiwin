<div class="btn-toolbar">
	<a href="/evnDefine/add" class="btn btn-primary"><i class="icon-plus"></i>游戏接入入口</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" class="collapse">
<form class="form_search"  action="/evnDefine/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<input type="text" name="event_code" value="" placeholder="输入活动接入ID" > 
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">游戏接入列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>活动接入ID</th>
					<th>活动状态</th>
					<th>用户身份</th>
					<th>身份获取配置节点名称</th>
					<th>活动项列表</th>
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
		<a href="/evnDefine/modify?event_code=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/evnDefine/del?event_code=%s"></i></a>
	</td>
<tr>
EndOfRowTag;

			 	foreach ($evn_define_list as $evnDefine){

					$rowHtml=sprintf($rowsHtmlTag,$evnDefine["event_code"],
						($evnDefine["event_status"]==1?"有效":"无效"),
						($evnDefine["auth_type"]==1?"G家":($evnDefine["auth_type"]==2?"透传":($evnDefine["auth_type"]==3?"游戏接口验证":"debug游戏接口验证"))),
						$evnDefine["auth_type_cfg_name"],
						$evnDefine["aid_list"],$evnDefine["event_code"],$evnDefine["event_code"]);
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
