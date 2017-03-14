<div class="btn-toolbar">
	<a href="/fAppPromoterAgency/add" class="btn btn-primary"><i class="icon-plus"></i>新增商户</a>
	<a href="/fAppPromoterAgency/docIndex" class="btn btn-primary"><i class="icon-picture"></i>大商户游戏资料下载</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" class="in collapse">
<form class="form_search"  action="/fAppPromoterAgency/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<table class="tableApp" style="width:815px;">
			<tr>
				<td style="width:120px;">商户名称：</td>
				<td><input type='text' maxlength='20'  name="AgencyName" value="<?php echo $AgencyName;?>" /></td>
				<td style="width:120px;">商户编号：</td>
				<td><input type='text' maxlength='20' name="AgencyCode"  value="<?php echo $AgencyCode;?>" /></td>
			</tr>
			<tr>
				<td>联系电话</td>
				<td><input type='text' maxlength='20'  name="Telphone" value="<?php echo $Telphone;?>" /></td>
				<td>渠道类型</td>
				<td>
					<select name="ChannelType">
						<option value=""  <?php echo empty($ChannelType)?" selected ":"";?>>全部</option>
						<option value="1" <?php echo $ChannelType==1?" selected ":"";?>>批次号</option>
						<option value="2" <?php echo $ChannelType==2?" selected ":"";?>>手机号</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>登录限制</td>
				<td>
					<select name=IsFrozen>
						<option value=""    <?php echo empty($IsFrozen)?" selected ":"";?>>全部</option>
						<option value="-1" <?php echo $IsFrozen==-1?" selected ":"";?>>允许登录</option>
						<option value="1"  <?php echo $IsFrozen==1?" selected ":"";?>>禁止登录</option>
					</select>
				</td>
				<td>显示排序</td>
				<td>
					<select name="OrderField">
						<option value="a.CreateDt" <?php echo $OrderField=="a.CreateDt"?" selected ":"";?>>创建时间：近→远</option>
						<option value="b.Amount" <?php echo $OrderField=="b.Amount"?" selected ":"";?>>获得返利：高→低</option>
						<option value="a.MaxPromoterNum" <?php echo $OrderField=="a.MaxPromoterNum"?" selected ":"";?>>渠道数量：多→少</option>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td style="text-align:right;">
					<input type="hidden" name="search" value="1" >
					<input type="hidden" name="export" value="0" id="export">
					<button type="submit" class="btn btn-primary">查找</button>&nbsp;&nbsp;
				</td>
				<td>&nbsp;&nbsp;
				<?php
					if(count($agency)>0){ 
				?>
					<button type="button" class="btn btn-primary" onclick="javascript:exportCSV();">导出到CSV</button>
				<?php
					} 
				?>
				</td>
				<td></td>
			</tr>
		</table>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">大商户用户列表</a>
	<div id="page-stats" class="block-body collapse in" style="padding-left:0px;padding-right:0px;">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:85px;">商户名称</th>
					<th style="width:60px;">商户编号</th>
					<th style="width:35px;">渠道类型</th>
					<th style="width:35px;text-align:right">渠道数量</th>
					<th style="width:48px;text-align:right">累计充值</th>
					<th style="width:48px;text-align:right">累计返利</th>
					<th style="width:95px;">联系电话</th>
					<th style="width:42px">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>			
	<td style="text-align:right">%s</td>			
	<td style="text-align:right">%s</td>
	<td style="text-align:right">%s</td>
	<td>%s</td>							
	<td>
		<a href="/fAppPromoterAgency/modify?AgencyId=%s" title="修改商户信息"><i class="icon-pencil"></i></a>&nbsp;
		<a href="/fAppPromoterAgency/amount?AgencyId=%s" title="查看商户账单信息"><i class="icon-th-list"></i></a>&nbsp;
		<a href="/fAppPromoterAgency/mdyPwd?AgencyId=%s" title="修改商户登录密码"><i class="icon-user"></i></a>&nbsp;
		%s
	</td>
<tr>
EndOfRowTag;
			foreach ($agency as $row){
				$GameAmount= isset($row["GameAmount"])?$row["GameAmount"]:0;
				$Amount= isset($row["Amount"])?$row["Amount"]:0;
				$loginHtml="";
				if($row["IsFrozen"]==1){
					$loginHtml=sprintf("<a href=\"/fAppPromoterAgency/mdyLoginState?AgencyId=%s&IsFrozen=0\" title=\"允许商户登录\" style=\"color:red;\"><i class=\"icon-lock\"></i></a>",$row["AgencyId"]);
				}else{
					$loginHtml=sprintf("<a href=\"/fAppPromoterAgency/mdyLoginState?AgencyId=%s&IsFrozen=1\" title=\"禁止商户登录\"><i class=\"icon-lock\"></i></a>",$row["AgencyId"]);
				}
				echo sprintf($rowsHtmlTag,$row["Name"],$row["Code"],
						($row["ChannelType"]==1?"批次号":($row["ChannelType"]==2?"手机号":"")),
						$row["MaxPromoterNum"],
						number_format($GameAmount,2,".",","),
						number_format($Amount,2,".",","),
						$row["Telphone"],
						$row["AgencyId"],$row["AgencyId"],$row["AgencyId"],$loginHtml);
			}
			?>
			</tbody>
		</table>
		<?php echo $page;?>
	</div>
</div>
<script type="text/javascript">
function exportCSV()
{
	$("#export").val(1);
	$(".form_search").submit();
	$("#export").val(0);
}
</script>
