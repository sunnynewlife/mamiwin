<div class="btn-toolbar">
	<a href="/qAppEvn/add" class="btn btn-primary"><i class="icon-plus"></i>新建活动</a>
</div>
<div id="search" class="in collapse">
	<form class="form_search" action="/qAppEvn/index" method="POST" style="margin-bottom: 0px">
		<div style="float: left; margin-right: 5px; margin-left: 15px; margin-top: 25px;">
			<table class="tableApp" style="width: 815px;">
				<tr>
					<td style="width: 120px;">开始时间：</td>
					<td><input type='text' maxlength='20' class='dateInputBind start' name="startDt" value="<?php echo $startDt;?>" /></td>
					<td style="width: 120px;">结束时间：</td>
					<td><input type='text' maxlength='20' class='dateInputBind end' name="endDt" value="<?php echo $endDt;?>" /></td>
				</tr>
				<tr>
					<td>游戏名称：</td>
					<td><input type='text' maxlength='20' name="AppName" value="<?php echo $AppName;?>" /></td>
					<td>活动名称:</td>
					<td><input type='text' maxlength='20' name="EvnName" value="<?php echo $EvnName;?>" /></td>
				</tr>
				<tr>
					<td>活动状态：</td>
					<td>
						<select name="EvnStatus">
							<option value="" <?php echo empty($EvnStatus)?" selected":""; ?>>全部</option>
							<option value="1" <?php echo $EvnStatus=="1"?" selected":""; ?>>未开始</option>
							<option value="2" <?php echo $EvnStatus=="2"?" selected":""; ?>>已开始(未成团)</option>
							<option value="3" <?php echo $EvnStatus=="3"?" selected":""; ?>>已开始(已成团)</option>
							<option value="4" <?php echo $EvnStatus=="4"?" selected":""; ?>>已过期(未成团)</option>
							<option value="5" <?php echo $EvnStatus=="5"?" selected":""; ?>>已过期(已成团)</option>
						</select>
					</td>
					<td>活动类型:</td>
					<td>
						<select name="EvnType">
							<option value="" <?php echo empty($EvnType)?" selected":""; ?>>全部</option>
							<option value="1" <?php echo $EvnType=="1"?" selected":""; ?>>非阶梯式返利</option>
							<option value="2" <?php echo $EvnType=="2"?" selected":""; ?>>阶梯式返利</option>
						</select>
					</td>
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
	<a href="#page-stats" class="block-heading" data-toggle="collapse">活动列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width: 110px;">游戏名称</th>
					<th style="width: 120px;">活动名称</th>
					<th style="width: 30px;">顺序</th>
					<th style="width: 140px;">活动时间段</th>
					<th style="width: 80px;">活动类型</th>
					<th style="width: 120px;">参与(实际/随机/需要)</th>
					<th style="width: 75px;" >上线状态</th>
					<th style="width: 95px;" >活动状态</th>
					<th width="60px">操作</th>
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
				<td>%s/%s/%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>
					<a href="/qAppEvn/modify?idx=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
					<a href="/qAppEvn/query?idx=%s" title="查看"><i class="icon-list-alt"></i></a>
				</td>
			</tr>
EndOfRowTag;
			foreach ($evn_list as $item){
				echo sprintf($rowsHtmlTag,
					$item["AppName"],$item["EvnName"],$item["EvnOrder"],
					str_replace("-", ".", $item["EvnStart"])."-".str_replace("-", ".", $item["EvnEnd"]),
					$item["EvnType"]=="1"?"非阶梯式返利":"阶梯式返利",
					$item["EvnJoinQty"],$item["EvnJoinRandQty"],$item["EvnQty"],
					$item["Status"]=="1"?"在线":($item["Status"]=="2"?"白名单可见":"下线"),
					$item["EvnJoinStatus"],
					$item["idx"],$item["idx"]);
			}
			?>
			</tbody>
		</table>
		<?php echo $page;?>
	</div>
</div>
<script type="text/javascript">
$(".dateInputBind").each(function(){
	$(this).datepicker({
		dateFormat: "yy-mm-dd",
		defaultDate: "+0",
		dayNamesShort:['周日','周一', '周二', '周三', '周四', '周五', '周六'],
		dayNamesMin:['日','一', '二', '三', '四', '五', '六'],
		changeMonth: true,
		changeYear: true,
		monthNames:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
		monthNamesShort:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
		numberOfMonths: 1,
		yearRange: "-5:+5",
	});	
});
</script>
