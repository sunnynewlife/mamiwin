<div class="btn-toolbar">
	<a href="/userInfo/add" class="btn btn-primary"><i class="icon-plus"></i>新增用户</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">注册用户列表</a>
	<div id="search" class="in collapse">
		
	</div>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="60px;">ID</th>
					<th width="40px;">登录手机号</th>
					<th width="60px;">第三方账号</th>
					<th width="200px;">经验值</th>
					<th width="60px">注册时间</th>
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
	<td>%s</td>		
<tr>
EndOfRowTag;
			$titleLen=20;
			foreach ($User_Info as $item){
				$LoginName=$item["LoginName"];
				$AcctSource=$item["AcctSource"];
				$Exp_Points=$item["Exp_Points"];
				$Create_Time=$item["Create_Time"];
				$IDX=$item["IDX"];
				
				echo sprintf($rowsHtmlTag,
					$IDX,
					$LoginName,
					$AcctSource,
					// DictionaryData::User_Share_To_Type[$AcctSource],
					$Exp_Points,
					$Create_Time
				);
			}
			?>
			</tbody>
		</table>
		<?php echo $page;?>
	</div>
</div>
