<div class="btn-toolbar">
	<a href="/fAliPayCompApply/index?State=3" class="btn btn-primary">提交到支付宝</a>
	<a href="/fAliPayCompApply/index?State=4" class="btn btn-primary">支付宝转账成功</a>
	<a href="/fAliPayCompApply/index?State=5" class="btn btn-primary">支付宝转账失败</a>
	<a href="/fAliPayCompApply/testComTrans?d=1" class="btn btn-primary">测试分账数据</a>
</div>
<div class="block">
<div id="search" class="in collapse">
		<form class="form_search"  id="payListForm"  method="POST" style="margin-bottom:0px" action="/fAliPayCompApply/testComTrans">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
				
					<tr>
						<td style="width:120px;">查询几天前数据</td>
						<td><input type='text' maxlength='20' class='dateInputBind start' name="d" value="<?php echo $queryDate; ?>" /></td>
					</tr>
					
					<tr>
						<td></td>
						<td><button type="submit" class="btn btn-primary">查  询</button></td>
						
					</tr>										
				</table>			
			</div>
			<div style="clear:both;"></div>
			<input type="hidden" name="search" value="1" >
		</form>
	</div>
	
    
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
		          	<th>充值金额(A)</th>
					<th>厂商金额(A-)</th>
					<th>平台(B)</th>
					<th>转GBAO(D)</th>
					<th>大客户(C)</th>
				</tr>
                <tbody>
                <tr>
		          	<td><?php echo $transInfo['totalAmounts'];?></td>
                    <td><?php echo $transInfo['appAmounts'];?></td>
                    <td><?php echo $transInfo['transBAmounts'];?></td>
                    <td><?php echo $transInfo['transDAmounts'];?></td>
                    <td><?php echo $transInfo['transCAmounts'];?></td>
				<tr>
				<tr>
					<td colspan="5">batch_num:<?php echo $transInfo['batchNum'];?></td>
				</tr>
				<tr>
					<td colspan="5"><?php echo $transInfo['detail'];?></td>
				</tr>
				</tbody>
			</thead>
           </table>    
		<form method="post" action="" id="batch">
		<table class="table table-striped">
			<thead>
				<tr>
                    <th>充值ID</th>
		          	<th>充值金额</th>
					<th>转C账户</th>
					<th>转GBAO</th>
					<th>厂商分成基数比例</th>
					<th>厂商分成比例</th>
					<th>厂商分成金额</th>
				</tr>
			</thead>
			<tbody>
            <?php
            if(is_array($transInfo) && array_key_exists("transArray",$transInfo)){
                $totalAmount = 0;
                $totalCAmount = 0;
                $totalDAmount = 0;
                $totalAAmount = 0;
                foreach ($transInfo["transArray"] as $item){
			     //var_dump($item);
			?>
				<tr>
                    <td><?php echo $item['TransactionId'];?></td>
		          	<td><?php echo $item['amount'];?></td>
					<td><?php echo $item['transC'];?></td>
					<td><?php echo $item['transD'];?></td>
					<td><?php echo $item['appCostFeePercent'];?></td>
					<td><?php echo $item['appRate'];?></td>
					<td><?php echo $item['alipayIncome'];?></td>
				<tr>
			<?php
//                $totalAmount = bcadd($totalAmount,$item['amount'],2);
//                $totalCAmount = bcadd($totalCAmount,$item['transC'],2);
//                $totalDAmount = bcadd($totalDAmount,$item['transD'],2);
//                $totalAAmount = bcadd($totalAAmount,$item['alipayIncome'],2);
			 }
            }	
			?>
                <!--tr>
                    <td>合计</td>
		          	<td><?php echo $totalAmount;?></td>
		          	<td><?php echo $totalCAmount;?></td>
		          	<td><?php echo $totalDAmount;?></td>
		          	<td></td>
		          	<td></td>
		          	<td><?php echo $totalAAmount;?></td>
				<tr-->   
                <tr>
                    <td colspan="7"><?php echo($transInfo['detail']);?></td>
                </tr>         
			</tbody>
		</table>
		</form>
	</div>
</div>
