<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">内部消息频道Mapping</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">

				<label>消息Key</label> 
				<input name="typeKey" type="text"  class="input-xlarge" required="true" autofocus="true"  value="<?php echo $MsgChannels["typeKey"];?>" />
				
				<label>消息描述</label> 
				<input name="typeName" type="text"  class="input-xlarge" required="true" autofocus="true"  value="<?php echo $MsgChannels["typeName"];?>" />
				
				<label>使用的频道</label>
				<select name="ChannelId"  id="ChannelId" class="input-xlarge" onchange="javascript:channelChanged();">
<?php
				foreach ($Channels as $item){
					
					echo sprintf("<option value='%s' %s>%s</option>",
							$item["id"],
							($MsgChannels["ChannelId"]==$item["id"]?" selected ":" "),
							$item["name"]);
				}
?>				
				</select>
			
				<label>消息类型</label>
				<select name="TypeId" class="input-xlarge" id="TypeId">
<?php
				foreach ($Channels as $item){
					if(empty($MsgChannels["ChannelId"])){
						if(count($item["types"])>0 && count($item["types"]["messageTypeList"])>0){
							$typeList=$item["types"]["messageTypeList"];
							foreach ($typeList  as $typeItem){
								echo sprintf("<option value='%s'>%s</option>",$typeItem["id"],$typeItem["name"]);
							}
						}
						break;
					}
					else {
						if($MsgChannels["ChannelId"]==$item["id"]){
							if(count($item["types"])>0 && count($item["types"]["messageTypeList"])>0){
								$typeList=$item["types"]["messageTypeList"];
								foreach ($typeList  as $typeItem){
									echo sprintf("<option value='%s' %s>%s</option>",
										$typeItem["id"],
										($typeItem["id"]==$MsgChannels["TypeId"]?" selected ":""),
										$typeItem["name"]);
								}
							}
							break;
						}
					}
				}
?>				
				</select>
				
				
				<input type="hidden" name="submit" value="1" />
				<input type="hidden" name="RecId" value="<?php echo $MsgChannels["RecId"];?>" />
				
				<ul class="nav nav-tabs" style="border-bottom:0px;">
					<li style="padding-top:5px;"><a href="<?php echo $back_url;?>">放弃</a></li>
					<li> 
						<div class="btn-toolbar">
							<button type="submit" class="btn btn-primary">
								<strong>提交</strong>
							</button>
					
						</div>
					</li>
				</ul>
								
			</form>				
		</div>
	</div>
</div>
<script type="text/javascript">
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
</script>