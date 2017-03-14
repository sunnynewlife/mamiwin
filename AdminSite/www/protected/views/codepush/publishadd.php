<!-- START 以上内容不需更改，保证该TPL页内的标签匹配即可 -->
<?php if (isset($alert_message)) echo $alert_message;?> 
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">Git 打包</a></li>
    </ul>	
	<div id="myTabContent" class="tab-content">
		  <div class="tab-pane active in" id="home">
           <form id="tab" method="post" action="">
				<h4>项目：<?php echo $service;?></h4>
				<h5>Source：<?php echo $source;?></h4>
			
				<label>Hash Main &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-important">必须正确</span></label>
				<input type="text" name="hash_main" value="" class="input-xlarge" required="true" autofocus="true">
				
				<label>Has SDK &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info">如果没有引用SDK项目则不用填写</span></label>
				<input type="text" name="hash_sdk" value="" class="input-xlarge" >
				
				<label>描述</label>
				<textarea name="commit" rows="3" class="input-xlarge"></textarea>
				
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary"><strong>提交</strong></button>
				</div>
				
			</form>
        </div>
    </div>
</div>