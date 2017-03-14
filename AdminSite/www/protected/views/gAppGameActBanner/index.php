<div class="btn-toolbar">
	<a href="/gAppGameActBanner/add?os_type=<?php echo Yii::app()->request->getParam('os_type',1);?>" class="btn btn-primary"><i class="icon-plus"></i>增加banner配置</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" >
<form class="form_search"  action="/gAppGameActBanner/index?os_type=<?php echo Yii::app()->request->getParam('os_type',1);?>" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<select name="gameid">
		<option value=""></option>
					<?php 
					$rowsHtmlTag=<<<EndOfRowTag
					<option value="%s">%s</option>
EndOfRowTag;
				$os_type=Yii::app()->request->getParam('os_type',1);
				$sql="select gameid,game_name from t_game_config where os_type=".$os_type.";";
				$game_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
				$game_name_list=array();
			 	foreach ($game_list as $gameItem){
			 		$game_name_list[$gameItem['gameid']]=$gameItem['game_name'];
			 		$rowHtml=sprintf($rowsHtmlTag,
			 				$gameItem['gameid'],
			 				$gameItem['game_name']);
			 		echo $rowHtml;
			 	}
					?>
		</select>
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
	<a href="#page-stats" class="block-heading" data-toggle="collapse"><?php echo Yii::app()->request->getParam('os_type',1)==1?"Android":"iOS";?>平台特权活动轮播广告管理</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:140px;">游戏名称</th>
					<th>图片</th>
					<th>url</th>
					<th>序号</th>
					<th>状态</th>
					<th width="80px">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td><img src="%s" width="100" ></td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>
		<a href="/gAppGameActBanner/modify?id=%s&gameid=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/gAppGameActBanner/del?id=%s&gameid=%s"></i></a>
	</td>
<tr>
EndOfRowTag;
				$os_type=Yii::app()->request->getParam('os_type',1);
				$state=array('0'=>'下架','1'=>'灰度','2'=>'上架');
				$sql="select gameid,game_name from t_game_config where os_type=".$os_type.";";
				$game_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
				$game_name_list=array();
				foreach ($game_list as $gameItem){
					$game_name_list[$gameItem['gameid']]=$gameItem['game_name'];
				}
			 	foreach ($t_activity_banner as $item){
			 		if(isset($game_name_list[$item["gameid"]])){
			 			$rowHtml=sprintf($rowsHtmlTag,
			 					$game_name_list[$item["gameid"]],
			 					$item["img"],
			 					$item["url"],
			 					$item["no"],
			 					$state[$item["state"]],
			 					$item["id"],$item["gameid"],$item["id"],$item["gameid"]);
			 			echo $rowHtml;
			 		}else{
			 			echo "游戏".$item["gameid"]."信息有误";
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