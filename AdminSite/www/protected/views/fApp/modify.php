<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">游戏信息配置</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="" onsubmit="return checkSubmit();">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">游戏名称*</td>
						<td><input type="text" maxlength="50" name="AppName" id="AppName" value="<?php echo $App["AppName"];?>" class="input-xlarge" required="true" autofocus="true" /></td>
						<td style="width:100px;">游戏APPID*</td>
						<td><input type="text" maxlength="50" name="AppId" readonly id="AppId" value="<?php echo $App["AppId"];?>" class="input-xlarge" required="true" autofocus="true" /></td>
					</tr>
					<tr>
						<td>开发商</td>
						<td><input type="text" maxlength="50" name="Developer" value="<?php echo $App["Developer"];?>" class="input-xlarge" autofocus="true" /></td>
						<td>运营商</td>
						<td><input type="text" maxlength="50" name="Publisher" value="<?php echo $App["Publisher"];?>" class="input-xlarge" autofocus="true" /></td>
					</tr>
					<tr>
						<td>游戏类型*</td>
						<td>
							<select name="AppType" class="input-xlarge">
							<?php
							foreach ($game_type as $item){
								echo sprintf("<option value=\"%s\" %s >%s</option>",$item["code"],($App["AppType"]==$item["code"]?" selected ":" "),$item["name"]);
							} 
							?>
							</select>	
						</td>
						<td>LOGO图片*</td>
						<td><input type="text" maxlength="50" name="FileId" value="<?php echo $App["FileId"];?>"  id="FileId" onclick="javascript:selectPics('FileId',false);" class="input-xlarge" autofocus="true" required="true" /></td>
					</tr>
					<tr>
						<td>推荐指数*</td>
						<td>
							<select name="RecommendIndex" class="input-xlarge">
<?php
								foreach ($game_data_index as $itemKey => $itemValue){
									echo sprintf("<option value=\"%s\" %s>%s</option>",$itemKey,($App["RecommendIndex"]==$itemKey?" selected ":""),$itemValue);
								} 
?>							
							</select>
						</td>
						<td>流行指数*</td>
						<td>
							<select name="PopularIndex" class="input-xlarge">
<?php
								foreach ($game_data_index as $itemKey => $itemValue){
									echo sprintf("<option value=\"%s\" %s>%s</option>",$itemKey,($App["PopularIndex"]==$itemKey?" selected ":""),$itemValue);
								} 
?>							
							</select>						
						</td>
					</tr>
					<tr>
						<td>留存指数*</td>
						<td>
							<select name="RemainIndex" class="input-xlarge">
<?php
								foreach ($game_data_index as $itemKey => $itemValue){
									echo sprintf("<option value=\"%s\" %s>%s</option>",$itemKey,($App["RemainIndex"]==$itemKey?" selected ":""),$itemValue);
								} 
?>							
							</select>
						</td>
						<td>付费指数*</td>
						<td>
							<select name="PayIndex" class="input-xlarge">
<?php
								foreach ($game_data_index as $itemKey => $itemValue){
									echo sprintf("<option value=\"%s\" %s>%s</option>",$itemKey,($App["PayIndex"]==$itemKey?" selected ":""),$itemValue);
								} 
?>							
							</select>						
						</td>
					</tr>
					<tr>
						<td>新手难度*</td>
						<td>
							<select name="BeginnerLevel" class="input-xlarge">
<?php
								foreach ($game_data_index as $itemKey => $itemValue){
									echo sprintf("<option value=\"%s\" %s>%s</option>",$itemKey,($App["BeginnerLevel"]==$itemKey?" selected ":""),$itemValue);
								} 
?>							
							</select>
						</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>关键字</td>
						<td colspan=3>
							<select name="LabelTag1" style="width:130px;">
								<option value=""></option>
<?php
								foreach ($game_tag as $keyLabel){
									echo sprintf("<option value=\"%s\" %s>%s</option>",$keyLabel,($App["LabelTag1"]==$keyLabel?" selected ":""),$keyLabel);
								} 
?>									
							</select>
							<select name="LabelTag2" style="width:130px;">
								<option value=""></option>
<?php
								foreach ($game_tag as $keyLabel){
									echo sprintf("<option value=\"%s\" %s>%s</option>",$keyLabel,($App["LabelTag2"]==$keyLabel?" selected ":""),$keyLabel);
								} 
?>									
							</select>	
							<select name="LabelTag3" style="width:130px;">
								<option value=""></option>
<?php
								foreach ($game_tag as $keyLabel){
									echo sprintf("<option value=\"%s\" %s>%s</option>",$keyLabel,($App["LabelTag3"]==$keyLabel?" selected ":""),$keyLabel);
								} 
?>																	
							</select>	
							<select name="LabelTag4" style="width:130px;">
								<option value=""></option>
<?php
								foreach ($game_tag as $keyLabel){
									echo sprintf("<option value=\"%s\" %s>%s</option>",$keyLabel,($App["LabelTag4"]==$keyLabel?" selected ":""),$keyLabel);
								} 
?>																	
							</select>	
							<select name="LabelTag5" style="width:130px;">
								<option value=""></option>
<?php
								foreach ($game_tag as $keyLabel){
									echo sprintf("<option value=\"%s\" %s>%s</option>",$keyLabel,($App["LabelTag5"]==$keyLabel?" selected ":""),$keyLabel);
								} 
?>																	
							</select>								
						</td>
					</tr>
					
					<tr>
					<td>版本名称*</td>
						<td><input type="text" maxlength="50" name="VersionName" value="<?php echo $AppVersion["VersionName"]; ?>" class="input-xlarge" autofocus="true" required="true" /></td>
						<td>游戏大小*</td>
						<td><input type="text" maxlength="50" name="GameSize" value="<?php echo $AppVersion["GameSize"]; ?>" class="input-large" autofocus="true" required="true" />&nbsp;M</td>
					</tr>
					<tr>
						<td>游戏截图*</td>
						<td><input type="text" maxlength="50" name="GamePics" value="<?php echo $AppVersion["GamePics"]; ?>" id="GamePics" onclick="javascript:selectPics('GamePics',true);" class="input-xlarge" autofocus="true" required="true" /></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>游戏概述*</td>
						<td colspan="3"><textarea id="AppIntroduct" maxlength="300" name="AppIntroduct" rows="4" style="width:600px;" class="input-xlarge" required="true" autofocus="true"><?php echo $App["AppIntroduct"];?></textarea></td>
					</tr>
					<tr>
						<td>游戏详情*</td>
						<td colspan="3"><textarea id="AppDetail" maxlength="2000" name="AppDetail" rows="8" style="width:600px;" class="input-xlarge" required="true" autofocus="true"><?php echo $App["AppDetail"]; ?></textarea></td>
					</tr>
					<tr>
						<td>打包时提示</td>
						<td colspan="3"><textarea id="PromptTitle" maxlength="100" name="PromptTitle" rows="1" style="width:600px;" class="input-xlarge" autofocus="true"><?php echo $App["PromptTitle"];?></textarea></td>
					</tr>
					<tr>
						<td>打包文件名前缀*</td>
						<td><input type="text" maxlength="50" name="PackagePrefixName" value="<?php echo $App["PackagePrefixName"]; ?>"  class="input-xlarge" autofocus="true" required="true" <?php echo $App["RegistState"]==1?" readonly ":" " ?> /></td>
						<td>打包平台状态</td>
						<td>
							<select name="RegistState" disabled=true>
								<option value="0" <?php echo $App["RegistState"]==0?" selected ":" " ?>>未注册</option>
								<option value="1" <?php echo $App["RegistState"]==1?" selected ":" " ?>>已注册</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>打包游戏名*(英文)</td>
						<td><input type="text" maxlength="50" name="AppNameEn" value="<?php echo $App["AppNameEn"];?>"  class="input-xlarge" autofocus="true" required="true" /></td>
						<td colspan=2><font color=red>英文字符，不含有其他字符和符号</font></td>
					</tr>
					<tr>
						<td>游戏状态</td>
						<td>
							<select name="AppStatus" class="input-xlarge">
								<option value="0" <?php echo $App["AppStatus"]==0?" selected ":" " ?>>测试</option>
								<option value="1" <?php echo $App["AppStatus"]==1?" selected ":" " ?>>上架</option>
								<option value="2" <?php echo $App["AppStatus"]==2?" selected ":" " ?>>下架</option>
							</select>
						</td>
						<td>测试白名单</td>
						<td><input type="text" maxlength="50000" name="TestPhone" value="<?php echo $App["TestPhone"];?>" class="input-xlarge" autofocus="true" /></td>
					</tr>
					<tr>
						<td>红利信息*</td>
						<td colspan="3"><input type="text" maxlength="200" name="ProfitUrl" value="<?php echo $App["ProfitUrl"]; ?>" class="input-xlarge" autofocus="true" required="true" style="width:600px;" /></td>
					</tr>
					<tr>
						<td>游戏活动地址</td>
						<td colspan="3"><input type="text" maxlength="200" name="EventUrl" value="<?php echo $App["EventUrl"]; ?>" class="input-xlarge" autofocus="true"  style="width:600px;" /></td>
					</tr>
					<tr>
						<td>开发商分成比例*</td>
						<td><input type="text" maxlength="50" id="DeveloperProrate" name="DeveloperProrate" value="<?php echo bcmul($App["DeveloperProrate"],"100",0); ?>" class="input-xlarge" autofocus="true" required="true" /></td>
						<td><font color=red>值在1-100之间</font></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>成本费率*</td>
						<td><input type="text" maxlength="50" name="CostFeePercent" value="<?php echo $App["CostFeePercent"];?>" class="input-small" autofocus="true" required="true" /></td>
						<td colspan=2><font color=red>数值。如:0.015 表示1.5%成本。开发商在此费率扣除后计算分成</font></td>
					</tr>
					<tr>
						<td>排列顺序*</td>
						<td><input type="text" maxlength="50" name="SortIndex" value="<?php echo $App["SortIndex"];?>" class="input-small" autofocus="true" required="true" /></td>
						<td colspan=2><font color=red>值为数字,值小排列在前面</font></td>
					</tr>
					<tr>
						<td>游戏自动打包阀值*</td>
						<td><input type="text" maxlength="50" name="MinPackingPoolSize" value="<?php echo $App["MinPackingPoolSize"];?>" class="input-xLarge" autofocus="true" required="true" /></td>
						<td colspan=2><font color=red>值为>0数值，游戏的推广版本推广包最新可领取库存量</font></td>						
					</tr>
				</table>
			
				<table class="table table-striped" style="width:815px;">
					<tr><td>返利信息配置</td></tr>
				</table>
				<table class="tableApp" style="width:815px;" id="prorateTable">
					<tr>
						<td style="width:220px;">开始时间(<font color=red>保含当天</font>)</td>
						<td style="width:220px;">结束时间(<font color=red>包含当天</font>)</td>
						<td style="width:230px;">返利比例(<font color=red>值在1-100之间</font>)</td>
						<td style="width:65px;">
							<input type="button" value="增加" onclick="javascript:addProrate();" />
						</td>
						<td style="width:80px;">
							<input type="button" value="检查&保存" onclick="javascript:CheckSaveProrate();" />
						</td>
					</tr>
<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td><input type='text' maxlength='20' class='dateInputBind start %s' required='true' value='%s' /></td>
	<td><input type='text' maxlength='20' class='dateInputBind end %s'   required='true' id='dateInputBind_%s' value='%s' /></td>
	<td><input type='text' maxlength='20' class='ProratePercent %s' required='true' id='ProratePercent_%s' value='%s' /></td>
	<td><a onclick='javascript:deleteRow(this);'>删除</a></td><td>&nbsp;</td>
<tr>
EndOfRowTag;
					
					$currentTime=time();
					foreach ($ProrateHistory as $rowItem){
						echo sprintf($rowsHtmlTag,
								$currentTime,substr($rowItem["StartDt"], 0,10),
								$currentTime,$currentTime,substr($rowItem["EndDt"], 0,10),
								$currentTime,$currentTime,(int)($rowItem["PromoterProrate"]*100)	
							);
						$currentTime++;
					}
					
?>
				</table>
			
				<br/>
				<br/>
				<table class="table table-striped" style="width:815px;">
					<tr><td>分享信息</td></tr>
				</table>
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">微信分享-标题</td>
						<td><input type="text" maxlength="50" name="WeixinTitle" value="<?php echo $App["WeixinTitle"];?>" class="input-xlarge" required="true" autofocus="true" /></td>
						<td style="width:120px;">微信分享-图片</td>
						<td><input type="text" maxlength="50" name="WeixinPic" id="WeixinPic" value="<?php echo $App["WeixinPic"];?>" class="input-xlarge" required="true" autofocus="true" onclick="javascript:selectPics('WeixinPic',false)" /></td>
					</tr>
					<tr>
						<td>微信分享-内容</td>
						<td colspan=3><textarea id="WeixinContent" maxlength="300" name="WeixinContent" rows="3" style="width:600px;" class="input-xlarge" required="true" autofocus="true"><?php echo $App["WeixinContent"];?></textarea></td>
					</tr>
					<tr>
						<td>微博分享-文字</td>
						<td colspan=3><textarea id="WeiBoContent" maxlength="300" name="WeiBoContent" rows="3" style="width:600px;" class="input-xlarge" required="true" autofocus="true"><?php echo $App["WeiBoContent"];?></textarea></td>
					</tr>
					<tr>
						<td>微博分享-图片</td>
						<td colspan=3><input type="text" maxlength="50" name="WeiBoPic" id="WeiBoPic" onclick="javascript:selectPics('WeiBoPic',false);" value="<?php echo $App["WeiBoPic"];?>" class="input-xlarge" required="true" autofocus="true" /></td>
					</tr>
					<tr>
						<td>短信分享-文字</td>
						<td colspan=3><textarea id="PhoneMsg" maxlength="300" name="PhoneMsg" rows="3" style="width:600px;" class="input-xlarge" required="true" autofocus="true"><?php echo $App["PhoneMsg"];?></textarea></td>
					</tr>
				</table>
				
				<input type="hidden" name="submit" value="1" />
				<input type="hidden" name="prorateRateValue" value="" id="prorateRateValue" />
				<input type="hidden" name="prorateRateChange" value="" id="prorateRateChange"/>
				<input type="hidden" name="AppVersionId" value="<?php echo $AppVersion["AppVersionId"];?>" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary">
						<strong>提交</strong>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php $this->widget('application.widget.PicSelector'); ?>
<script type="text/javascript">
function selectPics(pvTxtId,CanMutilChoice)
{
	showPicSelector($("#AppId").val(),pvTxtId,$("#"+pvTxtId).val(),CanMutilChoice);
}
function bindDatePicker()
{
	$(".dateInputBind").each(function(){
		$(this).datepicker({
			dateFormat: "yy-mm-dd",
			defaultDate: "+0",
			dayNamesShort:['周日','周一', '周二', '周三', '周四', '周五', '周六'],
			dayNamesMin:['日','一', '二', '三', '四', '五', '六'],
			changeMonth: true,
			changeYear: true,
			monthNames:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
			monthNamesShort:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
			numberOfMonths: 1,
			yearRange: "-5:+5",
		});	
	});		
}
function addProrate()
{
	var currentId=(new Date().getTime());
	var newRow = "<tr><td><input type='text' maxlength='20' class='dateInputBind start "+currentId+"' required='true' /></td><td><input type='text' class='dateInputBind end "+currentId+"'  maxlength='20' required='true' id='dateInputBind_"+currentId+"' /></td><td><input type='text' maxlength='20' class='ProratePercent "+currentId+"' required='true' id='ProratePercent_"+currentId+"' /></td><td><a onclick='javascript:deleteRow(this);'>删除</a></td><td>&nbsp;</td></tr>";
	$("#prorateTable tr:last").after(newRow);
	bindDatePicker();
}
function deleteRow(p)
{
	$(p).parent().parent().remove();
}

function parseDate(str)
{
    return new Date(Date.parse(str.replace(/-/g,"/")));
}
function formatDate(fmt,pvDate)
{
	var o = {
	        "M+": pvDate.getMonth() + 1,  
	        "d+": pvDate.getDate(),  
	        "h+": pvDate.getHours(), 
	        "m+": pvDate.getMinutes(), 
	        "s+": pvDate.getSeconds(), 
	        "q+": Math.floor((pvDate.getMonth() + 3) / 3),  
	        "S": pvDate.getMilliseconds() 
	    };
	    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (pvDate.getFullYear() + "").substr(4 - RegExp.$1.length));
	    for (var k in o)
	    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
	    return fmt;	
}

function CheckSaveProrate()
{
	var prorates=[];
	var firstCheck=true;
	$(".dateInputBind").each(function(){
		var classNames=$(this).attr("class").split(" ");
		if(classNames.length>=3){
			if(classNames[1]=="start"){
				var rowId=classNames[2];
				var endId="#dateInputBind_"+rowId;
				var porateId="#ProratePercent_"+rowId;

				var startDate=$(this).val()+" 00:00:00";
				var endDate=$(endId).val()+" 23:59:59";
				var properateVal=$(porateId).val();

				if(startDate!="" && endDate!="" && properateVal!="" ){
					var startDt=parseDate(startDate);
					var endDt=parseDate(endDate);
					var iProrater=parseInt(properateVal);
					if(startDt==NaN || endDt==NaN || iProrater==NaN || iProrater.toString()!=properateVal || iProrater<1 || iProrater>=100 || startDt>endDt ){
						firstCheck=false;
						layer.alert('返利信息数据格式不正确，请确保结束时间大于开始时间、返利比例在1~100之间。', 8);
						return;
					}
					var lvProrate=[startDt,endDt,iProrater];
					prorates.push(lvProrate);
				}else{
					firstCheck=false;
					layer.alert('请输入返利信息配置', 8);
					return;
				}
			}
		}
	});

	if(firstCheck==false){
		return false;
	}
	if(prorates.length>0){
		var submitPororateValue="";
		for(var i=0;i<prorates.length;i++){
			var lvProrate=prorates[i];
			var checkDate=true;
			if(i>0){
				for(var j=0;j<i;j++){
					var checkProrate=prorates[j];
					if(lvProrate[0]>= checkProrate[0] && lvProrate[0]<=checkProrate[1]){
						checkDate=false;
						break;
					}
					if(lvProrate[1]>= checkProrate[0] && lvProrate[1]<=checkProrate[1]){
						checkDate=false;
						break;
					}
				}
			}
			if(checkDate){
				var rowVal=formatDate("yyyy-MM-dd",lvProrate[0])+"#"+ formatDate("yyyy-MM-dd",lvProrate[1])+"#"+ lvProrate[2];
				submitPororateValue+=","+rowVal;					
			}else{
				layer.alert('请返利信息配置时间有重叠，请检查时间。', 8);
				return false;
			}
		}
		submitPororateValue=submitPororateValue.substring(1);
		$("#prorateRateValue").val(submitPororateValue);	
		$("#prorateRateChange").val("1");
		layer.alert("检查完成，返利设置时间没有错误！",1);
	}
	return true;
}
bindDatePicker();

function checkSubmit()
{
	try{
		var lvDeveloperProrate=$("#DeveloperProrate").val();
		var iDeveloperProrate=parseInt(lvDeveloperProrate);
		if((""+iDeveloperProrate)!=lvDeveloperProrate){
			layer.alert("开发商分成比例 值有错误，请检查！",8);
			return false;
		}
		if(iDeveloperProrate>=100 || iDeveloperProrate<0){
			layer.alert("开发商分成比例 值有错误，请检查！",8);
			return false;
		}
	}catch(ex){
		alert(ex);
		return false;
	}
	return true;
}
</script>