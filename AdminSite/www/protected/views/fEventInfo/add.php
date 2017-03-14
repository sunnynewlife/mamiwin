<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写配置信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>标题</label> 
				<input  name="title" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>介绍</label> 
				<input  name="info" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>应用ID</label> 
				<input  name="app_id" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>开始时间</label> 
				<input  name="begin_time" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<label>结束时间</label> 
				<input  name="end_time" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<label>奖励ID,json格式</label> 
				<input  name="item" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<label>状态</label> 
				<select name="status">
					<option value="0"  >正常</option>
					<option value="1" >删除</option>
					<option value="2" >暂停</option>
				</select>
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary" onclick="doConvert();">
						<strong>提交</strong>
					</button>
				</div>
			
			</form>
		</div>
	</div>
</div>
<?php $this->widget('application.widget.JsonEditor'); ?>
<script type="text/javascript">
function jsonFormatChange()
{
	if($("#jsonFormater").attr("checked")=="checked"){
		$("#btnJsonEdit").show();
	}else{
		$("#mask").hide();
		$("#btnJsonEdit").hide();
	}
}
function doJsonEdit()
{
	showJsonEditor($("#config_value").val());   
}
</script>
