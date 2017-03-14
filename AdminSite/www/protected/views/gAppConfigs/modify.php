<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写配置项信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>配置项名称</label> 
				<input  name="config_key" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_app_configs["config_key"];?>"/>
				<br/>
				<br/>
<?php
	$isJsonFormat=false;
	$jsonObject=json_decode($t_app_configs["config_value"]);
	if(isset($jsonObject) && is_array($jsonObject)){
		$isJsonFormat=true;
	}
?>				
				<table class="table table-striped" style="width:815px;">
					<tr>
						<td style="width:80px;">配置内容项</td>
						<td style="width:60px;">格式:</td>
						<td style="width:15px;padding-right:0px;">
							<input type="checkbox" value="1" name="jsonFormater" id="jsonFormater" onchange="javascript:jsonFormatChange();" <?php echo $isJsonFormat?"checked":""; ?>>
						</td>
						<td style="padding-left:4px;width:120px;">
							<label for="jsonFormater">json格式</label>
						</td>
						<td>
							<a href="javascript:doJsonEdit();" id="btnJsonEdit" style="display:<?php echo $isJsonFormat?"block":"none"; ?>">编辑Json格式配置内容 </a>
						</td>
					</tr>
					
				</table>
				<textarea id="config_value" name="config_value" rows="8" style="width:800px;" class="input-xlarge" required="true" autofocus="true"><?php echo $t_app_configs["config_value"];?></textarea>
	
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
