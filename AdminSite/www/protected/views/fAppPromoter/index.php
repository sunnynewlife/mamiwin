<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">推广员基础信息数据</a>
	<div id="search" class="in collapse">
		<form class="form_search"  method="POST" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">推广员账号：</td>
						<td style="width:120px;"><input type='text' maxlength='20'  name="Phone"  required="true" value="<?php echo $Promoter["PhoneNo"];?>"/></td>
						<td><button type="submit" class="btn btn-primary">查  询</button></td>
					</tr>
				</table>			
				<input type="hidden" name="search" value="1" >
			</div>
			<div style="clear:both;"></div>
		</form>
	</div>
	
	<div id="page-stats" class="block-body collapse in">
		<div style="float:left;margin-right:5px;margin-left:5px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:140px;">注册时间：</td>
						<td><?php echo $Promoter["CreateDt"]; ?></td>
						<td style="width:140px;">手机型号</td>
						<td><?php echo $Promoter["PhoneType"]; ?></td>
					</tr>
					<tr>
						<td>最后登录时间：</td>
						<td><?php echo $Promoter["LoginDt"]; ?></td>
						<td>最近一次登录IP:</td>
						<td><?php echo $Promoter["LoginIp"]; ?></td>
					</tr>
					<tr>
						<td>分红用户累计充值：</td>
						<td><?php echo empty($Promoter["GameAmountSummary"])?"":number_format($Promoter["GameAmountSummary"],2,".",","); ?></td>
						<td>累计获得返利:</td>
						<td><?php echo empty($Promoter["IncomeSummary"])?"":number_format($Promoter["IncomeSummary"],2,".",","); ?></td>
					</tr>
					<tr>
						<td>累计提现：</td>
						<td><?php echo empty($Promoter["AliPaySummary"])?"":number_format($Promoter["AliPaySummary"],2,".",","); ?></td>
						<td>账户余额:</td>
						<td><?php echo empty($Promoter["Amount"])?"":number_format($Promoter["Amount"],2,".",","); ?></td>
					</tr>					
					<tr>
						<td>操作：</td>
						<td id="tdPayState">
						<?php
							if($Promoter["PayState"]==1){
								echo sprintf("<a href='#' onclick=\"javscript:changePayState('%s',0);\">冻结</a>",$Promoter["PromoterId"]);
							}else if($Promoter["PayState"]==0 && isset($Promoter["PromoterId"])){
								echo sprintf("<a href='#' onclick=\"javscript:changePayState('%s',1);\" >解除冻结</a>",$Promoter["PromoterId"]);
							}
						?>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
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
					<th>推广游戏</th>
					<th style="width:180px;">申请打包时间</th>
					<th style="width:160px;text-align:right;">累计下载次数</th>
					<th style="width:160px;text-align:right;">下家累计充值</th>
					<th style="width:160px;text-align:right;">累计获得返利</th>
				</tr>
			</thead>
			<tbody>	
<?php 
$rowsHtmlTag=<<<EndOfRowTag
	<tr>
		<td>%s</td>
		<td>%s</td>
		<td style="text-align:right;">%s</td>
		<td style="text-align:right;">%s</td>
		<td style="text-align:right;">%s</td>
	</tr>
EndOfRowTag;
		foreach ($Promoter["GameList"] as $item){
			echo sprintf($rowsHtmlTag,
				$item["AppName"],
				$item["CreateDt"],
				empty($item["DownloadCount"])?"0":number_format($item["DownloadCount"],0,"",","),
				empty($item["GameAmount"])?"0.00":number_format($item["GameAmount"],2,".",","),
				empty($item["TotalAmount"])?"0.00":number_format($item["TotalAmount"],2,".",",")
			);
		}
?>				
			</tbody>
		</table>
		
		<div style="clear:both;"></div>
		
		<label>提现信息</label>
		
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:150px;">提现申请时间</th>
					<th style="width:140px;text-align:right;">提现金额</th>
					<th style="width:150px;">处理时间</th>
					<th>提现状态</th>
				</tr>
			</thead>
			<tbody>
<?php 
$payHtmlTag=<<<EndOfRowTag
	<tr>
		<td>%s</td>
		<td style="text-align:right;">%s</td>
		<td>%s</td>
		<td>%s</td>
	</tr>
EndOfRowTag;
		foreach ($Promoter["AliPayList"] as $item){
			$Amount= (empty($item["Amount"])?0:$item["Amount"])+(empty($item["Fee"])?0:$item["Fee"]);
			$ProcState="";
			$ProcDt="";
			if($item["State"]=="0"){
				$ProcState="待审核";
			}else if($item["State"]=="1"){
				$ProcState="审核不通过";
				$ProcDt=$item["ApprovalDt"];
			}else if($item["State"]=="2"){
				$ProcState="审核通过,待提交到支付宝";
				$ProcDt=$item["ApprovalDt"];
			}else if($item["State"]=="3"){
				$ProcState="已提交到支付宝";
				$ProcDt=$item["SubmitDt"];
			}else if($item["State"]=="4"){
				$ProcState="支付宝已到帐";
				$ProcDt=$item["ReplyDt"];
			}else if($item["State"]=="5"){
				$ProcState="支付宝处理失败";
				$ProcDt=$item["ReplyDt"];
			}
				
			echo sprintf($payHtmlTag,
				$item["CreateDt"],
				number_format($Amount,2,".",","),
				$ProcDt,
				$ProcState
			);
		}
?>				
			
			
			</tbody>
		</table>		
	</div>
</div>
<script type="text/javascript">
function changePayState(PromoterId,State)
{
	var changeStateUrl="/fAppPromoter/payState";
	var PateStateData={"PromoterId":PromoterId,"PayState":State};
	$.ajax(
		{
			url:changeStateUrl,
			data:PateStateData,
			success: function(json){
						if(json.return_code==0){
							var html="<a href='#' onclick=\"javscript:changePayState('"+PromoterId+"',"+(State==1?0:1)+");\">"+(State==1?"冻结":"解除冻结")+"</a>";
							$("#tdPayState").html(html);						
						}else{
							layer.alert(json.return_msg,8);
						}
			         },
			timeout:5000,
			error:function(XMLHttpRequest, textStatus, errorThrown){
						if(textStatus!="success"){
							var errorMsg="textStatus:"+textStatus+" Message:"+errorThrown.message+" Description:"+errorThrown.description;
							layer.alert(errorMsg,8);
						}
					},
			dataType:"json"
		}
	);			
}
</script>