<div class="btn-toolbar">
	<a href="/fAppAgencySubscribe/add" class="btn btn-primary"><i class="icon-plus"></i>增加订阅者</a>
	<a href="/fAppAgencySubscribe/reload" class="btn btn-primary"><i class="icon-refresh"></i>服务重新加载业务参数</a>
</div>
<div id="search" class="in collapse">
<form class="form_search"  action="/fAppAgencySubscribe/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<table class="tableApp" style="width:815px;">
			<tr>
				<td style="width:120px;">休眠时间：</td>
				<td><input type='text' maxlength='20'  name="SleepTime" value="<?php echo $ServerCfg["sleep_seconds"];?>" /></td>
				<td>无新消息处理时,访问Kafka消息队列间隔时间。单位：秒</td>
			</tr>
			<tr>
				<td>重试次数：</td>
				<td><input type='text' maxlength='20'  name="TryCount" value="<?php echo  $ServerCfg["try_count"];?>" /></td>
				<td>推送给订阅者，最多尝试次数。</td>
			</tr>
			<tr>
				<td>服务统计间隔：</td>
				<td><input type='text' maxlength='20'  name="ReportPeriod" value="<?php echo  $ServerCfg["report_period"];?>" /></td>
				<td>服务推送情况统计记录时间间隔。单位:秒。</td>
			</tr>
			<tr>
				<td></td>
				<td style="text-align:right;">
					<input type="hidden" name="search" value="1" >
					<button type="submit" class="btn btn-primary">保存服务参数</button>
				</td>
				<td></td>
			</tr>
			
		</table>
	</div>
	<div style="clear:both;"></div>
</form>
</div>

<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">数据订阅服务</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>商户名称</th>
					<th>商户登录ID</th>
					<th>配置名称-系统</th>
					<th>配置名称-接口</th>
					<th width="80px">操作</th>
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
	<td>
		<a href="/fAppAgencySubscribe/add?agencyId=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a data-toggle="modal" href="#myModal" title="删除"><i class="icon-remove" href="/fAppAgencySubscribe/del?agencyId=%s"></i></a>
	</td>
<tr>
EndOfRowTag;
			foreach ($AgencyCfg as $key => $row){
				$rowHtml=sprintf($rowsHtmlTag,
							$row["name"],
							$key,
							$row["System"],
							$row["Interface"],
							$key,$key
						);
				echo $rowHtml;
			}
			?>
			</tbody>
		</table>
		<p>推送服务最近1条数据统计(自服务启动后累计情况)</p>
		<p>服务启动时间:<font color=red><?php echo isset($ServerStat["server_start"])?$ServerStat["server_start"]:"";?></font>
			<br/>
			业务报告时间:<font color=red><?php echo isset($ServerStat["reprot_time"])?$ServerStat["reprot_time"]:"";?></font>
			<br/>
			配置加载时间:<font color=red><?php echo isset($ServerStat["biz_cfg_load_time"])?$ServerStat["biz_cfg_load_time"]:"";?></font>
		</p>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>商户登录ID</th>
					<th>推送消息总数</th>
					<th>最后推送时间</th>
				</tr>
			</thead>
			<tbody>
<?php
$rowsSubscriberTag=<<<EndOfSubRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
<tr>
EndOfSubRowTag;
			if(isset($ServerStat["subscriber"])){
				foreach ($ServerStat["subscriber"] as $key => $row){
					echo sprintf($rowsSubscriberTag,$key,$row["msg_count"],$row["last_msg_time"]);
				}
			}
?>						
			</tbody>
		</table>
	</div>
</div>

<script>
	$('.icon-remove').click(
		function() 
		{
			var href=$(this).attr('href');
			bootbox.confirm('确定要这样做吗？', 
					function(result) 
					{
						if(result){
							location.replace(href);
						}
					});		
		});
</script>
