<div class="btn-toolbar">
	<a href="/actPackageInfo/add" class="btn btn-primary"><i class="icon-plus"></i>礼包定义</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" class="collapse">
<form class="form_search"  action="/actPackageInfo/index" method="GET" style="margin-bottom:0px">
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
					<th>礼包ID</th>
					<th>礼包名称</th>
					<th>礼包类型</th>
					<th>限制类型</th>
					
					<th>限制总量</th>
					<th>用户限制类型</th>
					<th>用户限制总量</th>
					<th>所属活动</th>
					
					<th>领取时间类型</th>
					<th>领取时间</th>
					<th>限制在游戏区</th>
					<th>礼包状态</th>
					
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
			
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
			
	<td>
		<a href="/actPackageInfo/modify?pid=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/actPackageInfo/del?pid=%s"></i></a>
	</td>
<tr>
EndOfRowTag;

			 	foreach ($package_info as $packDefine){
					$periodType="";
					switch ($packDefine["period_type"]){
						case 1:
							$periodType="一次性";
							break;
						case 2:
							$periodType="周期性-每小时";
							break;
						case 3:
							$periodType="周期性-每天";
							break;
						default:
							$periodType="";
							break;
					}
					$rowHtml=sprintf($rowsHtmlTag,
						$packDefine["pid"],
						$packDefine["name"],
						$packDefine["type"],
 					    ($packDefine["limit_type"]==1?"无":"有"),
						
						$packDefine["limit_qty"],
						($packDefine["user_limit_type"]==1?"无":"有"),
						$packDefine["user_limit_qty"],
						$packDefine["aid"],

						$periodType,
						$packDefine["period_range"],
						($packDefine["area_range"]==1?"无":"有"),
						($packDefine["status"]==1?"有效":"无效"),
						$packDefine["pid"],$packDefine["pid"]);
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
