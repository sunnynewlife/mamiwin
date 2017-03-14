<div class="btn-toolbar">
	<a href="/lunaAccessLog/siteProfile" class="btn btn-primary"><i class="icon-bookmark"></i>网站性能图</a>
</div>
<div id="search" class="in collapse">
<form class="form_search"  action="/lunaAccessLog/index" method="POST" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<input type="text" name="SiteName" value="<?php echo $SiteName;?>" placeholder="输入查询的站点域名" required=true > <br/>
		<input type="text" name="UserIp" value="<?php echo $UserIp;?>" placeholder="用户IP" >
		<input type="text" name="ServerIp" value="<?php echo $ServerIp;?>" placeholder="服务器IP" >
		<input type="text" name="SessionId" value="<?php echo $SessionId;?>" placeholder="用户会话SessionId" > 
		<input type="text" name="RecGUID" value="<?php echo $RecGUID;?>" placeholder="用户RequestId" >
		<br/>
		
		<select name="logLevel" style="width:100px">
			<option value="" <?php echo empty($logLevel)?" selected":""; ?>>All</option>
			<option value="NOTICE" <?php echo $logLevel=="NOTICE"?" selected":""; ?>>NOTICE</option>
			<option value="WARNING" <?php echo $logLevel=="WARNING"?" selected":""; ?>>WARNING</option>
			<option value="ERROR" <?php echo $logLevel=="ERROR"?" selected":""; ?>>ERROR</option>
			<option value="FATAL" <?php echo $logLevel=="FATAL"?" selected":""; ?>>FATAL</option>
			<option value="DEBUG" <?php echo $logLevel=="DEBUG"?" selected":""; ?>>DEBUG</option>
			<option value="INFO" <?php echo $logLevel=="INFO"?" selected":""; ?>>INFO</option>
		</select>
		<select name="ModuleType" style="width:100px">
			<option value="" <?php echo empty($ModuleType)?" selected":""; ?>>All</option>
			<option value="LunaPdo" <?php echo $ModuleType=="LunaPdo"?" selected":""; ?>>LunaPdo</option>
			<option value="HttpInterface" <?php echo $ModuleType=="HttpInterface"?" selected":""; ?>>HttpInterface</option>
			<option value="hps" <?php echo $ModuleType=="hps"?" selected":""; ?>>hps</option>
			<option value="other" <?php echo $ModuleType=="other"?" selected":""; ?>>other</option>
		</select>
		<input type="text" name="ExecuteDurationMin" value="<?php echo $ExecuteDurationMin;?>" placeholder="耗时(秒)"  style="width:97px;">~
		<input type="text" name="ExecuteDurationMax" value="<?php echo $ExecuteDurationMax;?>" placeholder="耗时(秒)" style="width:97px;">
		<input type="text" name="RequestTimeMin" value="<?php echo $RequestTimeMin;?>" placeholder="请求时间"  style="width:130px;">~
		<input type="text" name="RequestTimeMax" value="<?php echo $RequestTimeMax;?>" placeholder="请求时间" style="width:130px;">
		
		<br/>
		<input type="text" name="RequestUri" value="<?php echo $RequestUri;?>" placeholder="请求Uri" style="width:500px;" >
		<br/>
		<input type="text" name="msg_1" value="<?php echo $msg_1;?>" placeholder="字段1" >
		<input type="text" name="msg_2" value="<?php echo $msg_2;?>" placeholder="字段2" >
		<input type="text" name="msg_3" value="<?php echo $msg_3;?>" placeholder="字段3" >
		<input type="text" name="msg_4" value="<?php echo $msg_4;?>" placeholder="字段4" >
		
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:195px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block" style="width:2000px;">
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:180px;">RequestUri</th>
					<th style="width:135px;">请求时间</th>
					<th style="width:80px;text-align:right;">执行时长</th>
					<th style="width:90px;">用户IP</th>
					<th style="width:90px;">服务器IP</th>
					<th style="width:210px;">SessionId</th>
					<th style="width:360px;">RequestId</th>
					<th>请求参数</th>
				</tr>
			</thead>
			<tbody>
<?php
	$rowsUriLog=<<<EndOfRowUriTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td style="text-align:right;">%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
</tr>
EndOfRowUriTag;
		foreach ($AccessLogList as $requestId => $requestUriLog){
			echo sprintf($rowsUriLog,
					$requestUriLog["RequestUri"],$requestUriLog["RequestTime"], $requestUriLog["ExecuteDuration"],
					$requestUriLog["UserIp"],$requestUriLog["ServerIp"],
					$requestUriLog["SessionId"],$requestId,$requestUriLog["RequestParameter"]);
		}	
?>			
			</tbody>
		</table>	
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:110px;">SessionId</th>
					<th style="width:170px;">RequestId</th>
					<th style="width:65px;">LogLevel</th>
					<th style="width:80px;">LogModule</th>
					<th style="width:80px;text-align:right;">执行耗时</th>
					<th>字段1</th>
					<th style="width:200px;">字段2</th>
					<th style="width:500px;">字段3</th>
					<th style="width:80px;">字段4</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td style="text-align:right;">%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>			
<tr>
EndOfRowTag;
	
			foreach ($AccessLogDetailList as $detaiLogRow){
				echo sprintf($rowsHtmlTag,
					$detaiLogRow["SessionId"],$detaiLogRow["RecGUID"],$detaiLogRow["LevelType"],
					$detaiLogRow["LogModelType"],$detaiLogRow["ModelDuration"],$detaiLogRow["msg_1"],
					$detaiLogRow["msg_2"],$detaiLogRow["msg_3"],$detaiLogRow["msg_4"]);
			}
			?>
			</tbody>
		</table>
		<?php echo $page;?>
	</div>
</div>