<div class="btn-toolbar">
	<a href="/mWMaterial/add" class="btn btn-primary"><i class="icon-plus"></i>新增任务</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">任务查看列表</a>
	<div id="search" class="in collapse">
		<form class="form_search"  method="POST" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp">
					<tr>
						<td style="width:100px;">任务类型</td>
						<td style="width:250px;">
							<select name="Task_Type">
								<option value="">所有</option>
								<option value="1">学习任务</option>
								<option value="2">陪伴任务</option>
							</select>
						</td>
						<td style="width:100px;">学习时间</td>
						<td>
							<input type="text"  name="Min_Time" value="" class="input-xlarge"  autofocus="true"  style="width:50px;"/>
							~
							<input type="text"  name="Max_Time" value="" class="input-xlarge" autofocus="true" style="width:50px;" />
							分钟
						</td>
						<td style="width:100px;">年龄段</td>
						<td>
							<input type="text" maxlength="50" name="Min_Age" value="" class="input-xlarge"  autofocus="true" style="width:50px;" />
							~
							<input type="text" maxlength="50" name="Max_Age" value="" class="input-xlarge" autofocus="true"  style="width:50px;"/>
							岁
						</td>	
					</tr>
					<tr>
						<td>孩子性别</td>
						<td>
							<select name="Child_Gender">
								<option value="">所有</option>
								<option value="1">不限制</option>
								<option value="2">女孩</option>
								<option value="3">男孩</option>
							</select>
						</td>
						<td>父母性别</td>
						<td><select name="Parent_Gender">
								<option value="">所有</option>
								<option value="1">不限制</option>
								<option value="2">母亲</option>
								<option value="3">父亲</option>
							</select>
						</td>
						<td>父母婚姻状况</td>
						<td>
							<select name="Parent_Marriage">
								<option value="">所有</option>
								<option value="1">不限</option>
								<option value="2">单亲</option>					
							</select>
						</td>						
					</tr>
					<tr>
						<td>是否独生</td>
						<td>
							<select name="Only_Children">
								<option value="">所有</option>
								<option value="1">不限</option>
								<option value="2">独生小孩</option>
							</select>	
						</td>
						<td >任务标题</td>
						<td colspan=5>
							<input type="text" maxlength="50" name="Task_Title" value="" class="input-xlarge"  autofocus="true" style="width:530px;" />
						</td>											
					</tr>
					<tr>
						<td>任务状态</td>
						<td>
							<select name="Task_Status">
								<option value="">所有</option>
								<option value="1">未发布</option>
								<option value="2">公开</option>
								<option value="3">灰度</option>
							</select>	
						</td>
						<td colspan=4 ><button type="submit" class="btn btn-primary">查  询</button></td>																				
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
					<th>任务ID</th>
					<th>任务类型</th>
					<th>任务标题</th>
					<th>学习时间</th>
					<th>年龄</th>
					<th>孩子性别</th>
					<th>父母性别</th>
					<th>婚姻状况</th>					
					<th>是否独生</th>
					<th>任务状态</th>
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
	<td>
		<a href="/mWMaterial/modify?IDX=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除这条任务记录"><i class="icon-remove" href="/mWMaterial/del?IDX=%s"></i></a>&nbsp;
		<a href="/mWMaterial/preview?id=%s" target="_blank" title="素材预览" style="display:%s"><i class="icon-eye-open"></i></a>
	</td>
<tr>
EndOfRowTag;

			$titleLen=20;
			foreach ($Task_Material as $item){
				$title=$item["Task_Title"];
				if(mb_strlen($title,"utf-8")>$titleLen){
					$title=mb_substr($title, 0,$titleLen,"utf-8")."..";
				}
				$previewDisplay=($item["Matrial_IDX"]>0?"":"none");
				echo sprintf($rowsHtmlTag,
					$item["IDX"],
					DictionaryData::Task_Material_Task_Type[$item["Task_Type"]],
					$title,
					sprintf("%d-%d分钟",$item["Min_Time"],$item["Max_Time"]),
					sprintf("%d-%d岁",$item["Min_Age"],$item["Max_Age"]),
					DictionaryData::Task_Material_Child_Gender[$item["Child_Gender"]],
					DictionaryData::Task_Material_Parent_Gender[$item["Parent_Gender"]],
					DictionaryData::Task_Material_Parent_Marriage[$item["Parent_Marriage"]],
					DictionaryData::Task_Material_Only_Children[$item["Only_Children"]],
					DictionaryData::Task_Material_Task_Status[$item["Task_Status"]],						
					$item["IDX"],$item["IDX"],
					$item["Matrial_IDX"],$previewDisplay
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
			bootbox.confirm('确定要删除这个任务吗？', 
					function(result) 
					{
						if(result){
							location.replace(href);
						}
					});		
		});
</script>
