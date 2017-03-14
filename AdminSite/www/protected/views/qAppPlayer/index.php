<div id="search" class="in collapse">
	<form class="form_search" action="/qAppPlayer/index" method="POST" style="margin-bottom: 0px">
		<div style="float: left; margin-right: 5px; margin-left: 15px; margin-top: 25px;">
			<table class="tableApp" style="width: 815px;">
				<tr>
					<td style="width: 120px;">手机号码：</td>
					<td><input type="text" maxlength="50" name="PhoneNo" id="PhoneNo" value="<?php echo $PhoneNo;?>"  autofocus="true"  /></td>
					<td style="width: 120px;">微信OpenId：</td>
					<td><input type="text" maxlength="100" name="OpenId" id="OpenId" value="<?php echo $OpenId;?>"  autofocus="true"  /></td>
				</tr>
				<tr>
					<td>关注时间：</td>
					<td>
						<input type='text' maxlength='20' class='dateInputBind start' name="sCreateDt" value="<?php echo $sCreateDt;?>" style="width:80px;"  />
							&nbsp;&nbsp;~&nbsp;&nbsp;
						<input type='text' maxlength='20' class='dateInputBind end' name="eCreateDt" value="<?php echo $eCreateDt;?>" style="width:80px;"  />						
					</td>
					<td>绑定时间:</td>
					<td>
						<input type='text' maxlength='20' class='dateInputBind start' name="sBindDt" value="<?php echo $sBindDt;?>" style="width:80px;"  />
							&nbsp;&nbsp;~&nbsp;&nbsp;
						<input type='text' maxlength='20' class='dateInputBind end' name="eBindDt" value="<?php echo $eBindDt;?>" style="width:80px;"  />						
					</td>
				</tr>
			</table>
		</div>
		<div class="btn-toolbar"
			style="padding-top: 25px; padding-bottom: 0px; margin-bottom: 0px">
			<input type="hidden" name="search" value="1">
			<button type="submit" class="btn btn-primary">查询</button>
<?php
			if(count($Q_MicroPlayer)>0){
				echo "<button type=\"button\" class=\"btn btn-primary\" onclick=\"exportCSV();\">导出</button>";
			} 
?>						
			
		</div>
		<div style="clear: both;"></div>
		<input type="hidden" name="export" value="0" id="export">
	</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">微信用户列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width: 47px;">IDX</th>
					<th>OpenId</th>
					<th style="width: 88px;">手机号码</th>
					<th style="width:88px;text-align:right">累计礼金</th>
					<th style="width:88px;text-align:right" >礼金余额</th>
					<th style="width: 75px;" >是否关注</th>
					<th style="width: 132px;" >关注时间</th>
					<th style="width: 132px;" >绑定时间</th>
					<th style="width: 58px;" >操作</th>
				</tr>
			</thead>
			<tbody>
<?php
$rowsHtmlTag = <<<EndOfRowTag
			<tr>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td style="text-align:right">%s</td>
					<td style="text-align:right">%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>
						%s
					</td>
			</tr>
EndOfRowTag;
			foreach ($Q_MicroPlayer as $item){
				if(empty($item["PhoneNo"])){
					$opHtml=sprintf('<a href="/qAppPlay/payState?idx=%s" title="冻结账户"><i class="icon-pause"></i></a>&nbsp;',$item["Idx"]);
				}else{
					$className= ($item["PayState"]=="1"?"icon-pause":"icon-play");
					$title=($item["PayState"]=="1"?"冻结账户":"解冻账户");
					$newPateState=($item["PayState"]=="1"?"0":"1");
					$opHtml=sprintf('
						<a href="/qAppPlayer/hot?PhoneNo=%s" title="查看参加的活动"><i class="icon-list-alt"></i></a>&nbsp;
						<a href="/qAppPlayer/gift?PhoneNo=%s" title="查看领取的礼包"><i class="icon-gift"></i></a>&nbsp;
						<a href="/qAppPlayer/payState?idx=%s&state=%s" title="%s"><i class="%s"></i></a>&nbsp;',$item["PhoneNo"],$item["PhoneNo"],$item["Idx"],
							$newPateState,$title,$className);
				}
				echo sprintf($rowsHtmlTag,$item["Idx"],
					$item["OpenId"],$item["PhoneNo"],
					number_format($item["IncomeSummary"],2,".",","),
					number_format($item["NetAmount"],2,".",","),
					$item["FocusStatus"]=="1"?"是":"否",
					$item["FocusDt"],$item["BindDt"],$opHtml);
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
function exportCSV()
{
	$("#export").val(1);
	$(".form_search").submit();
	$("#export").val(0);
}
</script>