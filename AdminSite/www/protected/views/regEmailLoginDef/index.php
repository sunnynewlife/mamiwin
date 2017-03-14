<div class="btn-toolbar">
	<a href="/regEmailLoginDef/add" class="btn btn-primary"><i class="icon-plus"></i>增加邮件登录地址定义</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>	
</div>
<div id="search" class="collapse">
<form class="form_search"  action="/regEmailLoginDef/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<input type="text" name="server_domain" value="" placeholder="输入Server Domain" > 
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">注册邮件登录地址定义列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>IDX</th>
					<th>Server Domain</th>
					<th>登录地址</th>
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
	<td>
		<a href="/regEmailLoginDef/modify?idx=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/regEmailLoginDef/del?idx=%s"></i></a>
	</td>
<tr>
EndOfRowTag;

			 	foreach ($reg_email_login as $item){

					$rowHtml=sprintf($rowsHtmlTag,$item["idx"],
						$item["server_domain"],
						$item["server_login_url"],
						$item["idx"],$item["idx"]);
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
