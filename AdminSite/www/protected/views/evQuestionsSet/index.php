<div class="btn-toolbar">
	<a href="/mWFiles/add" class="btn btn-primary"><i class="icon-plus"></i>新增素材</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">素材查看列表</a>
	<div id="search" class="in collapse">
		
	</div>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="60px;">ID</th>
					<th width="90px;">题集名</th>
					<th width="20px;">题目数量</th>
					<th width="60px">操作</th>
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
		<a href="/evQuestionsSet/modify?IDX=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
	</td>
<tr>
EndOfRowTag;
			$titleLen=20;
			foreach ($Evaluation_Questions_Set as $item){
				$Set_Name=$item["Set_Name"];
				if(mb_strlen($Set_Name,"utf-8")>$titleLen){
					$title=mb_substr($Set_Name, 0,$titleLen,"utf-8")."..";
				}
				
				echo sprintf($rowsHtmlTag,
					$item["IDX"],
					// DictionaryData::Task_Material_Child_Gender[$item["Conditon_Child_Gender"]],
					// DictionaryData::Task_Material_Parent_Gender[$item["Condition_Parent_Gender"]],
					$Set_Name,
					$item["Set_Qty"],
					// $item["Set_Type"],
					// $item["Condition_Min_Age"],
					// $item["Condition_Max_age"],
					$item["IDX"]
				);
			}
			?>
			</tbody>
		</table>
		<?php echo $page;?>
	</div>
</div>
<script type="text/javascript">

	$('.icon-remove').click(
		function() 
		{
			var href=$(this).attr('href');
			bootbox.confirm('确定要删除这个素材吗？', 
					function(result) 
					{
						if(result){
							location.replace(href);
						}
					});		
		});
</script>
