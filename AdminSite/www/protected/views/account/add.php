<!-- START 以上内容不需更改，保证该TPL页内的标签匹配即可 -->
<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">请填写账号资料</a></li>
    </ul>	
	
	<div id="myTabContent" class="tab-content">
		  <div class="tab-pane active in" id="home">
           <form id="tab" method="post" action="/account/add" autocomplete="off">
				<label>登录名</label>
				<input type="text" name="user_name" value="" class="input-xlarge" autofocus="true" required="true" >
				<label>密码</label>
				<input type="password" name="password" value="" class="input-xlarge" required="true" >
				<label>姓名</label>
				<input type="text" name="real_name" value="" class="input-xlarge" required="true" >
				<label>手机</label>
				<input type="text" name="mobile" value="" class="input-xlarge" required pattern="\d{11}">
				<label>邮件</label>
				<input type="email" name="email" value=""  class="input-xlarge" required="true" >
				<label>描述</label>
				<textarea name="user_desc" rows="3" class="input-xlarge"></textarea>
				<label>账号组</label>
				<select name="user_group" class="input-xlarge" id="DropDownTimezone">
					<?php $this->widget('application.widget.Group'); ?>
				</select>
				<input type="hidden" name="submit" value="1"  class="input-xlarge" required="true" >
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary"><strong>提交</strong></button>
					<div class="btn-group"></div>
				</div>
			</form>
        </div>
    </div>
</div>