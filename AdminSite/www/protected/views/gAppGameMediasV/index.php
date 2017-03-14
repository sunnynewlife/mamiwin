<div class="btn-toolbar">
	<a href="/gAppGameMediasV/add?os_type=<?php echo Yii::app()->request->getParam('os_type',1);?>&in_version=<?php echo Yii::app()->request->getParam('in_version','1.0');?>" class="btn btn-primary"><i class="icon-plus"></i>增加多媒体定义</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search">
<form class="form_search"  action="/gAppGameMediasV/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
				<input type="hidden" name="in_version" value="<?php echo Yii::app()->request->getParam('in_version','2.0');?>"> 
		<input type="hidden" name="os_type" value="<?php echo Yii::app()->request->getParam('os_type',1);?>"> 
		<input type="hidden" name="search" value="1" >
		<label>游戏名称</label> 
	 	<select name="gameid">
	 		<option value=""></option>
		<?php 
					$rowsHtmlTag=<<<EndOfRowTag
					<option value="%s" %s>%s</option>
EndOfRowTag;
				$os_type=Yii::app()->request->getParam('os_type',1);
				$in_version=Yii::app()->request->getParam('in_version','2.0');
				$gameid=Yii::app()->request->getParam('gameid','');
				$sql="select gameid,game_name from t_game_config where os_type=".$os_type." and in_version='".$in_version."';";
				$game_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
				$sql="select gameid,tool_name from t_game_tool where os_type=".$os_type." and in_version='".$in_version."';";
				$tool_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
				$game_name_list=array();
			 	foreach ($game_list as $gameItem){
			 		$game_name_list[$gameItem['gameid']]="游戏：".$gameItem['game_name'];
			 		$rowHtml=sprintf($rowsHtmlTag,
			 				$gameItem['gameid'],
			 				$gameid==$gameItem['gameid']?'selected':'',
			 				"游戏：".$gameItem['game_name']);
			 		echo $rowHtml;
			 	}
			 	foreach ($tool_list as $gameItem){
			 		$game_name_list[$gameItem['gameid']]="助手：".$gameItem['tool_name'];
			 		$rowHtml=sprintf($rowsHtmlTag,
			 				$gameItem['gameid'],
			 				$gameid==$gameItem['gameid']?'selected':'',
			 				"助手：".$gameItem['tool_name']);
			 		echo $rowHtml;
			 	}
					?>
		</select>
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse"><?php echo Yii::app()->request->getParam('os_type',1)==1?"Android":"iOS";?>平台多媒体管理</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:140px;">媒体名称</th>
					<th>归属</th>
					<th>平台类型</th>
					<th>URL</th>
					<th>媒体类型</th>
					<th>排序序号</th>
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
	<td>
		<a href="/gAppGameMediasV/modify?id=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/gAppGameMediasV/del?id=%s"></i></a>
	</td>
<tr>
EndOfRowTag;
			 	$state=array('0'=>'下架','1'=>'灰度','2'=>'上架');
			 	foreach ($t_game_media as $item){
		 			$rowHtml=sprintf($rowsHtmlTag,
		 					$item['media_name'],
		 					$game_name_list[$item["gameid"]]?$game_name_list[$item["gameid"]]:'',
		 					$os_type==1?"Android":"iOS",
		 					$item["url"],
		 					$item["type"]==1?"图片":"视频",
		 					$item["media_no"],
		 					$state[$item["state"]],
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
	jQuery("#game_type").bind("change",function(){
		$("#class_game").hide();
		$("#class_tool").hide();
		if($("#game_type").val()==3){
			$("#class_tool").show();
		}else{
			$("#class_game").show();
		}
	});
</script>
