<div class="btn-toolbar">
	<a href="/mWMaterial/add" class="btn btn-primary"><i class="icon-plus"></i>新增任务</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">任务查看列表</a>
	<div id="search" class="in collapse">
		<form class="form_search"  method="POST" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">任务类型：</td>
						<td>
							<select name="Task_Type">
								<option value="">全部</option>
								<option value="1">学习任务</option>
								<option value="2">陪伴任务</option>							
							</select>						
						</td>
						<td style="width:120px;">任务发布状态：</td>
						<td>
							<select name="Task_Status">
								<option value="">全部</option>
								<option value="0">未发布</option>
								<option value="1">公开</option>
								<option value="2">灰度</option>							
							</select>						
						</td>
					</tr>
					<tr>
						<td style="width:120px;">学习所需时间：</td>
						<td>
							<input type='text'  name="Min_Time" />
						</td>
						<td colspan=2>
							~<input type='text'  name="Max_Time" />
						</td>
					</tr>
					<tr>
						<td style="width:120px;">年龄范围：</td>
						<td>
							<input type='text'  name="Min_Age" />
						</td>
						<td colspan=2>
							~<input type='text'  name="Max_Age" />
						</td>
					</tr>
					<tr>
						<td style="width:120px;">孩子性别：</td>
						<td>
							<select name="Child_Gender">
								<option value="0">不限制</option>
								<option value="1">女孩</option>
								<option value="2">男孩</option>							
							</select>						
						</td>
						<td style="width:120px;">父母性别：</td>
						<td>
							<select name="Parent_Gender">
								<option value="0">不限制</option>
								<option value="1">母亲</option>
								<option value="2">父亲</option>							
							</select>						
						</td>
					</tr>
					<tr>
						<td style="width:120px;">父母婚姻状况：</td>
						<td>
							<select name="Parent_Marriage">
								<option value="0">不限</option>
								<option value="1">单亲</option>					
							</select>						
						</td>
						<td style="width:120px;">是否独生：</td>
						<td>
							<select name="Only_Children">
								<option value="0">不限</option>
								<option value="1">独生小孩</option>
							</select>						
						</td>
					</tr>																					
					<tr>
						<td style="width:120px;">任务标题：</td>
						<td style="width:120px;"><input type='text'  name="Task_Title" /></td>
						<td><button type="submit" class="btn btn-primary">查  询</button></td>
						<td></td>
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
					<th>素材类型</th>
					<th>任务类型</th>
					<th>任务标题</th>
					<th>学习时间</th>
					<th>年龄</th>
					<th>孩子性别</th>
					<th>父母性别</th>
					<th>婚姻状况</th>
					<th>是否独生</th>
					<th>培养能力类型</th>
					<th width="80px">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td width="60px">%s</td>
	<td width="220px">%s</td>
	<td width="80px">%s</td>
	<td width="150px">%s</td>
	<td width="150px">%s</td>
	<td width="40px;">
		<a href="/fAppMsgHistory/modify?InformationId=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除这条任务记录"><i class="icon-remove" href="/mWMaterial/del?IDX=%s"></i></a>
	</td>
<tr>
EndOfRowTag;
/*
			$titleLen=20;
			foreach ($MsgList as $item){
				$title=$item["Title"];
				if(mb_strlen($title,"utf-8")>$titleLen){
					$title=mb_substr($title, 0,$titleLen,"utf-8")."..";
				}
				echo sprintf($rowsHtmlTag,
					$item["InformationId"],
					$title,
					(empty($item["PhoneListFileCounts"]) || $item["PhoneListFileCounts"]<1)?"未上传":$item["PhoneListFileCounts"],
					$item["CreateDt"],
					$item["ScheduleTime"],
					$item["InformationId"],$item["InformationId"]
				);
			}
*/
			?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">

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
