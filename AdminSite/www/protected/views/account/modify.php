<!-- START 以上内容不需更改，保证该TPL页内的标签匹配即可 -->
<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">请修改账号资料</a></li>
    </ul>	
	
	<div id="myTabContent" class="tab-content">
		  <div class="tab-pane active in" id="home">
           <form id="tab" method="post" action="/account/modify" autocomplete="off">
				<label>登录名 <span class="label label-info">不可修改</span></label>
				<input type="text" name="user_name" value="<?php echo $user['user_name']; ?>" class="input-xlarge" readonly="true">
				<label>密码 <span class="label label-important" >如不修改请留空</span></label>
				<input type="password" name="password" value="" class="input-xlarge">
				<label>姓名</label>
				<input type="text" name="real_name" value="<?php echo $user['real_name']; ?>" class="input-xlarge" required="true" >
				<label>手机</label>
				<input type="text" name="mobile" value="<?php echo $user['mobile']; ?>" class="input-xlarge" required pattern="\d{11}">
				<label>邮件</label>
				<input type="email" name="email" value="<?php echo $user['email']; ?>"  class="input-xlarge" required="true" >
				<label>描述</label>
				<textarea name="user_desc" rows="3" class="input-xlarge"><?php echo $user['user_desc'];?></textarea>
				<label>账号组</label>
				<select name="user_group" class="input-xlarge" id="DropDownTimezone">
					<?php $this->widget('application.widget.Group',array('current_user_group'=>$user['user_group'])); ?>
				</select>
				<input type="hidden" name="submit" value="1"  class="input-xlarge" required="true" >
				<input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>"  class="input-xlarge" required="true" >
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary"><strong>提交</strong></button>
					<div class="btn-group"></div>
				</div>
			</form>
        </div>
    </div>
</div>	