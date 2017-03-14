<div class="btn-toolbar">
	<a href="/gAppActivityV/add?os_type=<?php echo Yii::app()->request->getParam('os_type',1);?>&in_version=<?php echo Yii::app()->request->getParam('in_version','1.0');?>" class="btn btn-primary"><i class="icon-plus"></i>增加活动信息配置</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search">
<form class="form_search"  action="/gAppActivityV/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<input type="hidden" name="in_version" value="<?php echo Yii::app()->request->getParam('in_version','1.0');?>"> 
		<label>查询所有请留空</label>
		<select name="gameid">
		<option value=""></option>
					<?php 
					$rowsHtmlTag=<<<EndOfRowTag
					<option value="%s">%s</option>
EndOfRowTag;
				$os_type=Yii::app()->request->getParam('os_type',1);
				$in_version=Yii::app()->request->getParam('in_version','2.0');
				$sql="select gameid,game_name from t_game_config where in_version=".$in_version." and os_type=".$os_type.";";
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
	<a href="#page-stats" class="block-heading" data-toggle="collapse"><?php echo Yii::app()->request->getParam('os_type',1)==1?"Android":"iOS";?>平台特权活动管理</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>活动名称</th>
					<th style="width:140px;">所属游戏名称</th>
					<th>活动标题</th>
					<th>活动图片</th>
					<th>活动排序编号</th>
					<th>活动开始时间</th>
					<th>活动介绍时间</th>
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
	<td><img src="%s" width="100" ></td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>
		<a href="/gAppActivityV/modify?activity_id=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/gAppActivityV/del?activity_id=%s&gameid=%s"></i></a
	</td>
<tr>
EndOfRowTag;
				$os_type=Yii::app()->request->getParam('os_type',1);
				$sql="select gameid,game_name from t_game_config where os_type=".$os_type.";";
				$game_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
				$game_name_list=array();
				$state=array('0'=>'下架','1'=>'灰度','2'=>'上架');
				foreach ($game_list as $gameItem){
					$game_name_list[$gameItem['gameid']]=$gameItem['game_name'];
				}
			 	foreach ($t_game_activity as $item){
			 		if(isset($game_name_list[$item["gameid"]])){
						$rowHtml=sprintf($rowsHtmlTag,
							$item["activity_name"],
							$game_name_list[$item["gameid"]],
							$item["activity_title"],
							$item["activity_img"],
							$item["activity_no"],
							$item["activity_start_time"],
							$item["activity_end_time"],
							$state[$item["state"]],
							$item["activity_id"],$item["activity_id"],$item["gameid"]);
						echo $rowHtml; 
			 		}else{
			 			echo 'os='.($os_type==2?"iOS":"Android")."且gameid=".$item["gameid"].",信息不可用";
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
