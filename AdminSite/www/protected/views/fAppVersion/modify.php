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
						<td style="width:120px;">版本ID</td>
						<td><input type="text" maxlength="50" name="AppVersionId" readonly id="AppVersionId" value="<?php echo $AppVersion["AppVersionId"];?>" class="input-xlarge" required="true" autofocus="true" /></td>
						<td>版本名称*</td>
						<td><input type="text" maxlength="50" name="VersionName" value="<?php echo $AppVersion["VersionName"];?>" class="input-xlarge" autofocus="true" required="true" /></td>
					</tr>
					<tr>
						<td>游戏大小*</td>
						<td><input type="text" maxlength="50" name="GameSize" value="<?php echo $AppVersion["GameSize"]; ?>" class="input-large" autofocus="true" required="true" />&nbsp;M</td>
						<td>游戏截图*</td>
						<td><input type="text" maxlength="50" name="GamePics" value="<?php echo $AppVersion["GamePics"]; ?>" id="GamePics" onclick="javascript:selectPics('GamePics');" class="input-xlarge" autofocus="true" required="true" /></td>
					</tr>
					<tr>
						<td>版本上架状态*</td>
						<td>
							<select name="State" class="input-xlarge">
								<option value="0" <?php echo $AppVersion["State"]==0?" selected ":" " ?>>测试</option>
								<option value="1" <?php echo $AppVersion["State"]==1?" selected ":" " ?>>上架</option>
								<option value="2" <?php echo $AppVersion["State"]==2?" selected ":" " ?>>下架</option>
							</select>
						</td>
						<td>测试白名单</td>
						<td><input type="text" maxlength="50000" name="TestPhone" value="<?php echo $AppVersion["TestPhone"];?>" class="input-xlarge" autofocus="true" /></td>						
					</tr>
					<tr>
						<td>版本母包状态:</td>
						<td><?php echo $AppVersion["PackageStateName"];?></td>
						<?php
							switch ($AppVersion["PackageState"])
							{
								case 0:
									echo "<td>母包文件名:</td><td><input type=\"text\" maxlength=\"50\" name=\"PackagePath\" value=\"\" class=\"input-xlarge\" autofocus=\"true\" required=\"true\" /></td>";
									break;
								case 1:
									echo "<td>母包文件名:</td><td><input type=\"text\" maxlength=\"50\" name=\"PackagePath\" value=\"".$AppVersion["PackagePath"]."\" class=\"input-xlarge\" autofocus=\"true\" required=\"true\" /></td>";
									break;
								case 2:
								case 3:
									echo "<td>原始包路径:</td><td><input type=\"text\" name=\"PackagePath\" readonly id=\"PackagePath\" value=\"".$AppVersion["PackagePath"]."\" class=\"input-xlarge\"  /></td>";
									break;
								default:
									echo "<td></td><td></td>";
									break;
							}
						?>
					</tr>
					<tr>
						<td>当前推广版本</td>
						<td>
							<select name="IsPublishVersion" class="input-xlarge">
								<option value="0" <?php echo $AppVersion["IsPublishVersion"]==0?" selected ":" " ?>>不推广</option>
								<option value="1" <?php echo $AppVersion["IsPublishVersion"]==1?" selected ":" " ?>>推广</option>
							</select>
						</td>
<?php
						if(count($OtherVersions)>0){
							echo "<td>替换为:</td><td><select name=\"App_AppVersionId\" class=\"input-xlarge\">";
								echo "\t<option value=\"NO\">不替换</option>\n";
								foreach ($OtherVersions as $rowItem){
									echo sprintf("\t<option value=\"%s\" %s>%s</option>\n",$rowItem["AppVersionId"],($AppVersion["App_AppVersionId"]==$rowItem["AppVersionId"]?" selected ":""),$rowItem["VersionName"]);
								}							
							echo "</select></td>";
						}else{
							echo "<td></td><td></td>";
						}
?>						
					</tr>
					<tr>
						<td>MD5签名值</td>
						<td><input type="text" maxlength="50" name="PackageMd5" value="<?php echo $AppVersion["PackageMd5"];?>" class="input-xlarge" autofocus="true" required="true" <?php echo ($AppVersion["PackageState"]==2 || $AppVersion["PackageState"]==3)?" readonly ":" "; ?> /></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>版本包下载地址</td>
						<td colspan=3><input type="text" name="VersionPackagePath" value="<?php echo $AppVersion["PackageUrl"];?>"  style="width:540px;" class="input-xlarge" autofocus="true" readonly /></td>
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