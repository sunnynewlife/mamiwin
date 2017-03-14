<div id="search" class="in collapse">
	<form class="form_search" name="frm" action="/qAppCoupon/query" method="POST" style="margin-bottom: 0px">
		<div style="float: left; margin-right: 5px; margin-left: 15px; margin-top: 25px;">
			<table class="tableApp" style="width: 815px;" border="0">
				<tr>
						<td>礼券发送时间</td>
						<td>
							<input type='text' maxlength='20' class='dateInputBind start' name="GrantStartDt" id="GrantStartDt" value="<?php echo $GrantStartDt;?>" style="width:80px;"  />
							&nbsp;&nbsp;~&nbsp;&nbsp;
							<input type='text' maxlength='20' class='dateInputBind end' name="GrantEndDt" id="GrantEndDt" value="<?php echo substr($GrantEndDt,0,10);?>" style="width:80px;"  />
						</td>
						<td colspan=2></td>
				</tr>
				<tr>
					<td>礼券ID：</td>
					<td><input type='text' maxlength='20' name="CouponIdx" value="<?php echo $CouponIdx;?>" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
						<td>用户手机号</td>
						<td colspan=3>
							<textarea id="PhoneNo" maxlength="1500" name="PhoneNo" rows="4" style="width:649px;" class="input-xlarge" autofocus="true"><?php echo $PhoneNo;?></textarea>
						</td>
				</tr>
				<tr>
						<td><button type="button" class="btn btn-primary" id="btn_down">报表下载</button></td>
						<td colspan=3>
						</td>
				</tr>
			</table>
		</div>
		<div class="btn-toolbar"
			style="padding-top: 25px; padding-bottom: 0px; margin-bottom: 0px">
			<input type="hidden" name="search" value="1">
			<button type="submit" class="btn btn-primary" id="btn_query">查询</button>
		</div>
		<div style="clear: both;"></div>
	</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">礼券列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width: 90px;">用户手机</th>
					<th style="width: 60px;">礼券ID</th>
					<th style="width: 85px;">礼券发送时间</th>
					<th style="width: 85px;">礼券领取时间</th>
					<th style="width: 60px;" >是否兑换</th>
					<th style="width: 120px;" >使用游戏</th>
					<th style="width: 85px;" >礼券使用时间</th>
					<th style="width: 60px;" >充值金额</th>
					<th style="width: 85px;" >充值时间</th>
					<th style="width: 90px;" >订单号</th>
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
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
			</tr>
EndOfRowTag;
			foreach ($Coupon_list as $item){
				$AppName = "";
				$isUsed  = $item["isUsed"] ; 
				$PhoneNo  = $item["PhoneNo"] ; 
				$CouponIdx  = $item["CouponIdx"] ; 
				$CreateDt  = isset($item["CreateDt"])?$item["CreateDt"]:""; 
				$DrawDt  = isset($item["DrawDt"])?$item["DrawDt"]:""; 
				$UpdateDt  = isset($item["UpdateDt"])?$item["UpdateDt"]:""; 
				$CouponIdx  = $item["CouponIdx"] ;
				$AppName  = isset($item["AppName"])?$item["AppName"]:""; 
				$GameAmount  = isset($item["GameAmount"])?$item["GameAmount"]:""; 
				$TransactDt  = isset($item["TransactDt"])?$item["TransactDt"]:""; 
				$CheckingID  = isset($item["CheckingID"])?$item["CheckingID"]:""; 
				// $AppId == isset($item["AppId"])?$item["AppId"]:""; 
				$grantUrl = "";
				if(empty($AppName)){
					$UpdateDt = "";
				}
				echo sprintf($rowsHtmlTag,$PhoneNo,
					$CouponIdx,$CreateDt,$DrawDt,$isUsed,$AppName,
					$UpdateDt,$GameAmount,$TransactDt,$CheckingID);
			}
			?>
			</tbody>
		</table>
		<?php echo $page;?>
	</div>
</div>
<script type="text/javascript">

$(".dateTimeInputBind").each(function(){
	$(this).datetimepicker({
		dateFormat: "yy-mm-dd",
		timeFormat: 'HH:mm:ss',
		stepHour: 1,        
		stepMinute: 1,        
		stepSecond: 3,
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



$("#btn_down").click(function(){
	document.frm.action="/qAppCoupon/downloadCoupon";
	document.frm.submit();
});
$("#btn_query").click(function(){
	document.frm.action="/qAppCoupon/query";
	document.frm.submit();
});

</script>
