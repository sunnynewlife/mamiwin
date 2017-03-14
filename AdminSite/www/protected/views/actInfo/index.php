<div class="btn-toolbar">
	<a href="/actInfo/add" class="btn btn-primary"><i class="icon-plus"></i>活动信息定义</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" class="collapse">
<form class="form_search"  action="/actInfo/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<input type="text" name="name" value="" placeholder="请输入活动名" style="width:150px;">
	</div>	
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<input type="text" name="game_code" value="" placeholder="请输入活动游戏代码" style="width:150px;">
	</div>		
	<div style="float:left;margin-right:5px">
		<label>选择活动类型</label>
		<select name="type" class="input-xlarge" style="width:150px;">
			<option value="" selected>全部</option>
			<option value="1">领取类</option>
			<option value="2">抽奖类</option>
			<option value="3">签到类</option>
		</select>
	</div>
	<div style="float:left;margin-right:5px">
		<label>选择活动状态</label>
		<select name="status" class="input-xlarge" style="width:150px;">
			<option value="" selected>全部</option>
			<option value="1">有效</option>
			<option value="2">无效</option>
		</select>
	</div>	
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<input type="hidden" name="search" value="1" >
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
					<th>活动ID</th>
					<th>活动名</th>
					<th>游戏代码</th>
					<th>类型</th>
					<th>限制类型</th>
					<th>限制总量</th>
					<th>用户限制类型</th>
					<th>用户限制总量</th>
					<th>限制在游戏区</th>
					<th>活动时间类型</th>
					<th>活动时间定义</th>
					<th>活动状态</th>
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
		<a href="/actInfo/modify?aid=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="/actInfo/del?aid=%s" title="删除"><i class="icon-remove"></i></a>
	</td>
<tr>
EndOfRowTag;

			 	foreach ($act_info_list as $actInfo){
					$periodType="";
					switch ($actInfo["period_type"]){
						case 1:
							$periodType="一次性";
							break;
						case 2:
							$periodType="周期性-每小时";
							break;
						case 3:
							$periodType="周期性-每天";
							break;
						case 4:
							$periodType="时间段";
							break;
						default:
							$periodType="";
							break;
					}
					$rowHtml=sprintf($rowsHtmlTag,
						$actInfo["aid"],
						$actInfo["name"],
						$actInfo["game_code"],	
						($actInfo["type"]==1?"领取类":($actInfo["type"]==2?"抽奖累":"签到类")),
						($actInfo["limit_type"]==1?"无":"有"),
						$actInfo["limit_qty"],
						($actInfo["user_limit_type"]==1?"无":"有"),
						$actInfo["user_limit_qty"],
						($actInfo["area_range"]==1?"无":"有"),
						$periodType,
						$actInfo["period_range"],
						($actInfo["status"]==1?"有效":"无效"),
						$actInfo["aid"],$actInfo["aid"]);
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
