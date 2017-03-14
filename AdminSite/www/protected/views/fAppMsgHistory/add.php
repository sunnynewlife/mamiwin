<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">活动消息发送设置-内容编辑</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="" onsubmit="return checkContentUrl();">
				<table class="table table-striped" style="width:815px;">
					<tr><td>消息内容设置:(全为必填项)</td></tr>
				</table>
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">消息频道</td>
						<td colspan=2>
							<select name="ChannelId"  id="ChannelId" class="input-xlarge" onchange="javascript:channelChanged();" required="true">
			<?php
							foreach ($Channels as $item){
								if($ExcludeChannelId!=$item["id"]){
									echo sprintf("<option value='%s'>%s</option>",$item["id"],$item["name"]);
								}
							}
			?>				
							</select>
						</td>
					</tr>
					<tr>						
						<td>消息类型</td>
						<td colspan=2>
							<select name="TypeId" class="input-xlarge" id="TypeId" required="true">
			<?php
							foreach ($Channels as $item){
								if( $ExcludeChannelId!=$item["id"] && count($item["types"])>0 && count($item["types"]["messageTypeList"])>0){
									$typeList=$item["types"]["messageTypeList"];
									foreach ($typeList  as $typeItem){
										echo sprintf("<option value='%s'>%s</option>",$typeItem["id"],$typeItem["name"]);
									}
									break;
								}
							}
			?>				
							</select>
						</td>
					</tr>
					<tr>
						<td>消息标题</td>
						<td colspan=2><input type="text" maxlength="50" name="Title" value="" class="input-xlarge" autofocus="true" required="true" /></td>
					</tr>
					<tr>
						<td>通知标题</td>
						<td colspan=2><input type="text" maxlength="50" name="NotifyTitle" value="" class="input-xlarge" autofocus="true"  required="true" /></td>
					</tr>
					<tr>
						<td>消息摘要</td>
						<td colspan=2>
							<textarea maxlength="200" name="Abstract" rows="3" style="width:400px;" class="input-xlarge" autofocus="true" required="true"></textarea>
					</tr>
					<tr>
						<td>消息图片</td>
						<td colspan=2><input type="text" maxlength="50" name="ImageUrl" value="" id="ImageUrl" onclick="javascript:selectPics('ImageUrl');" class="input-xlarge" autofocus="true" required="true" /></td>
					</tr>
					<tr>
						<td>消息内容</td>
						<td style="width:200px">
							<label for="ConentTypeUrl">外部地址提供&nbsp;&nbsp;<input type="radio"  id="ConentTypeUrl" name="ConentType" required="true" value="url" style="margin-top:-2px;" checked=true onclick="javascript:showTr('trContentUrl','trContentText');"/></label>
						</td>
						<td>
						<!-- 
							<label for="ConentTypeText">消息文本&nbsp;&nbsp;<input type="radio"  id="ConentTypeText" name="ConentType" required="true" value="text" style="margin-top:-2px;"onclick="javascript:showTr('trContentText','trContentUrl');" /></label>
						-->
						</td>
					</tr>
					<tr id="trContentUrl">
						<td>内容URL地址</td>
						<td colspan=2><input type="text" maxlength="200" id="DetailUrl" name="DetailUrl" value="" class="input-xlarge" autofocus="true" style="width:400px"/></td>
					</tr>
					<tr id="trContentText" style="display:none;" >
						<td>消息文本</td>
						<td colspan=2>
							 <textarea maxlength="300" id="Content" name="Content" rows="3" style="width:400px;" class="input-xlarge" autofocus="true"></textarea>
						</td>
					</tr>
				</table>
				<br/>				
				<table class="table table-striped" style="width:815px;">
					<tr><td>分享内容设置</td></tr>
				</table>
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">微信分享-标题</td>
						<td><input type="text" maxlength="50" name="WeixinTitle" value="" class="input-xlarge"  autofocus="true" /></td>
						<td style="width:120px;">微信分享-图片</td>
						<td><input type="text" maxlength="50" name="WeixinPic" id="WeixinPic" value="" class="input-xlarge"  autofocus="true" onclick="javascript:selectPics('WeixinPic')" /></td>
					</tr>
					<tr>
						<td>微信分享-内容</td>
						<td colspan=3><textarea id="WeixinContent" maxlength="300" name="WeixinContent" rows="3" style="width:600px;" class="input-xlarge"  autofocus="true"></textarea></td>
					</tr>
					<tr>
						<td>微博分享-文字</td>
						<td colspan=3><textarea id="WeiBoContent" maxlength="300" name="WeiBoContent" rows="3" style="width:600px;" class="input-xlarge"  autofocus="true"></textarea></td>
					</tr>
					<tr>
						<td>微博分享-图片</td>
						<td colspan=3><input type="text" maxlength="50" name="WeiBoPic" id="WeiBoPic" onclick="javascript:selectPics('WeiBoPic');" value="" class="input-xlarge" autofocus="true" /></td>
					</tr>
					<tr>
						<td>短信分享-文字</td>
						<td colspan=3><textarea id="PhoneMsg" maxlength="300" name="PhoneMsg" rows="3" style="width:600px;" class="input-xlarge"  autofocus="true"></textarea></td>
					</tr>
				</table>
				<br/>
				
				
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary">
						<strong>保存消息</strong>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php $this->widget('application.widget.PicSelector'); ?>
<script type="text/javascript">
function selectPics(pvTxtId)
{
	showPicSelector($("#AppId").val(),pvTxtId,$("#"+pvTxtId).val(),false);
}

var _ChannelID=[
<?php 
	foreach ($Channels as $item){
		$types="";
		if(count($item["types"])>0 && count($item["types"]["messageTypeList"])>0){
			$typeList=$item["types"]["messageTypeList"];
			foreach ($typeList  as $typeItem){
				$types.=sprintf(",[\"%s\",\"%s\"]",$typeItem["id"],$typeItem["name"]);	
			}
		}
		echo sprintf("\t[\"%s\" %s],\n",$item["id"],$types);
	}
?>
     ["EndChannelID",["EndTypeID","EndTypeName"]]                
	];
	
function channelChanged()
{
	var channelId=$("#ChannelId").val();
	if(channelId!=""){
		$("#TypeId").empty();
		for(var i=0;i<_ChannelID.length;i++){
			var item=_ChannelID[i];
			if(item[0]==channelId){
				if(item.length>1){
					for(var j=1;j<item.length;j++){
						var appendHtml="<option value='"+item[j][0]+"'>"+item[j][1]+"</option>";
						$("#TypeId").append(appendHtml);
					}
				}
			}
		}
	}
}
function showTr(pvShown,pvHidden)
{
	//$("#"+pvHidden).hide();
	//$("#"+pvShown).show();
}
function checkContentUrl()
{
	var isOk=false;
	var msg="";
	if($("#ConentTypeUrl").attr("checked")=="checked"){
		isOk=($("#DetailUrl").val()!="");
		msg="请输入内容URL地址。";
	}else {
		isOk=($("#Content").val()!="");
		msg="请输入消息文本。";
	}
	if(isOk==false){
		layer.alert(msg, 8);
	}
	return isOk;
}
</script>