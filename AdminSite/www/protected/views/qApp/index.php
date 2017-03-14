<div class="btn-toolbar">
	<a href="/qApp/add" class="btn btn-primary"><i class="icon-plus"></i>新建微信游戏</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">活动列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width: 110px;">游戏名称</th>
					<th style="width: 60px;">排列顺序</th>
					<th style="width: 90px;">上线状态</th>
					<th style="width: 60px;text-align:right;">基础返利</th>
					<th style="width: 60px;text-align:right;">限时高返</th>
					<th style="width: 110px;text-align:right;" >累计充值</th>
					<th style="width: 110px;text-align:right;" >累计返利</th>
					<th style="width: 154px;" >标签</th>
					<th width="60px">操作</th>
				</tr>
			</thead>
			<tbody>
<?php
$rowsHtmlTag = <<<EndOfRowTag
			<tr>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td style="text-align:right;">%s</td>
				<td style="text-align:right;">%s</td>
				<td style="text-align:right;">%s</td>
				<td style="text-align:right;">%s</td>
				<td>%s</td>
				<td>
					<a href="/qApp/modify?IDX=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
					<a href="/fAppPromoterAgency/amount?AgencyId=%s&AppId=%s" title="收入明细"><i class="icon-th-list"></i></a>&nbsp;
					<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/qApp/del?IDX=%s"></i></a>
				</td>
			</tr>
EndOfRowTag;

			foreach ($qApps as $qApp){
				$status= ($qApp["Status"]=="1"?"在线":($qApp["Status"]=="2"?"白名单可见":"下线"));
				$PromoteProrate=$qApp["PromoteProrate"];
				$PromoteDt=$qApp["PromoteDt"];
				$TagStr="";
				
				if($qApp["HavingEvn"]=="1"){
					$TagStr.=" 活动";
				}				
				if($qApp["HavingGift"]=="1"){
					$TagStr.=" 礼包";
				}
				if($qApp["IsFirstPublish"]=="1"){
					$TagStr.=" 首发";
				}
				if($qApp["IsSinglePublish"]=="1"){
					$TagStr.=" 独家";
				}
				if($qApp["IsOtherPublish"]=="1"){
					$TagStr.=" 其他";
				}
				$TagStr=substr($TagStr, 1);
				
				if(empty($PromoteProrate)){
					$PromoteDt="";
				}else{
					$PromoteProrate=bcmul($PromoteProrate, "100",0)."%";
				}
				echo sprintf($rowsHtmlTag,$qApp["AppName"],$qApp["AppOrder"],$status,bcmul($qApp["BaseProrate"], "100",0)."%",
							$PromoteProrate,
							$qApp["GameAmount"],$qApp["Amount"],
							$TagStr,
							$qApp["IDX"],
							$qApp["AgencyId"],$qApp["AppId"],						
							$qApp["IDX"]);				
			}

			?>
			</tbody>
		</table>
	</div>
</div>
<script>
	$('.icon-remove').click(
		function() 
		{
			var href=$(this).attr('href');
			bootbox.confirm('确定要这样做吗？', 
					function(result) 
					{
						if(result){
							location.replace(href);
						}
					});		
		});
</script>
