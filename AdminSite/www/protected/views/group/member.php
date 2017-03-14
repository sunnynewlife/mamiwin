<!-- START 以上内容不需更改，保证该TPL页内的标签匹配即可 -->
<?php if (isset($alert_message)) echo $alert_message;?>     
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">账号组成员列表</a></li>
    </ul>	
	<div id="myTabContent" class="tab-content">
		  <div class="tab-pane active in" id="home">
             <form id="tab" method="post" action="/group/member/">
				 <table class="table table-striped">
              <thead>
                <tr>
					<th><input type="checkbox" id="checkAll" >全选</th>
					<th>#</th>
					<th>登录名</th>
					<th>姓名</th>
					<th>手机</th>
					<th >邮箱</th>
					<th >登录时间</th>
					<th >登录IP</th>
					<th >Group#</th>
					<th>描述</th>
                </tr>
              </thead>
              <tbody>							  
                <?php foreach ($users as $user_info){?>
					<tr>
					<td><input type="checkbox" name="user_ids[]" value="<?php echo $user_info["user_id"]; ?>" ></td>
					<td><?php echo $user_info["user_id"];?></td>
					<td><?php echo $user_info["user_name"];?></td>
					<td><?php echo $user_info["real_name"];?></td>
					<td><?php echo $user_info["mobile"];?></td>
					<td><?php echo $user_info["email"];?></td>
					<td><?php echo $user_info["login_time"];?></td>
					<td><?php echo $user_info["login_ip"];?></td>
					<td><?php echo $user_info["user_group"];?></td>
					<td><?php echo $user_info["user_desc"];?></td>
					</tr>
				<?php } ?>
              </tbody>
            </table>
			<label>选择账号组</label>
				<select name="user_group" class="input-xlarge" id="DropDownTimezone">
				<?php $this->widget('application.widget.Group',array('current_user_group'=>$group_id)); ?>
				</select>
				<div class="btn-toolbar">
					<input type="hidden" name="submit" value="1" /> 
					<input type="hidden" name="group_id" value="<?php echo $group_id ;?>" /> 
					<button type="submit" class="btn btn-primary"><strong>修改账号组</strong></button>
				</div>
			</form>
        </div>
    </div>
<script type="text/javascript">
$("#checkAll").click(function(){
     if($(this).attr("checked")){
		$("input[name='user_ids[]']").attr("checked",$(this).attr("checked"));
	 }else{
		$("input[name='user_ids[]']").attr("checked",false);
	 }
});
</script>