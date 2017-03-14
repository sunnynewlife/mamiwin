<div class="btn-toolbar">
	<a href="/fAppConfigs/add" class="btn btn-primary"><i class="icon-plus"></i>增加配置定义</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" class="collapse">
<form class="form_search"  action="/fAppConfigs/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<input type="text" name="ConfigKey" value="" placeholder="输入配置ConfigKey" > 
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">分红其他配置定义列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>IDX</th>
					<th>配置项Key</th>
					<th>配置项描述</th>
					<th>配置项值</th>
					<th width="80px">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td width="40px">%s</td>
	<td width="180px">%s</td>
	<td width="180px">%s</td>
	<td>%s</td>
	<td>
		<a href="/fAppConfigs/modify?ConfigId=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/fAppConfigs/del?ConfigId=%s"></i></a>
	</td>
<tr>
EndOfRowTag;

				$configValueMaxLen=50;
			 	foreach ($SystemConfig as $item){
					$configValue=$item["ConfigValue"];
					$valueLen=mb_strlen($configValue,"utf-8");
					if($valueLen>$configValueMaxLen){
						$configValue=mb_substr($configValue,0,$configValueMaxLen,"utf-8")."...";
					}
					$rowHtml=sprintf($rowsHtmlTag,$item["ConfigId"],
						$item["ConfigKey"],
						$item["ConfigName"],
						$configValue,
						$item["ConfigId"],$item["ConfigId"]);
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
