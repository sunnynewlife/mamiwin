<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">账单查询</a>
	<div id="search" class="in collapse">
		<form class="form_search" action="/fAppPromoterAgency/amount?AgencyId=<?php echo $AgencyId;?>" method="POST" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">商户名称:</td>
						<td><input type='text' maxlength='20'  value="<?php echo $Agency["Name"];?>"  readonly /></td>
						<td style="width:120px;">联系电话:</td>
						<td><input type='text' maxlength='20' value="<?php echo $Agency["Telphone"];?>"  readonly /></td>
					</tr>
					<tr>
						<td>登录账号:</td>
						<td><input type='text' maxlength='20' value="<?php echo $Agency["LoginName"];?>"  readonly /></td>
						<td >合作期限:</td>
						<td><input type='text' maxlength='20'  value="<?php echo str_replace("-","/", substr($Agency["BeginDt"], 0,10))."-".str_replace("-","/", substr($Agency["EndDt"], 0,10));?>"  readonly /></td>
					</tr>
					<tr>
						<td>渠道类型:</td>
						<td><input type='text' maxlength='20' value="<?php echo ($Agency["ChannelType"]==1?"批次号":($Agency["ChannelType"]==2?"手机号":"")); ?>"  readonly /></td>
						<td >渠道数量:</td>
						<td><input type='text' maxlength='20' value="<?php echo $Agency["MaxPromoterNum"];?>"  readonly /></td>
					</tr>
					<tr>
						<td>累计充值:</td>
						<td><input type='text' maxlength='20' value="<?php echo number_format($AgencyAmount["GameAmount"],2,".",",");?>"  readonly /></td>
						<td >累计返利:</td>
						<td><input type='text' maxlength='20' value="<?php echo number_format(bcadd($AgencyAmount["NetAmount"],$AgencyAmount["TaxFee"],2),2,".",",");?>"  readonly /></td>
					</tr>
					<tr>
						<td style="width:120px;">开始时间：</td>
						<td><input type='text' maxlength='20' class='dateInputBind start' name="startDt" value="<?php echo $startDt; ?>" required /></td>
						<td style="width:120px;">结束时间：</td>
						<td><input type='text' maxlength='20' class='dateInputBind end' name="endDt"  value="<?php echo $endDt;?>" required  /></td>
					</tr>
					<tr>
						<td>游戏用户手机号：</td>
						<td><input type='text' maxlength='20'  name="Phone" value="<?php echo $Phone;?>" /></td>
						<td>游戏:</td>
						<td>
							<select name="AppId">
								<option value="" <?php echo empty($AppId)?" selected ":"";?>>全部游戏</option>
							<?php
								foreach ($GameList as $row){
									echo sprintf("<option value=\"%s\" %s>%s</option>",$row["AppId"], ($row["AppId"]==$AppId?" selected ":""), $row["AppName"]);
								} 
							?>														
							</select>
						</td>						
					</tr>
					<tr>
						<td>批次号/手机号：</td>
						<td>
							<select name="PromoterPhone">
								<option value="" <?php echo empty($PromoterPhone)?" selected ":"";?>>全部批次/手机号</option>
							<?php
								foreach ($PromoterList as $row){
									echo sprintf("<option value=\"%s\" %s>%s</option>",$row["PhoneNo"],($row["PhoneNo"]==$PromoterPhone?" selected ":""),$row["PhoneNo"]);
								} 
							?>
							</select>	
						</td>
						<td colspan=2>
							<button type="submit" class="btn btn-primary" onclick="queryList('Pay');">充值查询</button>&nbsp;&nbsp;&nbsp;&nbsp;
							<button type="submit" class="btn btn-primary" onclick="queryList('Login');">登录查询</button>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
						if(count($PayList)>0){
							echo "<button type=\"button\" class=\"btn btn-primary\" onclick=\"exportCSV();\">导出</button>";
						} 
?>						
						</td>
					</tr>
				</table>			
			</div>
			<div style="clear:both;"></div>
			<input type="hidden" name="search" value="1" >
			<input type="hidden" name="queryType" id="queryType" value="<?php echo $queryType;?>" >
			<input type="hidden" name="export" value="0" id="export">
		</form>
	</div>
	
	
	<div id="page-stats" class="block-body collapse in">
<?php
$tablePayHTMLHead=<<<EndOfTablePayHead
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:130px;">分红用户账号</th>
					<th style="width:140px;">充值时间</th>
					<th style="width:80px;text-align:right;">充值金额</th>
					<th>充值游戏</th>
					<th style="width:60px;">返利比例</th>
					<th style="width:80px;text-align:right;">返利金额</th>
					<th style="width:90px;">返利归属</th>
				</tr>
			</thead>
			<tbody>
EndOfTablePayHead;

$tableLoginHTMLHead=<<<EndOfTableLoginHead
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:130px;">分红用户账</th>
					<th style="width:90px;">所属推广员</th>
					<th style="width:140px;">登录游戏</th>
					<th style="width:80px;">登录时间</th>
				</tr>
			</thead>
			<tbody>
EndOfTableLoginHead;

$rowsPayHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td style="text-align:right;">%s</td>
	<td>%s</td>
	<td>%s</td>
	<td style="text-align:right;">%s</td>
	<td>%s</td>			
<tr>
EndOfRowTag;

$rowsLoginHtmlTag=<<<EndOfLoingRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
<tr>
EndOfLoingRowTag;

		if(count($PayList)>0 || $ShowTableHead){
			echo ($queryType=="Pay"?$tablePayHTMLHead:$tableLoginHTMLHead);
		}
		if($queryType=="Pay"){
			foreach ($PayList as $rowItem){
				$Amount=0.0;
				if(empty($rowItem["Amount"])==false){
					$Amount+=$rowItem["Amount"];
				}
				if(empty($rowItem["TaxFee"])==false){
					$Amount+=$rowItem["TaxFee"];
				}
				echo sprintf($rowsPayHtmlTag,
						$rowItem["PlayPhone"],
						$rowItem["TransactDt"],
						empty($rowItem["GameAmount"])?"0.00":number_format($rowItem["GameAmount"],2,".",","),
						$rowItem["AppName"],
						number_format(bcmul($rowItem["PromoterProrate"], "100"),0,"",",")."%",
						number_format($Amount,2,".",","),
						$rowItem["PhoneNo"]
				);
			}
		}else{
			foreach ($PayList as $rowItem){
				echo sprintf($rowsLoginHtmlTag,
						$rowItem["PlayerPhoneNo"],
						$rowItem["PhoneNo"],
						$rowItem["AppName"],
						$rowItem["loginDt"]);
			}
		}
		if(count($PayList)>0 || $ShowTableHead){
				echo "</tbody></table>";
		}
?>				
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
function queryList(queryType)
{
	$("#queryType").val(queryType);
	$(".form_search").submit();
}
</script>