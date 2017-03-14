<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写配置定义信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>配置项Key</label> 
				<input type="text" maxlength="50" name="ConfigKey" value="" class="input-xlarge" required="true" autofocus="true" />
				
				<label>配置项描述</label> 
				<input type="text" maxlength="100" name="ConfigName" value="" class="input-xlarge" required="true" autofocus="true" />
				
				<label>配置项值</label>
				<table class="table table-striped" style="width:815px;">
					<tr>
						<td style="width:80px;">配置内容项</td>
						<td style="width:60px;">格式:</td>
						<td style="width:15px;padding-right:0px;">
							<input type="checkbox" value="1" name="jsonFormater" id="jsonFormater" onchange="javascript:jsonFormatChange();">
						</td>
						<td style="padding-left:4px;width:120px;">
							<label for="jsonFormater">json格式</label>
						</td>
						<td>
							<a href="javascript:doJsonEdit();" id="btnJsonEdit" style="display:none;">编辑Json格式配置内容 </a>
						</td>
					</tr>
					
				</table>
				<textarea id="ConfigValue" maxlength="15000" name="ConfigValue" rows="8" style="width:800px;" class="input-xlarge" required="true" autofocus="true"></textarea>
				
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary">
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
	showJsonEditor($("#ConfigValue").val());   
}
</script>