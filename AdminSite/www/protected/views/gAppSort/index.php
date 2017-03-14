
<div id="search">
<form class="form_search"  action="/gAppSort/index" method="GET" style="margin-bottom:0px">
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
	<?php $os_type=Yii::app()->request->getParam('os_type',1);?>
	<a href="#page-stats" class="block-heading" data-toggle="collapse"><?php echo Yii::app()->request->getParam('os_type',1)==1?"Android":"iOS";?>平台游戏推荐排序</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>游戏图片</th>
					<th>游戏名称</th>
					<th>序号</th>
					<th>状态</th>
					<th width="80px">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td><img src="%s"/ width="100"></td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>
		<a href="/gAppSort/modify?id=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/gAppSort/del?id=%s"></i></a>
	</td>
<tr>
EndOfRowTag;
				$os_type=Yii::app()->request->getParam('os_type',1);
				$sql="select gameid,game_name from t_game_config where os_type=".$os_type.";";
				$game_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
				$game_name_list=array();
				foreach ($game_list as $gameItem){
					$game_name_list[$gameItem['gameid']]=$gameItem['game_name'];
				}
				$state=array('0'=>'下架','1'=>'灰度','2'=>'上架');
			 	foreach ($t_game_config as $item){
			 		if(isset($game_name_list[$item["gameid"]])){
			 			$rowHtml=sprintf($rowsHtmlTag,
			 					$item["game_img"],
			 					$game_name_list[$item["gameid"]],
			 					(int)$item["recommend_no"],
			 					$state[$item["state"]],
			 					$item["id"],$item["id"]);
			 			echo $rowHtml;
			 		}
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
