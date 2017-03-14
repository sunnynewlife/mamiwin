<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">红包领取状态信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<table class="tableApp" style="width:815px;">
				<tr>
					<td style="width:120px;">查询结果</td>
					<td><?php echo $QueryResult;?></td>
				</tr>
				<tr>
					<td style="width:120px;">红包订单ID</td>
					<td><?php echo $EnvelopeId;?></td>
				</tr>
				<tr>
					<td style="width:120px;">红包金额</td>
					<td><?php echo $Total_amount;?></td>
				</tr>
				<?php if(count($Detail)>0) {
					$sendStatus="";
					$RecTime="";
					$RefundTime="";
					switch($Detail["status"]){
						case "SENDING":
							$sendStatus="发放中";
							break;
						case "SENT":
							$sendStatus="已发放待领取";
							break;
						case "FAILED":
							$sendStatus="发放失败";
							break;
						case "RECEIVED":
							$sendStatus="已领取";
							break;
						case "REFUND":
							$sendStatus="已退款";
							break;
						default:
							break;
					}
					echo sprintf("<tr><td>领取信息</td><td>%s %s %s %s</td></tr>",
							$sendStatus,"",$RecTime,$RefundTime);
					
				}?>
				
			</table>
		</div>
	</div>
</div>
