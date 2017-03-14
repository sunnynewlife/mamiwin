<div id="search" >
<form class="form_search"  action="/gData/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>查询条件</label>
		<input type="text" id="start_date" name="date" value="<?php echo Yii::app()->request->getParam('date')?>" placeholder="输入日期" > 
		<input type="text" id="ProductId" name="ProductId" value="<?php echo Yii::app()->request->getParam('ProductId')?>" placeholder="输入ProductId" > 
		<input type="text" id="Phone" name="Phone" value="<?php echo Yii::app()->request->getParam('Phone')?>" placeholder="输入Phone" >
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>ProductId</th>
					<th>LogId</th>
					<th>StepName</th>
					<th>Phone</th>
					<th>SessionId</th>
					<th>ReturnCode</th>
					<th>ReturnMsg</th>
					<th>SystemType</th>
					<th>SystemVersion</th>
					<th>LogTime</th>
					<th>游戏ID</th>
					<th>活动ID</th>
					<th>接口名称</th>
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
	<td>%s</td>
    <td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
<tr>
EndOfRowTag;
 			 	foreach ($gapp_log  as $item){
			 			$rowHtml=sprintf($rowsHtmlTag,
			 					$item["ProductId"],
			 					$item["LogId"],
			 					$item["StepName"],
			 					$item["Phone"],
			 					$item["SessionId"],
			 					$item["ReturnCode"],
			 					$item["ReturnMsg"],
			 					//$item["ReturnComment"],
			 					$item["SystemType"],
			 					$item["SystemVersion"],
			 					$item["LogTime"],
			 					$item["Extend1"],
			 					$item["Extend2"],
			 					$item["Extend3"]
			 					);
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
	jQuery(function () {
		var myDate = new Date();
	    // 时间设置
	    jQuery('#start_date').datetimepicker({
	        timeFormat:"",
	        dateFormat: "yy_mm_dd"
	    });

	});
</script>
