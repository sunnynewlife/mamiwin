<!-- START 以上内容不需更改，保证该TPL页内的标签匹配即可 -->
<?php if (isset($alert_message)) echo $alert_message;?> 
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">Git 项目资源</a></li>
    </ul>	
	<div id="myTabContent" class="tab-content">
		  <div class="tab-pane active in" id="home">
           <form id="tab" method="post" action="">
				<h4>新建一个项目，并保存项目开发代码 Git 地址</h4>
			
				<label>Service Name (项目名字)  &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-important">必须正确</span></label>
				<input type="text" name="service_name" value="" class="input-xlarge" required="true" autofocus="true">
			
				<label>Service (生产库)  &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-important">必须正确</span></label>
				<input type="text" name="service" value="" class="input-xlarge" required="true" autofocus="true">
				
				<label>Service Repo (生产库 git repo)  &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-important">必须正确</span></label>
				<input type="text" name="service_repo" value="" class="input-xlarge" required="true" autofocus="true">
				
				<br/>
				------------------------------------------------------------------------------------------------------------------------
				<br/><br/>
							
				<label>Source (开发库) &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-important">必须正确</span></label>
				<input type="text" name="source" value="" class="input-xlarge" required="true" autofocus="true">
				
				<label>Source Repo (开发库 git repo)  &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-important">必须正确</span></label>
				<input type="text" name="source_repo" value="" class="input-xlarge" required="true" autofocus="true">
				
				<br/>
				------------------------------------------------------------------------------------------------------------------------
				<br/><br/>
				
				<label>SDK (开发库) &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info">如果没有引用SDK项目则不用填写</span></label>
				<input type="text" name="sdk" value="" class="input-xlarge" >
				
				<label>SDK Repo (生产库 git repo)  &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info">如果没有引用SDK项目则不用填写</span></label>
				<input type="text" name="sdk_repo" value="" class="input-xlarge" required="true" autofocus="true">
				
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary"><strong>提交</strong></button>
				</div>
				
			</form>
        </div>
    </div>
</div>