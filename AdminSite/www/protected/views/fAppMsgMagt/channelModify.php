<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">消息频道信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">频道名称</td>
						<td><input type="text" maxlength="50" name="ChannelName"   value="<?php echo $Channel["name"];?>" class="input-xlarge" required="true" autofocus="true" /></td>
					</tr>
					<tr>
						<td>频道图标*</td>
						<td><input type="text" maxlength="50" name="ChannelPic" value="<?php echo $Channel["icon_id"];?>" id="ChannelPic" onclick="javascript:selectPics('ChannelPic');" class="input-xlarge" autofocus="true" required="true" /></td>
					</tr>
					<tr>
						<td>频道描述*</td>
						<td><input type="text" maxlength="50" name="DescContent"   value="<?php echo $Channel["DescContent"];?>" class="input-xlarge" required="true" autofocus="true" /></td>
					</tr>
					<tr>
						<td>排列顺序*</td>
						<td><input type="text" maxlength="50" name="SortOrder"   value="<?php echo $Channel["SortOrder"];?>" class="input-xlarge" required="true" autofocus="true" /></td>
					</tr>
					<tr>
						<td>上架状态*</td>
						<td>
							<select name="Status"  class="input-xlarge" required="true">
								<option value="0" <?php echo $Channel["Status"]=="0"?" selected ":" ";?>>测试</option>
								<option value="1" <?php echo $Channel["Status"]=="1"?" selected ":" ";?>>上线</option>
								<option value="2" <?php echo $Channel["Status"]=="2"?" selected ":" ";?>>下线</option>
							</select>
						</td>						
					</tr>
					<tr>
						<td>频道列表类型*</td>
						<td>
							<select name="ListType"  class="input-xlarge" required="true">
								<option value="1" <?php  echo $Channel["ListType"]=="1"?" selected ": " ";?>>使用外部地址</option>
								<option value="2" <?php  echo $Channel["ListType"]=="2"?" selected ": " ";?>>原生程序渲染</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>消息列表地址</td>
						<td><input type="text" maxlength="100" name="ListUrl" value="<?php echo $Channel["ListUrl"];?>"  class="input-xlarge" autofocus="true" style="width:450px;" /></td>
					</tr>
					<tr>
						<td>测试白名单</td>
						<td><textarea id="TestPhone"  name="TestPhone" rows="5" style="width:600px;" class="input-xlarge" autofocus="true"><?php echo $Channel["TestPhone"];?></textarea></td>
					</tr>
				</table>
				
				<input type="hidden" name="submit" value="1" />
				<input type="hidden" name="AppId" id="AppId" value="" />
				<input type="hidden" name="channelId"  value="<?php echo $Channel["id"];?>" />
				
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
<?php $this->widget('application.widget.PicSelector'); ?>
<script type="text/javascript">
function selectPics(pvTxtId)
{
	showPicSelector($("#AppId").val(),pvTxtId,$("#"+pvTxtId).val(),false);
}
</script>