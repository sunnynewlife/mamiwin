<div class="btn-toolbar">
	<a href="/actPackageRules/add" class="btn btn-primary"><i class="icon-plus"></i>活动礼包领取规则</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" class="collapse">
<form class="form_search"  action="/actPackageRules/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<input type="text" name="pid" value="" placeholder="输入礼包ID" > 
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">礼包定义列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>记录主键</th>
					<th>礼包</th>
					<th>规则</th>
					<th>附加条件（签到类该值为连续签到次数）</th>
					<th width="80px">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>
		<a href="/actPackageRules/modify?ID=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/actPackageRules/del?ID=%s"></i></a>
	</td>
<tr>
EndOfRowTag;

			 	foreach ($act_package_rules as $p_r){
					$packName="";
					$ruleName="";
					$key="";
					foreach ($PACKAGE_MAP as $packInfo){
						if($packInfo["pid"]==$p_r["pid"]){
							$packName=$packInfo["name"];
							break;
						}
					}
					foreach ($RULE_MAP as $ruleInfo){
						if($ruleInfo["rid"]==$p_r["rid"]){
							$ruleName=$ruleInfo["name"];
							$key=$p_r["conditionReturnValue"];	
							break;
						}
					}
					$rowHtml=sprintf($rowsHtmlTag,$p_r["ID"],$packName,$ruleName,$key,$p_r["ID"],$p_r["ID"]);
					echo $rowHtml; 
				}
			?>
			</tbody>
		</table>
		<?php echo $page;?>
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
