<!--- START 以上内容不需更改，保证该TPL页内的标签匹配即可 --->
<div class="btn-toolbar" style="margin-bottom:2px;">
    <a href="/account/add" class="btn btn-primary"><i class="icon-plus"></i> 账号</a>
	<a data-toggle="collapse" data-target="#search" href="#" title="检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>
</div>
<div id="search" class="collapse out">
<form class="form_search" action="" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>选择账号组</label>
		<select name="user_group" class="input-xlarge" id="DropDownTimezone">
		<?php $this->widget('application.widget.Group'); ?>
</select>

	</div>
	<div style="float:left;margin-right:5px">
		<label>查询所有用户请留空</label>
		<input type="text" name="user_name" value="" placeholder="输入登录名"> 
		<input type="hidden" name="search" value="1"> 
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
    <div class="block">
        <a href="#page-stats" class="block-heading" data-toggle="collapse">账号列表</a>
        <div id="page-stats" class="block-body collapse in">
               <table class="table table-striped">
              <thead>
                <tr>
					<th style="width:20px">#</th>
					<th style="width:80px">登录名</th>
					<th style="width:100px">姓名</th>
					<th style="width:100px">手机</th>
					<th style="width:80px">邮箱</th>
					<th style="width:80px">登录时间</th>
					<th style="width:80px">登录IP</th>
					<th style="width:80px">Group#</th>
					<th style="width:80px">描述</th>
					<th style="width:80px">操作</th>
                </tr>
              </thead>
              <tbody>							   
             <?php foreach ($users as $val) { ?>   			 
					<tr>
					<td><?php echo $val['user_id']; ?></td>
					<td><?php echo $val['user_name']; ?></td>
					<td><?php echo $val['real_name']; ?></td>
					<td><?php echo $val['mobile']; ?></td>
					<td><?php echo $val['email']; ?></td>
					<td><?php echo date('Y-m-d H:i:s',$val['login_time']); ?></td>
					<td><?php echo $val['login_ip']; ?></td>
					<td><?php echo $val['group_name']; ?></td>
					<td><?php echo $val['user_desc']; ?></td>
					<td>
					<?php if($val['user_id'] != 1) { ?>
					<a href="/account/modify?user_id=<?php echo $val['user_id']; ?>" title= "修改" ><i class="icon-pencil"></i></a>
					&nbsp;
					<?php 	
						if ($val['status'] == 1){
					?>
					<a data-toggle="modal" href="#myModal"  title= "封停账号" ><i class="icon-pause" href="/account/archived?page_no=<?php echo $page_no; ?>&user_id=<?php echo $val['user_id']; ?>"></i></a>
					<?php } ?>
					<?php if ($val['status'] == 0) {?>
					<a data-toggle="modal" href="#myModal" title= "解封账号" ><i class="icon-play" href="/account/open?page_no=<?php echo $page_no;  ?>&user_id=<?php echo $val['user_id']; ?>"></i></a>
					<?php } ?>
					&nbsp;
					<a data-toggle="modal" href="#myModal" title= "删除" ><i class="icon-remove" href="/account/del?page_no=<?php echo $page_no; ?>&user_id=<?php echo $val['user_id']; ?>" ></i></a>
					<?php } ?>
					</td>
					</tr>
				<?php } ?>
			 </tbody>
            </table> 
				<!--- START 分页模板 --->
				
            
               <?php echo $page;?>					
			   <!--- END --->
        </div>
    </div>

<!---操作的确认层，相当于javascript:confirm函数--->
<script>
				$('.icon-pause').click(function(){
						
						var href=$(this).attr('href');
						bootbox.confirm('确定要这样做吗？', function(result) {
							if(result){

								location.replace(href);
							}
						});		
					})
					
				
				$('.icon-play').click(function(){
						
						var href=$(this).attr('href');
						bootbox.confirm('确定要这样做吗？', function(result) {
							if(result){

								location.replace(href);
							}
						});		
					})
					
				
				$('.icon-remove').click(function(){
						
						var href=$(this).attr('href');
						bootbox.confirm('确定要这样做吗？', function(result) {
							if(result){

								location.replace(href);
							}
						});		
					})
					
				</script>