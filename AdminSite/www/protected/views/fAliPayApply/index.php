<div class="btn-toolbar">
	<a href="/fAliPayApply/index?State=0" class="btn btn-primary">未审核</a>
	<a href="/fAliPayApply/index?State=2" class="btn btn-primary">已通过</a>
	<a href="/fAliPayApply/index?State=1" class="btn btn-primary">未通过</a>
	<a href="/fAliPayApply/index?State=3" class="btn btn-primary">已提交至支付宝</a>
	<a href="/fAliPayApply/index?State=4" class="btn btn-primary">支付宝转账成功</a>
	<a href="/fAliPayApply/index?State=5" class="btn btn-primary">支付宝转账失败</a>
</div>

<div class="block">
	<div class="navbar">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="#" style="color:#fff">提现列表</a>
          <div class="nav-collapse collapse navbar-responsive-collapse">
<?php

$batchButton=<<<EndOfButtonHtmlTag
<ul class="nav pull-right">
	<li><a href="javascript:void(0);" onclick="jumpUrl('/fAliPayApply/batch?State=2')">批量通过</a></li>
</ul>
EndOfButtonHtmlTag;
          	if (0 == $state) {
				echo $batchButton;
			}
?>
          </div>
        </div>
      </div>
    </div>
    
	<div id="search" class="block" style="padding-top:7px;padding-left:5px;">
		<form class="form_search"  method="post" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px">
				<table>
					<tr>
						<td><input type="text" name="PhoneNo" value="<?php echo $PhoneNo;?>" placeholder="输入推广员手机号码" ></td>
						<td><input type='text' name="startDt" value="<?php echo $startDt;?>" maxlength='30' class='dateInputBind start'  placeholder="输入开始申请时间"/></td>
						<td><input type='text' name="endDt" value="<?php echo $endDt;?>" maxlength='30' class='dateInputBind end'  placeholder="输入结束申请时间"/></td>
						<td style="vertical-align:sub;"><button type="submit" class="btn btn-primary" >检索</button></td>
					</tr>
				</table>
			</div>
			<div style="clear:both;"></div>
			<input type="hidden" name="search" value="1" >
		</form>
	</div>    
    
	<div id="page-stats" class="block-body collapse in">
		<form method="post" action="" id="batch">
		<table class="table table-striped">
			<thead>
				<tr>
<?php
$thStateZeroHtml=<<<EndOfTHStateZeroTag
<th style="width:20px;"><input type="checkbox" name="selectAll" id="selectAll" value="true" onclick="javascript:chgSelect();"></th>
EndOfTHStateZeroTag;
		          	if (0 == $state) {
						echo $thStateZeroHtml;
		        	}
?>
					<th style="width:80px;">流水</th>
					<th style="width:90px;">推广员</th>
					<th style="width:100px;text-align:right;">提现金额(元)</th>
					<th style="width:75px;text-align:right;">手续费(元)</th>
					<th style="width:140px;">申请时间</th>
					<th style="width:140px;"><?php  echo  (4 == $state || 5 == $state)?"转账时间":"审核时间"; ?></th>
					<th>处理状态</th>
<?php
		          	if (0 == $state) {
						echo "<th width=\"60px\">操作</th>";
		        	}
?>
				</tr>
			</thead>
			<tbody>
<?php	
$trRowHtml=<<<EndOfTrRowTag
<tr>
	%s
	<td>%s</td>
	<td>%s</td>
	<td style="text-align:right;">%s</td>
	<td style="text-align:right;">%s</td>
	<td>%s</td>
	<td>%s</td>
	<td><font color="%s">%s</font></td>
	%s
</tr>
EndOfTrRowTag;
			
			 foreach ($ViewPromoterAlipayApply as $item){
				$firstTd="";
				$lastTd="";
				if (0 == $state) {
					$firstTd=sprintf("<td><input type=\"checkbox\" name=\"ApplyId[]\" value=\"%s\" /></td>",$item['ApplyId']);
					$lastTd=sprintf("<td><a href=\"/fAliPayApply/modify?state=2&ApplyId=%s\" title=\"通过审核\"><i class=\"icon-ok-circle\"></i></a>&nbsp;&nbsp;&nbsp;<a href=\"/fAliPayApply/modify?state=1&ApplyId=%s\" title=\"审核拒绝\"><i class=\"icon-ban-circle\"></i></a></td>",$item['ApplyId'],$item['ApplyId']);
				}
				$color="white";
				$msg="";
				if(0 == $item["State"]){
					$color="blue";
					$msg="未审核";
				}
				else if(1 == $item["State"]){
					$color="red";
					$msg="未通过";
				}else if(2 == $item["State"]){
					$color="green";
					$msg="已通过";
				}else if(4 == $item["State"]){
					$color="green";
					$msg="支付宝转账成功";
				}else if(5 == $item["State"]){
					$color="red";
					$msg=$item["ResultMemo"];
				}				
				
				echo sprintf($trRowHtml,$firstTd,
						$item['ApplyId'],
						$item['PhoneNo'],
						number_format($item['Amount'],2,".",","),
						number_format($item['Fee'],2,".",","),
						$item['CreateDt'],
						(4 == $state || 5 == $state)?$item['ReplyDt']:$item['ApprovalDt'],
						$color,$msg,
						$lastTd
					);
			}
?>			
			
			</tbody>
		</table>
		<?php echo $page;?>
		</form>
	</div>
</div>

<script>
$(".dateInputBind").each(function(){
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
			
function chgSelect() {
	if(true == $('#selectAll').attr("checked") || 'checked' == $('#selectAll').attr("checked")) {
		$("input[name='ApplyId[]']").each(function() { 
			$(this).attr("checked", true);
		});
	} else {
		$("input[name='ApplyId[]']").each(function() { 
			$(this).attr("checked", false); 
		});
	}
}
function jumpUrl(url) {
	$("#batch").attr('action', url);
	$("#batch").submit();
}
</script>
