<link rel="stylesheet" type="text/css" href="/static/css/jquery.jqplot.min.css" />
<script src="/static/js/lib/jquery.jqplot.min.js" ></script>

<div id="search" class="in collapse">
<form class="form_search"  action="/lunaAccessLog/siteProfile" method="POST" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<input type="text" name="SiteName" value="<?php echo $SiteName;?>" placeholder="输入查询的站点域名" required=true >
		<input type="text" name="ServerIp" value="<?php echo $ServerIp;?>" placeholder="服务器IP" >
		<br/>
		<select name="logLevel" style="width:98px">
			<option value="" <?php echo empty($logLevel)?" selected":""; ?>>All</option>
			<option value="NOTICE" <?php echo $logLevel=="NOTICE"?" selected":""; ?>>NOTICE</option>
			<option value="WARNING" <?php echo $logLevel=="WARNING"?" selected":""; ?>>WARNING</option>
			<option value="ERROR" <?php echo $logLevel=="ERROR"?" selected":""; ?>>ERROR</option>
			<option value="FATAL" <?php echo $logLevel=="FATAL"?" selected":""; ?>>FATAL</option>
			<option value="DEBUG" <?php echo $logLevel=="DEBUG"?" selected":""; ?>>DEBUG</option>
			<option value="INFO" <?php echo $logLevel=="INFO"?" selected":""; ?>>INFO</option>
		</select>
		<select name="ModuleType" style="width:118px">
			<option value="Uri" <?php echo $ModuleType=="Uri"?" selected":"";?>>网站服务地址</option>
			<option value="LunaPdo" <?php echo $ModuleType=="LunaPdo"?" selected":"";?>>数据库访问</option>
			<option value="HttpInterface" <?php echo $ModuleType=="HttpInterface"?" selected":"";?>>外部Http接口访问</option>
			<option value="hps" <?php echo $ModuleType=="hps"?" selected":"";?>>外部hps接口访问</option>
		</select>
		<input type="text" name="RequestUri" value="<?php echo $RequestUri;?>" placeholder="请求Uri / Sql特征 / 外部接口Http Uri特征" style="width:500px;" >
		<br/>
		<input type="text" name="RequestTimeMin" value="<?php echo $RequestTimeMin;?>" placeholder="请求时间"  style="width:200px;">~
		<input type="text" name="RequestTimeMax" value="<?php echo $RequestTimeMax;?>" placeholder="请求时间" style="width:200px;">
		
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:118px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped" style="width:600px;">
			<thead>
				<tr>
					<th style="width:200px;text-align:center">统计指标</th>
					<th style="width:200px;text-align:center;">指标值</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>访问次数</td>
					<td style="text-align:right;"><?php echo $Summary_Count;?></td>
				</tr>
				<tr>
					<td>平均耗时</td>
					<td style="text-align:right;"><?php echo $Summary_Avg;?></td>
				</tr>
				<tr>
					<td>最大耗时</td>
					<td style="text-align:right;"><?php echo $Summary_Max;?></td>
				</tr>
				<tr>
					<td>最小耗时</td>
					<td style="text-align:right;"><?php echo $Summary_Min;?></td>
				</tr>
			</tbody>
		</table>	
	</div>
	<div id="gameProfit" style="height:600px; width:100%;"></div>
</div>
<script class="code" type="text/javascript">
$(document).ready(function(){
  var plot1 = $.jqplot ('gameProfit', 
			[[<?php echo $YAxis;?>]],

		  	{ 
	  			title:{text:'<?php echo $SiteName;?> 性能图',color:'#FF0000'},
	  			series:[{color:'#FF0000'}],
	  			axes:{
		  			xaxis:{
		  				show:true,
		  				ticks:[<?php echo $XAxis;?>],
		  				tickOptions:{showMark:true,showLabel:false}
		  			},
		  			yaxis:{
		  				show:true,
		  				tickOptions:{showMark:true,showLabel:true,showGridline:true}
			  		}
	  			}
			}
		);
});
</script>