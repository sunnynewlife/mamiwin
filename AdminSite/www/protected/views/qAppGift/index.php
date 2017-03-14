<div class="btn-toolbar">
	<a href="/qAppGift/add" class="btn btn-primary"><i class="icon-plus"></i>新建礼包</a>
</div>
<div id="search" class="in collapse">
	<form class="form_search" action="/qAppGift/index" method="POST" style="margin-bottom: 0px">
		<div style="float: left; margin-right: 5px; margin-left: 15px; margin-top: 25px;">
			<table class="tableApp" style="width: 815px;">
				<tr>
					<td style="width: 120px;">归属游戏：</td>
					<td><input type="text" maxlength="50" name="AppName" id="AppName" value="<?php echo $AppName;?>" onclick="javascript:selectApp();"  autofocus="true"  /></td>
					<td style="width: 120px;">状态：</td>
					<td>
						<select name="Status" class="input-xlarge">
							<option value="" <?php echo empty($Status)?" selected":""; ?>>全部</option>
							<option value="1" <?php echo $Status=="1"?" selected":""; ?>>在线</option>
							<option value="2" <?php echo $Status=="2"?" selected":""; ?>>白名单可见</option>
							<option value="3" <?php echo $Status=="3"?" selected":""; ?>>下线</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>礼包ID：</td>
					<td><input type='text' maxlength='20' name="GiftIdx" value="<?php echo $GiftIdx;?>" /></td>
					<td>礼包名称:</td>
					<td><input type='text' maxlength='20' name="GiftName" value="<?php echo $GiftName;?>" /></td>
				</tr>
			</table>
		</div>
		<div class="btn-toolbar"
			style="padding-top: 25px; padding-bottom: 0px; margin-bottom: 0px">
			<input type="hidden" name="search" value="1">
			<button type="submit" class="btn btn-primary">查询</button>
		</div>
		<div style="clear: both;"></div>
	</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">礼包列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width: 60px;">礼包ID</th>
					<th style="width: 120px;">礼包名称</th>
					<th style="width: 120px;">归属游戏</th>
					<th style="width: 80px;" >状态</th>
					<th style="width: 40px;" >展示顺序</th>
					<th style="width: 4px;" >分类</th>
					<th style="width: 50px;" >标签</th>
					<th style="width: 70px;" >礼包数量</th>
					<th style="width: 40px;" >操作</th>
				</tr>
			</thead>
			<tbody>
<?php
$rowsHtmlTag = <<<EndOfRowTag
			<tr>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s/%s</td>
					<td>
						<a href="/qAppGift/modify?idx=%s" title="修改礼包"><i class="icon-pencil"></i></a>&nbsp;
						<a href="/qAppGift/upload?idx=%s" title="导入礼包码"><i class="icon-upload"></i></a>&nbsp;
						<a href="/qAppGift/download?idx=%s" title="导出礼包码"><i class="icon-download"></i></a>&nbsp;
					</td>
			</tr>
EndOfRowTag;
			foreach ($gift_list as $item){
				$tagType="";
				if($item["TagType1"]=="1"){
					$tagType.=",荐 ";
				}
				if($item["TagType2"]=="1"){
					$tagType.=",独 ";
				}
				if($item["TagType3"]=="1"){
					$tagType.=",新 ";
				}
				if(empty($tagType)==false){
					$tagType=substr($tagType, 1);
					$tagType=str_replace(",", "、", $tagType);
				}
				echo sprintf($rowsHtmlTag,$item["IDX"],$item["Name"],$item["AppName"],
					$item["Status"]=="1"?"在线":($item["Status"]=="2"?"白名单可见":"下线"),
					$item["GiftOrder"],
					$item["Category"]=="1"?"热门":"无",
					$tagType,
					isset($item["RestCount"])?$item["RestCount"]:0,
					isset($item["TotalCount"])?$item["TotalCount"]:0,
					$item["IDX"],$item["IDX"],$item["IDX"]);
			}
			?>
			</tbody>
		</table>
		<?php echo $page;?>
	</div>
</div>
<?php $this->widget('application.widget.AppSelector'); ?>
<script type="text/javascript">
function selectApp()
{
	showAppSelector(saveAppCallback);	
}
function saveAppCallback(pvAppId,pvAppName,pvProrate)
{
	$("#AppName").val(pvAppName);	
}
</script>
