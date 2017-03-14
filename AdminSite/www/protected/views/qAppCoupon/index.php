<div class="btn-toolbar">
	<a href="/qAppCoupon/add" class="btn btn-primary"><i class="icon-plus"></i>新建礼券</a>
</div>
<div id="search" class="in collapse">
	<form class="form_search" action="/qAppCoupon/index" method="POST" style="margin-bottom: 0px">
		<div style="float: left; margin-right: 5px; margin-left: 15px; margin-top: 25px;">
			<table class="tableApp" style="width: 815px;" border="0">
				<tr>
					<td style="width: 120px;">使用范围：</td>
					<td><input type="text" maxlength="50" name="AppName" id="AppName" value="<?php echo $AppName;?>" onclick="javascript:selectApp();"  autofocus="true"  /></td>
					<td style="width: 120px;">有效期：</td>
					<td>
						<select name="IsExpired">
							<option value="" <?php echo empty($IsExpired)?" selected":""; ?>>全部</option>
							<option value="1" <?php echo $IsExpired=="1"?" selected":""; ?>>未过期</option>
							<option value="2" <?php echo $IsExpired=="2"?" selected":""; ?>>已过期</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>礼券类型:</td>
					<td>
						<select name="Type">
							<option value="" <?php echo empty($Type)?" selected":""; ?>>全部</option>
							<option value="1" <?php echo $Type=="1"?" selected":""; ?>>通用礼券</option>
							<option value="2" <?php echo $Type=="2"?" selected":""; ?>>专属礼券</option>
						</select>
					</td>
					<td>礼券状态:</td>
					<td>
						<select name="Status">
							<option value="" <?php echo empty($Status)?" selected":""; ?>>全部</option>
							<option value="1" <?php echo $Status=="1"?" selected":""; ?>>在线</option>
							<option value="2" <?php echo $Status=="2"?" selected":""; ?>>白名单可见</option>
							<option value="3" <?php echo $Status=="3"?" selected":""; ?>>下线</option>
						</select>
					</td>
					
				</tr>
				<tr>
					<td>礼券ID：</td>
					<td><input type='text' maxlength='20' name="CouponIdx" value="<?php echo $CouponIdx;?>" /></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</div>
		<div class="btn-toolbar"
			style="padding-top: 25px; padding-bottom: 0px; margin-bottom: 0px">
			<input type="hidden" name="search" value="1">
			<input type="hidden" name="AppId" Id="AppId" value="<?php echo $AppId;?>" />
			<button type="submit" class="btn btn-primary">查询</button>
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
					<th style="width: 60px;">礼券ID</th>
					<th style="width: 60px;">礼券类型</th>
					<th style="width: 60px;">礼券名称</th>
					<th style="width: 150px;">使用范围</th>
					<th style="width: 100px;">状态</th>
					<th style="width: 80px;" >有效期</th>
					<th style="width: 100px;" >发送上限/已发送</th>
					<th style="width: 100px;" >已领取/已兑现</th>
					<th style="width: 40px;" >操作</th>
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
					<td>%s/%s</td>
					<td>%s/%s</td>
					<td>
						<a href="/qAppCoupon/modify?idx=%s" title="修改礼券"><i class="icon-pencil"></i></a>&nbsp;
						%s&nbsp;						
						<a href="/qAppCoupon/download?idx=%s" title="数据下载"><i class="icon-download"></i></a>&nbsp;
					</td>
			</tr>
EndOfRowTag;
			foreach ($Coupon_list as $item){
				$isExpired  = $item["IsExpired"] ; 
				$Status  = $item["Status"] ; 
				$CouponName  = $item["CouponName"] ; 
				$Quantity  = isset($item["Quantity"])?$item["Quantity"]:0 ; 
				$GrantCount  = isset($item["GrantCount"])?$item["GrantCount"]:0 ; 
				$DrawCount  = isset($item["DrawCount"])?$item["DrawCount"]:0 ; 
				$CashCount  = isset($item["CashCount"])?$item["CashCount"]:0 ; 
				$grantUrl = "";
				if(($isExpired == "1" || $isExpired == "0" ) && ($Status == 1 || $Status == 2) && $Quantity > 0 && $Quantity > $GrantCount){
					$grantUrl = '<a href="/qAppCoupon/upload?idx='. $item['IDX'].'" title="发券"><i class="icon-upload"></i></a>';
				}
				echo sprintf($rowsHtmlTag,$item["IDX"],
					$item["Type"]=="1"?"通用":"专属",
					// $item["AppIds"],
					$CouponName,$item["AppNames"],
					$item["Status"]=="1"?"在线":($item["Status"]=="2"?"白名单可见":"下线"),
					$isExpired=="0"?"永久有效":($isExpired=="1"?"未过期":"已过期"),
					$Quantity,$GrantCount,$DrawCount,$CashCount,
					$item["IDX"],$grantUrl,$item["IDX"]);

			}
			?>
			</tbody>
		</table>
		<?php echo $page;?>
	</div>
</div>
<?php $this->widget('application.widget.AppSelector'); ?>
<script type="text/javascript">
function selectApp()
{
	showAppSelector(saveAppCallback);	
}
function saveAppCallback(pvAppId,pvAppName,pvProrate)
{
	$("#AppId").val(pvAppId);	
	$("#AppName").val(pvAppName);	
}
</script>
