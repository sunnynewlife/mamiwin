<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">领取列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width: 100px;">领奖账号</th>
					<th style="width: 150px;">领奖时间</th>
					<th style="width: 60px;">领取金额</th>
				</tr>
			</thead>
			<tbody>
<?php
$rowsHtmlTag = <<<EndOfRowTag
			<tr>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
			</tr>
EndOfRowTag;
			foreach ($Evn1_list as $item){
				$PhoneNo  = $item["PhoneNo"] ; 
				$ActId  = $item["ActId"] ;
				$CreateDt  = $item["CreateDt"] ; 
				$Amount  = $item["Amount"] ; 
				echo sprintf($rowsHtmlTag,
					$PhoneNo,$CreateDt,$Amount);
			}
			?>
			</tbody>
		</table>
		<?php echo $page;?>
	</div>
</div>
