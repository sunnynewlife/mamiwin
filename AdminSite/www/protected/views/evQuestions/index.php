<div class="btn-toolbar">
	<a href="/evQuestions/add" class="btn btn-primary"><i class="icon-plus"></i>新增评测题</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">评测题查看列表</a>
	<div id="search" class="in collapse">
		<form class="form_search"  method="POST" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp">
					<tr>
						<td >题目</td>
						<td colspan=5>
							<input type="text" maxlength="50" name="Question_Stems" value="" class="input-xlarge"  autofocus="true" style="width:530px;" />
						</td>											
					</tr>
					
				</table>			
				<input type="hidden" name="search" value="1" >
			</div>
			<div style="clear:both;"></div>
		</form>
	</div>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>题目ID</th>
					<th>题集</th>
					<th>题目</th>
					<th>选项A</th>
					<th>选项B</th>
					<th>选项C</th>
					<th>选项D</th>
					<th>A分值</th>
					<th>B分值</th>					
					<th>C分值</th>
					<th>D分值</th>
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
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>				
	<td>
		<a href="/evQuestions/modify?IDX=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除这条任务记录"><i class="icon-remove" href="/evQuestions/del?IDX=%s"></i></a>&nbsp;		
	</td>
<tr>
EndOfRowTag;

			$titleLen=20;
			foreach ($Evaluation_Questions as $item){
				$Question_Set=$item["Question_Set_IDX"];
				$title=$item["Question_Stems"];
				$Option_A=$item["Option_A"];
				$Option_B=$item["Option_B"];
				$Option_C=$item["Option_C"];
				$Option_D=$item["Option_D"];
				$Point_A=$item["Point_A"];
				$Point_B=$item["Point_B"];
				$Point_C=$item["Point_C"];
				$Point_D=$item["Point_D"];
				if(mb_strlen($title,"utf-8")>$titleLen){
					$title=mb_substr($title, 0,$titleLen,"utf-8")."..";
				}
				echo sprintf($rowsHtmlTag,
					$item["IDX"],
					$Question_Set,
					$title,
					$Option_A,
					$Option_B,
					$Option_C,
					$Option_D,
					$Point_A,
					$Point_B,
					$Point_C,
					$Point_D,
					$item["IDX"],$item["IDX"]			
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
			bootbox.confirm('确定要删除这个评测题吗？', 
					function(result) 
					{
						if(result){
							location.replace(href);
						}
					});		
		});
</script>
