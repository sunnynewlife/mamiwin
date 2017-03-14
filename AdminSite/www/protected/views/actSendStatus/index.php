<div class="btn-toolbar">
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" class="collapse">
<form class="form_search"  action="/actSendStatus/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<input type="text" name="aid" value="" placeholder="输入活动ID" > 
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">活动领取量记录表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>记录主键</th>
					<th>活动ID</th>
					<th>游戏区ID</th>
					<th>领取日期</th>
					<th>领取数量</th>
					<th>最后领取时间</th>
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
	<td>
		<a href="/actSendStatus/modify?ID=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/actSendStatus/del?ID=%s"></i></a>
	</td>
<tr>
EndOfRowTag;

			 	foreach ($act_send_status as $p_r){
					$rowHtml=sprintf($rowsHtmlTag,
						$p_r["ID"],$p_r["aid"],$p_r["areaid"],$p_r["date"],$p_r["sendNum"],
						$p_r["last_update_time"],$p_r["ID"],$p_r["ID"]);
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
