<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">红包订单信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" enctype="multipart/form-data">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">选择订单文件</td>
						<td><input type="file"  enctype="multipart/form-data" name="EnvelopeIdFile" class="input-xlarge" required="true" autofocus="true" ></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<input type="hidden" name="submit" value="1" />
							<div class="btn-toolbar">
								<button type="submit" class="btn btn-primary">
									<strong>导入退回的红包订单</strong>
								</button>
							</div>
						</td>
					</tr>
				</table>
			</form>
			<table class="tableApp" style="width:815px;display:<?php echo $show_result=="1"?"":"none";?>;">
				<tr>
					<td style="width:120px; text-align:right;">本次导入结果</td>
					<td style="width:40px;"></td>
					<td>文件中订单个数: <font color=red><?php echo $file_count;?></font>个，有效处理退回订单：<font color=red><?php echo $import_count;?></font>个。</td>
				</tr>
			</table>
		</div>
	</div>
</div>
