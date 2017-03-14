<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">游戏版本信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">游戏ID</td>
						<td><input type="text" maxlength="50" name="AppId" readonly id="AppId" value="<?php echo $AppVersion["AppId"];?>" class="input-xlarge" required="true" autofocus="true" /></td>
						<td style="width:100px;">游戏名称</td>
						<td><?php echo $AppVersion["AppName"]; ?></td>
					</tr>
					<tr>
						<td>版本名称*</td>
						<td><input type="text" maxlength="50" name="VersionName" value="" class="input-xlarge" autofocus="true" required="true" /></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>游戏大小*</td>
						<td><input type="text" maxlength="50" name="GameSize" value="" class="input-large" autofocus="true" required="true" />&nbsp;M</td>
						<td>游戏截图*</td>
						<td><input type="text" maxlength="50" name="GamePics" value="<?php echo $AppIntroPicture;?>" id="GamePics" onclick="javascript:selectPics('GamePics');" class="input-xlarge" autofocus="true" required="true" /></td>
					</tr>
					<tr>
						<td>版本上架状态*</td>
						<td>
							<select name="State" class="input-xlarge">
								<option value="0" >测试</option>
								<option value="1" >上架</option>
								<option value="2" >下架</option>
							</select>
						</td>
						<td>测试白名单</td>
						<td><input type="text" maxlength="50000" name="TestPhone" value="" class="input-xlarge" autofocus="true" /></td>						
					</tr>
					</tr>
						<td>当前推广版本</td>
						<td>
							<select name="IsPublishVersion" class="input-xlarge">
								<option value="0">不推广</option>
								<option value="1">推广</option>
							</select>
						</td>
						<td>母包文件名:</td>
						<td><input type="text" maxlength="50" name="PackagePath" value="" class="input-xlarge" autofocus="true" required="true" /></td>
					</tr>
					<tr>
						<td>MD5签名值</td>
						<td><input type="text" maxlength="50" name="PackageMd5" value="" class="input-xlarge" autofocus="true" required="true" /></td>
						<td></td>
						<td></td>
					</tr>					
					
				</table>
				
				<input type="hidden" name="submit" value="1" />
				
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
	showPicSelector($("#AppId").val(),pvTxtId,$("#"+pvTxtId).val(),true);
}
</script>