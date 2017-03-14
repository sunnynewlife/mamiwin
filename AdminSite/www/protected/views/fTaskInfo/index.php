<div class="btn-toolbar">
	<a href="/fTaskInfo/add" class="btn btn-primary"><i class="icon-plus"></i>增加任务配置</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" class="collapse">
<form class="form_search"  action="/fTaskInfo/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<input type="text" name="config_key" value="" placeholder="输入配置项名称" > 
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse"><?php echo Yii::app()->request->getParam('os_type',1)==1?"Android":"iOS";?>平台特权活动轮播广告管理</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:140px;">任务名称</th>
					<th>活动编号</th>
					<th>条件</th>
					<th>状态</th>
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
	<td>
		<a href="/fTaskInfo/modify?id=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/fTaskInfo/del?id=%s"></i></a>
	</td>
<tr>
EndOfRowTag;
				$status=array('0'=>'正常','1'=>'删除','2'=>'暂停');
			 	foreach ($tbl_task_info as $item){
			 			$rowHtml=sprintf($rowsHtmlTag,
			 					$item["title"],
			 					$item["event_id"],
			 					$item["task_condition"],
			 					$status[$item["status"]],
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
