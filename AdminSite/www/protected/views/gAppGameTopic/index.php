<div class="btn-toolbar">
	<a href="/gAppGameTopic/add?os_type=<?php echo Yii::app()->request->getParam('os_type',1);?>&in_version=<?php echo Yii::app()->request->getParam('in_version','1.0');?>" class="btn btn-primary"><i class="icon-plus"></i>增加专题定义</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search">
<form class="form_search"  action="/gAppGameTopic/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>专题名称</label>
		<select name="id">
		<option value="">查询所有专题</option>
					<?php 
					$rowsHtmlTag=<<<EndOfRowTag
					<option value="%s">%s</option>
EndOfRowTag;
				$sql="select * from t_game_topic where os_type=? and in_version=?";
				$topic_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(Yii::app()->request->getParam('os_type',1),Yii::app()->request->getParam('in_version','2.0')),PDO::FETCH_ASSOC);
			 	foreach ($topic_list as $topic_item){
			 		$rowHtml=sprintf($rowsHtmlTag,
			 				$topic_item['id'],
			 				$topic_item['topic_name']);
			 		echo $rowHtml;
			 	}
					?>
		</select>
		<input type="hidden" name="os_type" value="<?php echo Yii::app()->request->getParam('os_type',1);?>" >
		<input type="hidden" name="in_version" value="<?php echo Yii::app()->request->getParam('in_version',1);?>" >
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse"><?php echo Yii::app()->request->getParam('os_type',1)==1?"Android":"iOS";?>平台游戏专题管理</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:140px;">专题名称</th>
					<th>状态</th>
					<th>标识</th>
					<th>排序序号</th>
					<th width="80px">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td style="color:red">%s</td>
	<td>%s</td>
	<td>
		<a href="/gAppGameTopic/modify?id=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/gAppGameTopic/del?id=%s"></i></a>
	</td>
<tr>
EndOfRowTag;
				$now=date('Y-m-d H:i:s');
                $state=array('0'=>'下架','1'=>'灰度','2'=>'上架');
			 	foreach ($t_game_topic as $item){
		 			$rowHtml=sprintf($rowsHtmlTag,
		 					$item["topic_name"],
		 					$state[$item["state"]],
		 					$item["flag"]=='hot'?'热门推荐':'',
		 					$item["topic_no"],
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
