<div class="btn-toolbar">
	<a href="/actRules/add" class="btn btn-primary"><i class="icon-plus"></i>规则定义</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" class="collapse">
<form class="form_search"  action="/actRules/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<input type="text" name="name" value="" placeholder="输入礼包名称" > 
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">礼包定义列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>规则ID</th>
					<th>规则名称</th>
					<th>规则类型</th>
					<th>规则接口数据查询定义</th>
					<th>规则数据</th>
					<th>不满足该条件,用户提示语</th>
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
	<td>
		<a href="/actRules/modify?rid=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/actRules/del?rid=%s"></i></a>
	</td>
<tr>
EndOfRowTag;

			 	foreach ($act_rules as $act_rule){
					$act_rule_type="";
					switch ($act_rule["type"]){
						case 1:
							$act_rule_type="白名单";
							break;
						case 2:
							$act_rule_type="游戏方接口检查";
							break;
						case 3:
							$act_rule_type="分享推荐类检查";
							break;
						case 4:
							$act_rule_type="签到类";
							break;
						case 5:
							$act_rule_type="自定义规则";
							break;
					}
					$rowHtml=sprintf($rowsHtmlTag,
						$act_rule["rid"],
						$act_rule["name"],
						$act_rule_type,
						$act_rule["rule_node_name"],
						$act_rule["data"],$act_rule["error_msg"],
						$act_rule["rid"],$act_rule["rid"]);
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
