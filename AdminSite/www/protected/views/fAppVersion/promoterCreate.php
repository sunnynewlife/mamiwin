<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">游戏版本批量预打包</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="frmTask" method="post" onsubmit="return checkForm();">
				<table class="tableApp" style="width:855px;">
					<tr>
						<td style="width:120px;">游戏名称*</td>
						<td><input type="text" maxlength="50" name="AppName" id="AppName" value="<?php echo $AppVersion["AppName"];?>" class="input-xlarge" required="true" autofocus="true" readonly /></td>
						<td style="width:100px;">游戏APPID*</td>
						<td><input type="text" maxlength="50" name="AppId" readonly id="AppId" value="<?php echo $AppVersion["AppId"];?>" class="input-xlarge" required="true" autofocus="true" /></td>
					</tr>
					<tr>
						<td>发布版本* </td>
						<td><input type="text" maxlength="50" name="VersionName" value="<?php echo $AppVersion["VersionName"]; ?>"  class="input-xlarge" autofocus="true" required="true" readonly /></td>
						<td>版本ID* </td>
						<td><input type="text" maxlength="50" name="AppVersionId" value="<?php echo $AppVersion["AppVersionId"]; ?>"  class="input-xlarge" autofocus="true" required="true" readonly /></td>
					</tr>
					<tr>
						<td>预打包数量 </td>
						<td><input type="text" maxlength="50" name="PackageNum" value="<?php echo $PackageNum;?>"  class="input-xlarge" autofocus="true" required="true" /></td>
						<td>发布到CDN</td>
						<td><input type="checkbox" id="PublishCDN" name="PublishCDN" value="1" /></td>
					</tr>
					<tr>
						<td colspan=4>
						<?php
						$rowsHtmlTag=<<<EndOfRowTag
<table style="width:850px;">
	<tr>
		<td style="width:60px;">请求时间:</td>
		<td style="width:140px;">%s</td>
		<td style="width:90px;">起始标识序号:</td>
		<td style="width:40px;">%s</td>
		<td style="width:60px;">打包个数:</td>
		<td style="width:40px;">%s</td>
		<td style="width:60px;">完成个数:</td>
		<td style="width:40px;">%s</td>	
		<td style="width:60px;">最后更新:</td>
		<td>%s</td>		
		<td>%s</td>
	</tr>
</table>
EndOfRowTag;
						
							if(count($PackingLog)>0){
								$showAllHtml="";
								if(count($PackingLog)>1){
									$showAllHtml=sprintf("&nbsp;&nbsp;<a href='javascript:switchLogHistory();'>显示/隐藏全部</a>");
								}
								echo sprintf($rowsHtmlTag,$PackingLog[0]["CreateDt"],
										$PackingLog[0]["PromotionNoStart"],
										$PackingLog[0]["RequestCount"],$PackingLog[0]["FinishCount"],$PackingLog[0]["UpdateDt"],$showAllHtml);			

							}else {
								echo "无批量打包记录 ";
							}
						?>
						</td>
					</tr>
				</table>
				<br/>
				<table id="packingLogHistory" class="tableApp" style="width:815px;display:;">
					<tr>
						<td>请求时间</td>
						<td>起始标识序号</td>
						<td>请求打包个数</td>
						<td>完成个数</td>
						<td>最后完成时间</td>
					</tr>
					<?php
						foreach ($PackingLog  as $row){
							echo  sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>",$row["CreateDt"],$row["PromotionNoStart"],$row["RequestCount"],$row["FinishCount"],$row["UpdateDt"]);
						}					 
					?>
				</table>
				
				<input type="hidden" name="submit" value="1" />
				<ul class="nav nav-tabs" style="border-bottom:0px;">
					<li style="padding-top:5px;"><a href="<?php echo $back_url;?>">放弃</a></li>
					<li> 
						<div class="btn-toolbar">
							<button type="submit" class="btn btn-primary" >
								<strong>批量预打包此版本</strong>
							</button>
						</div>
					</li>
				</ul>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
function switchLogHistory()
{
	$("#packingLogHistory").toggle();
}
function checkForm()
{
	if($("#PublishCDN").is(':checked')){
		return confirm("请确认本次预打包发布到CDN吗？");
	}
	return true;
}
</script>
