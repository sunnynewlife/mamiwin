<div class="btn-toolbar">
	<a href="/fAppMsgHistory/add" class="btn btn-primary"><i class="icon-plus"></i>发送消息</a>
	<a href="/fAppMsgHistory/index" class="btn btn-primary"><i class="icon-book"></i>&nbsp;待发送消息查看</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">已发送消息查看</a>
	<div id="search" class="in collapse">
		<form class="form_search"  method="POST" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">开始时间：</td>
						<td><input type='text' maxlength='20' class='dateInputBind start' name="startDt" value="<?php echo $DefaultStart;?>" /></td>
						<td style="width:120px;">结束时间：</td>
						<td><input type='text' maxlength='20' class='dateInputBind end' name="endDt" value="<?php echo $DefaultEnd;?>" /></td>
					</tr>
					<tr>
						<td style="width:120px;">消息标题：</td>
						<td style="width:120px;"><input type='text' maxlength='20'  name="title" /></td>
						<td><button type="submit" class="btn btn-primary">查  询</button></td>
						<td></td>
					</tr>
				</table>			
				<input type="hidden" name="search" value="1" >
			</div>
			<div style="clear:both;"></div>
		</form>
	</div>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="50px">消息ID</th>
					<th width="160px">标题</th>
					<th width="45px" title="最初上传文件中个数">计划数</th>
					<th width="45px" title="匹配推广员手机号后个数">发送数</th>
					<th width="45px" title="实际消息发送成功个数">成功数</th>
					<th width="135px" title="消息实际开始发送的时间">开始时间</th>
					<th width="135px" title="消息最后发送的时间">结束时间</th>
					<th title="用户浏览消息统计数">阅读统计</th>
					<th width="40px">操作</th>
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
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td>
		<a href="/fAppMsgHistory/resend?InformationId=%s" title="重发"><i class="icon-repeat"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="取消定时发送" style="display:none;"><i class="icon-remove" href="/fAppMsgHistory/cancel?InformationId=%s"></i></a>
	</td>
<tr>
EndOfRowTag;
			$titleLen=14;
			foreach ($MsgList as $item){
				$title=$item["Title"];
				if(mb_strlen($title,"utf-8")>$titleLen){
					$title=mb_substr($title, 0,$titleLen,"utf-8")."..";
				}
				echo sprintf($rowsHtmlTag,
					$item["InformationId"],
					$title,
					(empty($item["PhoneListFileCounts"]) || $item["PhoneListFileCounts"]<1)?"未上传":$item["PhoneListFileCounts"],
					$item["SentPhoneCount"],

					$item["InformationId"],$item["InformationId"]
				);
			}
			?>
			</tbody>
		</table>
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
