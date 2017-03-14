<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">推广员反馈信息数据</a>
	<div id="search" class="in collapse">
		<form class="form_search"  method="POST" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
				
					<tr>
						<td style="width:120px;">开始时间：</td>
						<td><input type='text' maxlength='20' class='dateInputBind start' name="startDt" value="<?php echo $startDt;?>" /></td>
						<td style="width:120px;">结束时间：</td>
						<td><input type='text' maxlength='20' class='dateInputBind end' name="endDt" value="<?php echo $endDt;?>" /></td>
					</tr>
					<tr>
						<td>推广员账号：</td>
						<td><input type='text' maxlength='20'  name="Phone" value="<?php echo $Phone;?>" /></td>
						<td>处理状态：</td>
						<td>
							<select name="State">
								<option value="ALL" <?php echo $State=="ALL"? " selected ":" ";?>>全部</option>
								<option value="0" <?php echo $State=="0"? " selected ":" ";?>>未处理</option>
								<option value="1" <?php  echo $State=="1"? " selected ":" ";?>>已处理</option>
							</select>
						</td>						
					</tr>
					<tr>
						<input type="hidden" name="search" value="1" >
						<td colspan=4 style="padding-left:390px;"><button type="submit" class="btn btn-primary">查  询</button></td>
					</tr>										
				</table>			
			</div>
			<div style="clear:both;"></div>
		</form>
	</div>
	
	
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:130px;">推广员账号</th>
					<th style="width:140px;">反馈时间</th>
					<th>反馈内容</th>
					<th style="width:140px;">QQ号</th>
					<th style="width:70px;">状态</th>
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
	<td>
		<a href="/fAppPromoter/feedback?feedbackId=%s">%s</i></a>&nbsp;
	</td>
<tr>
EndOfRowTag;
			$configValueMaxLen="25";
			foreach ($Feedback as $item){
				$statusTitle=$item["State"]==0?"未处理":($item["State"]==1?"查看":"");
				$content=$item["Content"];
				$valueLen=mb_strlen($content,"utf-8");
				if($valueLen>$configValueMaxLen){
					$content=mb_substr($content,0,$configValueMaxLen,"utf-8")."...";
				}
				$rowHtml=sprintf($rowsHtmlTag,
						$item["PhoneNo"],
						$item["CreateDt"],
						htmlentities($content,ENT_COMPAT,"utf-8"),
						$item["ContactQQ"],
						$item["FeedbackId"],
						$statusTitle
					);
				echo $rowHtml;
			}
?>				
			</tbody>
		</table>
		<?php echo $Page;?>
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