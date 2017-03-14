<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填外部接口访问定义</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>系统代码</label> 
				<input type="text" name="key" value="" class="input-xlarge" required="true" autofocus="true">
				
				<label>Domain</label> 
				<input type="text" name="domain" value="" class="input-xlarge" required="true" autofocus="true">
				
				<label>接口响应格式类型</label> 
				<select name="responseType" class="input-xlarge">
					<option value="json" selected>json格式</option>
					<option value="text">普通文本</option>
				</select>				
				
				<label>接口调用方式</label> 
				<select name="method" class="input-xlarge">
					<option value="get" selected>GET方式</option>
					<option value="post">POST方式</option>
				</select>
				
				<label>是否需要MD5签名</label> 
				<select name="needMd5Sign"  id="needMd5Sign" class="input-xlarge" onchange="javascript:itemChange();">
					<option value="false" selected>不需要</option>
					<option value="true">需要</option>
				</select>
				
				<div id="divMd5" style="display:none">
					<label>MD5签名密钥</label> 
					<input type="text" name="signPwd" value="" class="input-xlarge"  autofocus="true">
					
					<label>签名密钥位置</label> 
					<select name="signPosition" class="input-xlarge">
						<option value="head" selected>头部</option>
						<option value="bottom">尾部</option>
					</select>
					
					<label>校验签名参数名</label> 
					<input type="text" name="signParaName" value="" class="input-xlarge" autofocus="true">

					<label>参数名称、值链接字符</label> 
					<input type="text" name="signParamJoinChar" value="" class="input-xlarge" autofocus="true">
				</div>
				
								
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
<script type="text/javascript">
	function itemChange()
	{
		var needMd5Sign=$("#needMd5Sign").val();
		if(needMd5Sign=="true"){
			$("#divMd5").show();
		}else{
			$("#divMd5").hide();
		}
	}
</script>