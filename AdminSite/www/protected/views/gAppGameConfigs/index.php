<div class="btn-toolbar">
	<a href="/gAppGameConfigs/add?os_type=<?php echo Yii::app()->request->getParam('os_type',1);?>" class="btn btn-primary"><i class="icon-plus"></i>增加游戏配置</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" >
<form class="form_search"  action="/gAppGameConfigs/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<input type="text" name="game_name" value="" placeholder="输入游戏名称" > 
		<input type="hidden" name="os_type" value="<?php echo Yii::app()->request->getParam('os_type',1);?>"> 
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">

	<a href="#page-stats" class="block-heading" data-toggle="collapse"><?php echo Yii::app()->request->getParam('os_type',1)==1?"Android":"iOS";?>平台游戏管理</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:140px;">游戏ID</th>
					<th>游戏名称</th>
					<th>游戏LOGO</th>
					<th>游戏平台类型</th>
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
	<td><img src="%s"/ width="100"></td>
	<td>%s</td>
	<td>%s</td>
	<td>
		<a href="/gAppGameConfigs/modify?id=%s&gameid=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<!--<a data-toggle="modal" href="#" title="请勿删除">勿删</a>-->
	</td>
<tr>
EndOfRowTag;
				$state=array('0'=>'下架','1'=>'灰度','2'=>'上架');
			 	foreach ($t_game_config as $item){
					$rowHtml=sprintf($rowsHtmlTag,
						$item["gameid"],
						$item["game_name"],
						$item["game_logo"],
						$item["os_type"]==1?"Android":"iOS",
						$state[$item["state"]],
						$item["id"],$item["gameid"],$item["id"]);
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
