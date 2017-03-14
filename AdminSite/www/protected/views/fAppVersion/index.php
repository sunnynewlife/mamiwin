<div class="btn-toolbar">
	<a href="/fAppVersion/add?AppId=<?php echo $AppId; ?>" class="btn btn-primary"><i class="icon-plus"></i>创建新版本</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" class="collapse">
<form class="form_search"  action="/fAppVersion/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<input type="text" name="VersionName" value="" placeholder="输入版本名称" > 
		<input type="hidden" name="search" value="1" >
		<input type="hidden" name="AppId" value="<?php echo $AppId; ?>" >
		
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">版本列表</a>
	<div id="page-stats" class="block-body collapse in">
	
		<div style="float:left;margin-right:5px;margin-left:5px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:140px;">游戏名称：</td>
						<td><?php echo $AppName;?></td>
						<td style="width:140px;">游戏AppId</td>
						<td><?php echo $AppId; ?></td>
					</tr>
					<tr>
						<td colspan=4>&nbsp;</td>
					</tr>
				</table>			
		</div>
		<div style="clear:both;"></div>
			
		<table class="table table-striped">
			<thead>
				<tr>
					<th>版本ID</th>
					<th>版本名称</th>
					<th>版本状态</th>
					<th>游戏包状态</th>
					<th>当前推广版本</th>
					<th>替换为</th>
					<th style="text-align:right;">剩余包量</th>
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
	<td style="text-align:right;">%s</td>
	<td>
		<a href="/fAppVersion/modify?AppVersionId=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a href="%s"  %s title="%s"><i class="icon-arrow-up"></i></a>&nbsp;
		%s
		%s
	</td>
<tr>
EndOfRowTag;
				
			 	foreach ($AppVersion as $item){
					$createVersionHref="";
					$tip="";
					$clickStr="";
					
					$prePackingStr="";
					$delPackingStr="";
					
					if($item["PackageState"]==3){
						$createVersionHref="#";
						$tip="平台打包版本已经准备好，可以进行打包了。";
						$clickStr=" onclick=\"javascript:layer.alert('版本已提交到打包平台。', 1);\" style=\"color:red;\" ";
						$prePackingStr=sprintf("<a href=\"/fAppVersion/promoterCreate?AppVersionId=%s\" title=\"批量预打包此版本\"><i class=\"icon-download-alt\"></i></a>",$item["AppVersionId"]);
					} else if($item["PackageState"]==2){
						$createVersionHref="#";
						$tip="版本已提交到打包平台,等待平台通知版本准备好。";
						$clickStr=" onclick=\"javascript:layer.alert('版本已提交到打包平台，等待平台通知版本准备好。', 7);\" style=\"color:green;\" ";
					}else {
						if($AppRegisterState!=1){
							$createVersionHref="#";
							$tip="游戏还未注册到打包平台，请先注册游戏";
							$clickStr=" onclick=\"javascript:layer.alert('游戏还未注册到打包平台，请先注册游戏。', 8);\" style=\"color:gray;\" ";
						}else {
							if($item["PackageState"]==1 && empty($item["PackagePath"])==false){
								$createVersionHref="/fAppVersion/packageCreate?AppVersionId=".$item["AppVersionId"];
								$tip="版本提交到打包平台";
							}else{
								$createVersionHref="#";
								$tip="请先上传、更新游戏版本包。";
								$clickStr=" onclick=\"javascript:layer.alert('请先上传、更新游戏版本包。', 8);\" style=\"color:gray;\" ";
							}
						}
					}
					
					if(empty($item["PackageNum"])==false && $item["PackageNum"]>0){
						$delPackingStr=sprintf("<a href=\"/fAppVersion/delPackage?AppVersionId=%s\" title=\"减少库存包\"><i class=\"icon-remove\"></i></a>",$item["AppVersionId"]);
					}
					$rowHtml=sprintf($rowsHtmlTag,
						$item["AppVersionId"],
						$item["VersionName"],
						$item["State"]==0?"测试":($item["State"]==1?"上架":($item["State"]==2?"下架":"")),
						$item["PackageState"]==0?"未上传":($item["PackageState"]==1?"已上传":($item["PackageState"]==2?"已通知":($item["PackageState"]==3?"已被下载":""))),
						$item["IsPublishVersion"]==0?"不是":($item["IsPublishVersion"]==1?"是":""),
						empty($item["App_AppVersionId"])?"":$item["SubstuiteVersion"],
						empty($item["PackageNum"])?0:$item["PackageNum"],
						$item["AppVersionId"],
						$createVersionHref,$clickStr,$tip,$prePackingStr,$delPackingStr
					);
					echo $rowHtml; 
				}
			?>
			</tbody>
		</table>
	</div>
</div>
