<div class="btn-toolbar">
	<a href="/GAppGameConfigsV/add?os_type=<?php echo Yii::app()->request->getParam('os_type',1);?>&in_version=<?php echo Yii::app()->request->getParam('in_version','1.0');?>" class="btn btn-primary"><i class="icon-plus"></i>增加游戏配置</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search">
<form class="form_search"  action="/GAppGameConfigsV/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<input type="hidden" name="in_version" value="<?php echo Yii::app()->request->getParam('in_version','2.0');?>"> 
		<input type="hidden" name="os_type" value="<?php echo Yii::app()->request->getParam('os_type',1);?>"> 
		<input type="hidden" name="flag" value="<?php echo Yii::app()->request->getParam('flag','');?>"> 
		<input type="hidden" name="search" value="1" >
		<input  name="game_name" placeholder='游戏名字' value="<?php echo Yii::app()->request->getParam('game_name','');?>" >
		<div style="<?php echo Yii::app()->request->getParam('in_version','2.0')=='1.0'?"display:none;":""?>" >
			<label >游戏类型</label> 
			<select name="game_type">
			    <option value="">所有游戏</option>
				<option value="1" <?php echo Yii::app()->request->getParam('game_type',1)==1?"selected":"";?>>手游</option>
				<option value="2" <?php echo Yii::app()->request->getParam('game_type',1)==2?"selected":"";?>>PC游戏</option>
			</select>
		</div>
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
					<th>游戏标识</th>
					<th>排序序号</th>
					<th>专题</th>
					<th>游戏类型</th>
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
	<td style="color:red">%s</td>
	<td>%s</td>
    <td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>
		<a href="/GAppGameConfigsV/modify?id=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<!--<a data-toggle="modal" href="#" title="请勿删除">勿删</a>-->
	</td>
<tr>
EndOfRowTag;
				$flag_txt=array('hot'=>'热门推荐','new'=>'新上架推荐','first'=>'首发推荐','other'=>'其他推荐');
				$sql="select id,topic_name from t_game_topic where os_type=?;";
				$topic_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(Yii::app()->request->getParam('os_type',1)),PDO::FETCH_ASSOC);
				$topics=array();
				foreach ($topic_list as $item){
					$topics[$item['id']]=$item['topic_name'];
				}
				$state=array('0'=>'下架','1'=>'灰度','2'=>'上架');
			 	foreach ($t_game_config as $item){
					$rowHtml=sprintf($rowsHtmlTag,
						$item["gameid"],
						$item["game_name"],
						$item["game_logo"],
						$item["os_type"]==1?"Android":"iOS",
						$item["flag"],
						$item["index_no"],
						isset($topics[$item["topic_id"]])?$topics[$item["topic_id"]]:"",
						$item["game_type"]==1?"手游":"PC游戏",
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
