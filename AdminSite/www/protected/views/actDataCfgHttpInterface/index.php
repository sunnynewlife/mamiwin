<div class="btn-toolbar">
	<a href="/actDataCfgHttpInterface/add" class="btn btn-primary"><i class="icon-plus"></i>外部系统访问定义</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">游戏接入列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>系统名称</th>
					<th>响应类型</th>
					<th>MD5签名</th>
					<th>接口地址</th>
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
		<a href="/actDataCfgHttpInterface/addInterface?key=%s" title="新增接口"><i class="icon-plus"></i></a>&nbsp;			
		<a href="/actDataCfgHttpInterface/modify?key=%s" title="修改系统"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="/actDataCfgHttpInterface/del?key=%s" title="删除系统"><i class="icon-remove"></i></a>
	</td>
<tr>
EndOfRowTag;
$spanTableHtmlTag=<<<EndofSpanTableHtmlTag
<tr>
	<td>&nbsp;</td>
	<td colspan=4>
		<table style="margin-top:-9px;width:100%s;">
			<tr>
				<td style="background: #4D5B76; color: #FFFFFF;">接口代码</td>
				<td style="background: #4D5B76; color: #FFFFFF;">接口地址</td>
				<td style="background: #4D5B76; color: #FFFFFF;" width="80px">操作</td>
			</tr>
			%s
		</table>
	</td>
</tr>
EndofSpanTableHtmlTag;
$interfaceRowsHtmlTag=<<<EndOfInterfaceRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td>
		<a href="/actDataCfgHttpInterface/modifyInterface?key=%s&interface=%s" title="修改接口"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="/actDataCfgHttpInterface/del?key=%s&interface=%s" title="删除接口"><i class="icon-remove"></i></a>
	</td>
<tr>
EndOfInterfaceRowTag;

			foreach ($interface as $sysKey=> $interInfo){
				$rowHtml=sprintf($rowsHtmlTag,$sysKey,$interInfo["responseType"],$interInfo["needMd5Sign"]=="true"?"需要":"不需要",$interInfo["domain"],$sysKey,$sysKey,$sysKey);
				echo $rowHtml;
				$interfaceRowHtml="";
				foreach ($interInfo["interface"] as $interfaceName => $interfaceArr) {
					$interfaceRowHtml.=sprintf($interfaceRowsHtmlTag,$interfaceName,$interfaceArr["url"],$sysKey,$interfaceName,$sysKey,$interfaceName);
				}
				$interfaceHtml=sprintf($spanTableHtmlTag,"%",$interfaceRowHtml);
				echo $interfaceHtml; 				
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
