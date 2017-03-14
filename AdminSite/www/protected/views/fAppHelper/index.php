<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">辅助攻击-更新渠道包地址</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<label>请输入游戏AppId:</label>
			<input type="text" maxlength="50" id="AppId" name="AppId" value="" class="input-xlarge" autofocus="true" reqiured />
			
			<label>请输入游戏AppVersionId:</label>
			<input type="text" maxlength="50" id="AppVersionId" name="AppVersionId" value="" class="input-xlarge" autofocus="true" reqiured />

			<label>请输入测试的Url:</label>
			<input type="text" maxlength="50" id="TestUrl" name="TestUrl" value="http://116.211.12.173:80/down/promoter/9ZS1G7" class="input-xlarge"  style="width:400px;" autofocus="true" reqiured />
			<br/>
			<button type="button" class="btn btn-primary" onclick="javascript:sendUpdateTask();"><strong>更新渠道包地址 </strong></button>
		</div>
	</div>
</div>
<script type="text/javascript">
function sendUpdateTask()
{
	var lvAppId=$("#AppId").val();
	var lvAppVersionId=$("#AppVersionId").val();
	var lvTestUrl=$("#TestUrl").val();
	if(lvAppId=="") {
		layer.alert("请输入游戏AppId。",3);
		return;
	}
	if(lvAppVersionId=="") {
		layer.alert("请输入游戏AppVersionId。",3);
		return;
	}	

	var UpdateUrl="/fAppHelper/updatePromoterTask";
	var UpdateData={"AppId":lvAppId,"AppVersionId":lvAppVersionId,"TestUrl":lvTestUrl};
	$.ajax(
		{
			url:UpdateUrl,
			data:UpdateData,
			success: function(json){
						if(json.return_code==0){
							layer.alert("更新 消息发送成功",1);
						}else{
							layer.alert(json.return_msg,8);
						}
			         },
			timeout:25000,
			error:function(XMLHttpRequest, textStatus, errorThrown){
						if(textStatus!="success"){
							var errorMsg="textStatus:"+textStatus+" Message:"+errorThrown.message+" Description:"+errorThrown.description;
							layer.alert(errorMsg,8);
						}
					},
			dataType:"json"
		}
	);				
}
</script>