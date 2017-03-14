<!-- TPLSTART 以上内容不需更改，保证该TPL页内的标签匹配即可 -->
<div class="btn-toolbar">
	<a href="/group/add" class="btn btn-primary"><i class="icon-plus"></i> 账号组</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">账号组列表</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
			<tr>
				<th>#</th>
				<th>账号组名</th>
				<th>所有者</th>
				<th>描述</th>
				<th width="80px">操作</th>
			</tr>
			</thead>
			<tbody>							  
			<?php foreach ($groups as $group ) {?>
				<tr>
				<td><?php echo $group['group_id'];   ?></td>
				<td><?php echo $group['group_name']; ?></td>
				<td><?php echo $group['owner_name']; ?></td>
				<td><?php echo $group['group_desc']; ?></td>
				<td>
				<a href="/group/member?group_id=<?php echo $group['group_id']; ?>" title= "成员列表" ><i class="icon-list-alt"></i></a>
				&nbsp;
				<a href="/group/modify?group_id=<?php echo $group['group_id']; ?>" title= "修改" ><i class="icon-pencil"></i></a>
				&nbsp;
				<?php if($group['group_id']!= 1){ ?>
					<a data-toggle="modal" href="#myModal"  title= "删除" ><i class="icon-remove" href="/group/del?group_id=<?php echo $group['group_id']; ?>#myModal" data-toggle="modal" ></i></a>
				<?php }?>
				</td>
				</tr>
			<?php }?>
		    </tbody>
		</table>  
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