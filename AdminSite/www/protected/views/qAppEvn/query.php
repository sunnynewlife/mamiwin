<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">活动信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form class="form_search" id="formSearchSec" method="post" action="/qAppEvn/query?idx=<?php echo $EVN_IDX;?>" >
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;height:30px;">活动名称</td>
						<td><?php echo $QEvnItem["EvnName"];?></td>
						<td style="width:100px;">活动时间</td>
						<td>
							<input type='text' maxlength='20' class='dateInputBind start' name="startDt" value="<?php echo $QEvnItem["EvnStart"];?>" style="width:80px;" readonly />
							&nbsp;&nbsp;~&nbsp;&nbsp;
							<input type='text' maxlength='20' class='dateInputBind end' name="endDt" value="<?php echo $QEvnItem["EvnEnd"];?>" style="width:80px;" readonly />						
						</td>
					</tr>
					<tr>
						<td style="width:120px;height:40px;">游戏名称</td>
						<td><?php echo $QEvnItem["AppName"];?></td>
						<td id="showProrateTag">基础返利:</td>
						<td><?php echo $QEvnItem["BaseProrate"]."%"; ?> </td>
					</tr>
					<tr>
						<td>活动类型</td>
						<td>
							<select name="EvnType"  id="EvnType" class="input-xlarge"  onchange="javascript:changeEvnType();" disabled>
								<option value="1" <?php echo $QEvnItem["EvnType"]=="1"?" selected ":"" ?>>非阶梯式返利</option>
								<option value="2" <?php echo $QEvnItem["EvnType"]=="2"?" selected ":"" ?>>阶梯式返利</option>
							</select>	
						</td>
						<td>活动对象</td>
						<td>
							<select name="EvnJoinType" class="input-xlarge" disabled>
								<option value="1" <?php echo $QEvnItem["EvnJoinType"]=="1"?" selected ":"" ?>>全平台用户</option>
								<option value="2" <?php echo $QEvnItem["EvnJoinType"]=="2"?" selected ":"" ?>>报名用户 </option>
							</select>	
						</td>
					</tr>
					<tr style="display:<?php echo $QEvnItem["EvnType"]=="1"?"":"none"; ?>;">
						<td style="height:40px;">人数要求</td>
						<td><?php echo $QEvnItem["EvnQty"];?></td>
						<td>活动返利</td>
						<td><?php echo $QEvnItem["Prorate"];?>%</td>
					</tr>
				</table>
				<table class="tableApp" style="width:950px;display:<?php echo $QEvnItem["EvnType"]=="2"?"block":"none"; ?>;" id="complexEvn">
					<tr>
						<td colspan=5 style="background:darkgray;text-align:center;">阶梯返利</td>
					</tr>
					<tr>
						<td style="width:60px;">序号</td>
						<td style="width:180px;">要求人数</td>
						<td style="width:180px;">返利（%）</td>
					</tr>
				</table>
				<br/>
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;height:30px;">查询账号</td>
						<td style="width:240px;"><input type="text" maxlength="50" name="PhoneNo" id="PhoneNo" value="<?php echo $PhoneNo;?>" class="input-xSmall" autofocus="true" /></td>
						<td>
							<input type="hidden" name="export" id="export" value="" />
							<input type="hidden" name="idx" id="idx" value="<?php echo $EVN_IDX;?>" />
							<button type="submit" class="btn btn-primary">
								<strong>查询</strong>
							</button>
						<?php if(count($joiner)>0) { ?>							
							<button type="button" class="btn btn-primary" onclick="exportCSV();">
								<strong>导出</strong>
							</button>
						<?php } ?>
						</td>
					</tr>	
				</table>			
			</form>
		</div>
		<table class="table table-striped" style="width:600px;">
			<thead>
				<tr>
					<th style="width: 180px;">参与账号</th>
					<th style="width: 160px;">参加时间</th>
					<th style="width: 160px;">参与IP</th>
				</tr>
			</thead>
			<tbody>
<?php
$rowsHtmlTag = <<<EndOfRowTag
			<tr>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
			</tr>
EndOfRowTag;
			foreach ($joiner as $item){
				echo sprintf($rowsHtmlTag,$item["PhoneNo"],$item["CreateDt"],$item["ClientIp"]);
			}
			?>
			</tbody>
		</table>
		<?php echo $page;?>		
	</div>
</div>
<?php $this->widget('application.widget.AppSelector'); ?>

<script type="text/javascript">
function initAppProrateShown()
{
	var appId="<?php echo $QEvnItem["AppId"];?>";
	for( var i=0;i<_APP_LIST.length;i++){
		if(_APP_LIST[i].AppId==appId){
			$("#showProrateTag").html("基础返利:"+_APP_LIST[i].Prorate*100+"%");
			break;
		}
	}	
}
<?php
	foreach ($QEvnItem["AdvDynProrate"] as $aItem){
		echo	sprintf("addProrateRow('%s','%s');\n",$aItem["qty"],bcmul($aItem["prorate"], "100",0));
	} 
?>

function addProrateRow(pvQty,pvProrate)
{
	var currentId=(new Date().getTime());
	var rowCount=document.getElementById("complexEvn").rows.length;
	var lvNoId=1+rowCount-2;
	var newRow = "<tr id='tr_"+currentId+"'><td>"+lvNoId+"</td><td><input type='text' maxlength='50' value='"+pvQty+"' class='input-xlarge' readonly style='width:140px;' /></td><td><input type='text' maxlength='50' value='"+pvProrate+"' class='input-xlarge' readonly style='width:140px;' /></td></tr>";
	$("#complexEvn tr:last").after(newRow);
}
function exportCSV()
{
	$("#export").val(1);
	$(".form_search").submit();
	$("#export").val(0);

}
</script>