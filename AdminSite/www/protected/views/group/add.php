<!-- START 以上内容不需更改，保证该TPL页内的标签匹配即可 -->
<?php if (isset($alert_message)) echo $alert_message;?>    
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">请填写账号组资料</a></li>
    </ul>	
	
	<div id="myTabContent" class="tab-content">
		  <div class="tab-pane active in" id="home">

           <form id="tab" method="post" action="/group/add">
				<label>账号组名称</label>
				<input type="text" name="group_name" value="" class="input-xlarge" required="true" autofocus="true" >
				<label>描述</label>
				<textarea name="group_desc" rows="3" class="input-xlarge"></textarea>
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary"><strong>提交</strong></button>
				</div>
			</form>
        </div>
    </div>
</div>