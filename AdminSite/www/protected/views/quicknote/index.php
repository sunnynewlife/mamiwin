<?php if (isset($alert_message)) echo $alert_message;?>  
<div class="btn-toolbar">
	<a href="/quicknote/add"  class="btn btn-primary"><i class="icon-plus"></i> Quick Note</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">Quick Note列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
			<tr>
				<th>#</th>
				<th>所有者</th>
				<th>内容</th>
				<th width="80px">操作</th>
			</tr>
			</thead>
			<tbody>							  
				<?php foreach ($notes as $note){?> 
				<tr>
				<td><?php echo $note['note_id'];?></td>
				<td><?php echo $note['owner_name'];?></td>
				<td><?php echo $note['note_content'];?></td>
				<td>
				<?php if ($user_group ==1 || $note['owner_id'] == $current_user_id){?>
				<a href="/quicknote/modify?note_id=<?php echo $note['note_id'];?>" title= "修改" ><i class="icon-pencil"></i></a>
				&nbsp;
				<a data-toggle="modal" href="#myModal"  title= "删除" ><i class="icon-remove" href="/quicknote/del?note_id=<?php echo $note['note_id'];?>#myModal" data-toggle="modal" ></i></a>
				<?php }?>
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