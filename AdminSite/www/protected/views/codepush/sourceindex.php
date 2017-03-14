<?php if (isset($alert_message)) echo $alert_message;?>  
<div class="btn-toolbar">
	<a href="/codepush/publishindex"  class="btn btn-primary"><i class="icon-list"></i> 打包日志</a>
	<a href="/codepush/sourceadd"  class="btn btn-primary"><i class="icon-plus"></i> 项目资源配置增加</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">Git 项目配置列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
			<tr>
				<th>#</th>
				<th>Service Name</th>
				<th>Service</th>
				<th>Source</th>
				<th>Pre Fix Tag</th>
				<th>Is SDK</th>
				<th>SDK</th>
				<th>Is Valid</th>
				<th>Add Time</th>
				<th>Update Time</th>
				<th width="80px">操作</th>
			</tr>
			</thead>
			<tbody>							  
				<?php foreach ($sources as $source){?> 
				<tr>
				<td><?php echo $source['id'];?></td>
				<td><?php echo $source['service_name'];?></td>
				<td><?php echo $source['service'];?></td>
				<td><?php echo $source['source'];?></td>
				<td><?php echo $source['prefix_tag'];?></td>
				<td><?php echo $source['is_sdk'];?></td>
				<td><?php echo $source['sdk'];?></td>
				<td><?php echo $source['is_valid'];?></td>
				<td><?php echo $source['add_time'];?></td>
				<td><?php echo $source['update_time'];?></td>
				<td>
				<a href="/codepush/sourceupdate?source_id=<?php echo $source['id'];?>" title= "修改" ><i class="icon-pencil"></i></a>
				&nbsp;
				<a href="/codepush/publishadd?source_id=<?php echo $source['id'];?>" title= "发布" ><i class="icon-plus"> 打包</i></a>
				</td>
				</tr>
			<?php }?>
		  </tbody>
		</table>
			<!--- START 分页模板 --->
			<?php echo $page;?>
			 <!--- END --->
	</div>
</div>
<!---操作的确认层，相当于javascript:confirm函数--->
<script>
	$('.icon-remove').click(function(){				
		var href=$(this).attr('href');
		bootbox.confirm('确定要这样做吗？', function(result) {
			if(result){
					location.replace(href);
			}
		});		
	})		
</script>