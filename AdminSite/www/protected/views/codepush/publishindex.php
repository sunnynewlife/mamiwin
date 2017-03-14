<?php if (isset($alert_message)) echo $alert_message;?>  
<div class="btn-toolbar">
	<a href="/codepush/sourceindex"  class="btn btn-primary"><i class="icon-list"></i> 返回 项目资源配置列表</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">Git 打包列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
			<tr>
				<th>#</th>
				<th>Source Id</th>
				<th>Service</th>
				<th>Hash Main</th>
				<th>Hash SDK</th>
				<th>Tag</th>
				<th>Status</th>
				<th>Locked</th>
				<th>Valid</th>
				<th>Git Diff</th>
				<th>Result</th>
				<th>Add Time</th>
				<th>Update Time</th>
				<th>Opt Name</th>
				<th width="80px">操作</th>
			</tr>
			</thead>
			<tbody>							  
				<?php foreach ($publishs as $publish){?> 
				<tr>
				<td><?php echo $publish['id'];?></td>
				<td><?php echo $publish['source_id'];?></td>
				<td><?php echo $publish['service'];?></td>
				<td><?php echo $publish['hash_main'];?></td>
				<td><?php echo $publish['hash_sdk'];?></td>
				<td><?php echo $publish['tag'];?></td>
				<td><?php echo $publish['status'];?></td>
				<td><?php echo $publish['is_locked'];?></td>
				<td><?php echo $publish['is_valid'];?></td>
				<td><?php echo $publish['git_diff'];?></td>
				<td><?php echo $publish['result'];?></td>
				<td><?php echo $publish['add_time'];?></td>
				<td><?php echo $publish['update_time'];?></td>
				<td><?php echo $publish['opt_username'];?></td>
				<td>
				<!--
				<a href="/codepush/publishmodify?publish_id=<?php echo $publish['id'];?>" title= "修改" ><i class="icon-pencil"></i></a>
				&nbsp;
				<a data-toggle="modal" href="#myModal"  title= "删除" ><i class="icon-remove" href="/codepush/publishdel?publish_id=<?php echo $publish['id'];?>#myModal" data-toggle="modal" ></i></a>
				-->
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