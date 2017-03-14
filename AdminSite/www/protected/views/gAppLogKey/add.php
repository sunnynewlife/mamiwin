<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写配置信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<h2>基本信息</h2>
				<label>产品ID</label> 
				<input  name="ProductId" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>分配key</label> 
				<input  name="des_key" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>游戏介绍</label> 
				<textarea name="Product_intr" rows="5" class="input-xlarge" required="true" autofocus="true" value=""></textarea>
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
