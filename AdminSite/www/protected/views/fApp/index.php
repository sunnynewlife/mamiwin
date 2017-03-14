<div class="btn-toolbar">
	<a href="/fApp/add" class="btn btn-primary"><i class="icon-plus"></i>创建</a>
	<a href="/fApp/installPackageIndex" class="btn btn-primary"><i class="icon-info-sign"></i>游戏安装包信息</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" class="in collapse">
<form class="form_search"  action="/fApp/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<input type="text" name="AppName" value="" placeholder="输入游戏名称" > 
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">游戏列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:85px;">AppId</th>
					<th>游戏名称</th>
					<th style="width:60px;">排列顺序</th>
					<th style="width:90px;">游戏类型</th>
					<th style="width:100px;text-align:right">累计收入</th>
					<th style="width:100px;text-align:right">累计支出</th>
					<th style="width:60px;text-align:right">返利比例</th>
					<th style="width:40px;">状态</th>
					<th style="width:60px;text-align:right">打包上限</th>
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
	<td style="text-align:right">%s</td>
	<td style="text-align:right">%s</td>
	<td style="text-align:right">%s</td>
	<td>%s</td>
	<td style="text-align:right">%s</td>					
	<td>
		<a href="/fApp/showGameData?AppId=%s" title="查看游戏分红数据"><i class="icon-list-alt"></i></a>&nbsp;			
		<a href="/fApp/modify?AppId=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a href="/fAppVersion/index?AppId=%s" title="版本管理"><i class="icon-th-list"></i></a>&nbsp;
		<a href="%s"  %s title="%s"><i class="icon-arrow-up"></i></a>
	</td>
<tr>
EndOfRowTag;
			 	foreach ($ViewApp as $item){
					$registerHref="";
					$tip="";
					$clickStr="";
					
					if($item["RegistState"]==1){
						$registerHref="#";
						$tip="游戏已注册到打包平台";
						$clickStr=" onclick=\"javascript:layer.alert('游戏已注册到打包平台。', 1);\" style=\"color:red;\" ";
					}else if($item["RegistState"]==0 && empty($item["PackagePrefixName"])==false){
						$registerHref="/fApp/register?AppId=".$item["AppId"];
						$tip="游戏注册到打包平台";
					}else{
						$registerHref="#";
						$tip="请先更新保存游戏的打包文件名前缀";
						$clickStr=" onclick=\"javascript:layer.alert('请先更新保存游戏的打包文件名前缀。', 3);\" style=\"color:gray;\" ";
					}
					$rowHtml=sprintf($rowsHtmlTag,$item["AppId"],
						$item["AppName"],
						$item["SortIndex"],
						$item["AppType_Name"],
						empty($item["Deposit"])?"":number_format($item["Deposit"],2,".",","),
						empty($item["Withdraw"])?"":number_format($item["Withdraw"],2,".",","),
						empty($item["AppPromoterProrate"])?"":(number_format($item["AppPromoterProrate"]*100,0,".","")."%"),
						($item["AppStatus"]==0?"测试":($item["AppStatus"]==1?"上线":"下线")),
						number_format((empty($item["MinPackingPoolSize"])?$GlobalPackSize:$item["MinPackingPoolSize"]),0,"",","),
						$item["AppId"],$item["AppId"],$item["AppId"],$registerHref,$clickStr,$tip);
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
