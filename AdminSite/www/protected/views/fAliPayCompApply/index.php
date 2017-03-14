<div class="btn-toolbar">
	<a href="/fAliPayCompApply/index?State=3" class="btn btn-primary">提交到支付宝</a>
	<a href="/fAliPayCompApply/index?State=4" class="btn btn-primary">支付宝转账成功</a>
	<a href="/fAliPayCompApply/index?State=5" class="btn btn-primary">支付宝转账失败</a>
	<a href="/fAliPayCompApply/testComTrans?d=1" class="btn btn-primary">测试分账数据</a>
</div>
<div class="block">
	<div id="page-stats" class="block-body collapse in">
		<form method="post" action="" id="batch">
		<table class="table table-striped">
			<thead>
				<tr>
		          	<th>流水号</th>
					<th>转出账户</th>
					<th>转入账户</th>
					<th>转账金额</th>
					<th>申请时间</th>
					<th>回调时间</th>
					<th>状态</th>
				</tr>
			</thead>
			<tbody>
			<?php				
			 foreach ($CompAlipayApply as $item){
			?>
				<tr>
		          	<td><?php echo $item['BatchNo'];?></td>
					<td><?php echo $item['OutAccount'];?></td>
					<td><?php echo $item['InAccount'];?></td>
					<td><?php echo $item['Amount'];?>元</td>
					<td><?php echo $item['CreateDt'];?></td>
					<td><?php echo $item['ReplyDt'];?></td>
					<td>
						<?php 
							if(3 == $item["State"]){
								echo "<font color=\"green\">提交至支付宝</font>";
							}else if(4 == $item["State"]){
								echo "<font color=\"green\">支付宝转账成功</font>";
							}else if(5 == $item["State"]){
								echo "<font color=\"red\">支付宝转账失败</font>";
							}
						?>
					</td>
		          	
				<tr>
			<?php
			}
			?>
			</tbody>
		</table>
		<?php echo $page;?>
		</form>
	</div>
</div>
