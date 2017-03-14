<div class="btn-toolbar">
	<a href="/qAppPayApply/index?State=0" class="btn btn-primary">未审核</a>
	<a href="/qAppPayApply/index?State=2" class="btn btn-primary">已通过</a>
	<a href="/qAppPayApply/index?State=1" class="btn btn-primary">未通过</a>
	<a href="/qAppPayApply/index?State=3" class="btn btn-primary">红包已退回</a>
	<a href="/qAppPayApply/rollback" class="btn btn-primary">红包过期未领取，红包退回到申请账号</a>
</div>

<div class="block">
	<div class="navbar">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="#" style="color:#fff">红包列表</a>
          <div class="nav-collapse collapse navbar-responsive-collapse">
<?php

$batchButton=<<<EndOfButtonHtmlTag
<ul class="nav pull-right">
	<li><a href="javascript:void(0);" onclick="jumpUrl('/qAppPayApply/batch?State=2')">批量通过</a></li>
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
						<td><input type="text" name="PhoneNo" value="<?php echo $PhoneNo;?>" placeholder="输入玩家手机号码" ></td>
						<td><input type='text' name="startDt" value="<?php echo $startDt;?>" maxlength='30' class='dateInputBind start'  placeholder="输入开始申请时间"/></td>
						<td><input type='text' name="endDt" value="<?php echo $endDt;?>" maxlength='30' class='dateInputBind end'  placeholder="输入结束申请时间"/></td>
						<td><input type='text' name="envelopeId" value="<?php echo $envelopeId;?>" maxlength='512' placeholder="输入红包订单号"/></td>
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
					<th style="width:70px;">流水</th>
					<th style="width:90px;">玩家</th>
					<th style="width:90px;text-align:right;">红包金额(元)</th>
					<th style="width:140px;">申请时间</th>
					<th style="width:140px;">审核时间</th>
					<th style="width:80px;">处理状态</th>
					<th>红包订单号</th>
					<th width="40px">操作</th>
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
		<td>%s</td>
		<td>%s</td>
		<td><font color="%s">%s</font></td>
		<td>%s</td>
		%s
	</tr>			
EndOfTrRowTag;
			 foreach ($ViewQ_PlayerPayApply as $item){
				$firstTd="";
				$lastTd="<td></td>";
				
				$color="white";
				$StatusTxt="";
				
				if (0 == $item["State"]) {
					$firstTd=sprintf("<td><input type=\"checkbox\" name=\"IDX[]\" value=\"%s\" /></td>",$item['IDX']);
					$lastTd=sprintf("<td><a href=\"/qAppPayApply/modify?state=2&IDX=%s\" title=\"通过审核,给用户发送红包\"><i class=\"icon-ok-circle\"></i></a>&nbsp;&nbsp;&nbsp;<a href=\"/qAppPayApply/modify?state=1&IDX=%s\" title=\"审核拒绝\"><i class=\"icon-ban-circle\"></i></a></td>",$item['IDX'],$item['IDX']);
					
					$color		="blue";
					$StatusTxt	="未审核";
				} else if(1 == $item["State"]){
					$color="red";
					$StatusTxt="未通过";
				}else if(2 == $item["State"]){
					$color="green";
					$StatusTxt="已通过";
					if(empty($item["EnvelopeId"])==false && empty($item["OurOrderId"])==false){
						$lastTd=sprintf("<td><a href=\"/qAppPayApply/query?IDX=%s\" title=\"查看红包领取信息状态\"><i class=\"icon-screenshot\"></i></a></td>",$item['IDX']);
					}
				}else if(3 == $item["State"]){
					$color="red";
					$StatusTxt="过期退回";
				}
				echo sprintf($trRowHtml,$firstTd,
						$item['IDX'],$item['PhoneNo'],number_format($item['Amount'],2,".",","),
						$item['CreateDt'],$item['ApprovalDt'],
						$color,$StatusTxt,
						$item['EnvelopeId'],
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
		$("input[name='IDX[]']").each(function() { 
			$(this).attr("checked", true);
		});
	} else {
		$("input[name='IDX[]']").each(function() { 
			$(this).attr("checked", false); 
		});
	}
}
function jumpUrl(url) {
	$("#batch").attr('action', url);
	$("#batch").submit();
}
</script>
