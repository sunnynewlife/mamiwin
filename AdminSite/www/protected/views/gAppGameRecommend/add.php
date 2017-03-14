<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写配置信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>游戏ID</label> 
				<input  name="gameId" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>游戏名称</label> 
				<input  name="gameName" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>下载次数</label> 
				<input  name="downloadCount" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>游戏大小</label> 
				<input  name="size" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>开发商</label> 
				<input  name="developer" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>游戏LOGO</label> 
				<input  name="gameLogo" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>状态</label> 
				<input  name="state" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
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