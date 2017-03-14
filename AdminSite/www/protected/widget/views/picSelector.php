<style>
#pic_ui_window {
	width: 840px;
	height: 520px;
	border: 2px solid #4A84C4;
	border-top: none;
	overflow: auto;
}
</style>
<div id="pic_Selector_Div" style="display:none;">
	<div>
		<table class="table table-striped" style="margin-bottom: 0;">
			<tbody>
				<tr>
					<td style="width:220px;"><input type="text" maxlength="50" name="showAppId" id="showAppId" value="" class="input-large" autofocus="true"/></td>
					<td style="width:40px;"><input type="button" value="过滤" onclick="javascript:filterPic();"  /></td>
					<td><input type="button" value="保存选择的图片" onclick="javascript:saveSelectPics();" /></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div  id="pic_ui_window" style="z-index:100;">
		<table class="table table-striped" id="picList">
			<thead>
				<tr class="picHeadList">
					<th>IDX</th>
					<th>游戏</th>
					<th>图片</th>
					<th width="80px">选择</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr class="flagAppId %s">
	<td width="40px">%s</td>
	<td width="120px">%s</td>
	<td><img style="width:180px;" src=%s></td>
	<td>
		<input type="checkbox" value="%s" class="selectCheck %s" />
	</td>
</tr>
EndOfRowTag;
			 	foreach ($file as $item){
					$rowHtml=sprintf($rowsHtmlTag,
						$item["Scene"],
						$item["FileId"],
						$item["SceneName"],
						$item["DownloadUrl"],$item["FileId"],$item["FileId"]);
					echo $rowHtml; 
				}
			?>
			</tbody>
		</table>
				
	</div>
</div>

<script type="text/javascript">
var _PIC_FILTER_APPID="";
var _SET_TXT_ID="";
var _PREVIOUS_VALUE="";
var _PIC_LIST=[
<?php
	foreach ($file as $item){
		echo sprintf("\t{ FileId:\"%s\",AppName:\"%s\", AppId:\"%s\",PicUrl:\"%s\" },\n",$item["FileId"],$item["SceneName"],$item["Scene"],$item["DownloadUrl"]);
	} 
?>               
		{ FileId:"END",AppId:"END",PicUrl:"END" }		               
	];
var _CAN_MUTIL_CHOICES=false;

function showPicSelector(AppId,TxtId,CurrentValue,CanMutilChoices)
{
	_CAN_MUTIL_CHOICES=CanMutilChoices;
	_SET_TXT_ID=TxtId;
	_PIC_FILTER_APPID=AppId;
	_PREVIOUS_VALUE=CurrentValue;


	$("#showAppId").val(_PIC_FILTER_APPID);
	filterPic();

	
	restoreSelectedState();
	
	$.layer({
        type: 1,
        title: false, 
        shade: [0.5, '#000'], 
        area: ['840px', '580px'],
        page: {dom:"#pic_Selector_Div"}
    });
}
function filterPic()
{
	var showAppId=$("#showAppId").val();
	if(showAppId!=""){
		var checkClassName="flagAppId "+showAppId;
		$('#picList tr').each(function(){
			var className=$(this).attr('class');
			if(className!="picHeadList"){
				if(className==checkClassName){
					$(this).show();
				}else{
					$(this).hide();
				}
			}
		});
	}else{
		$('#picList tr').each(function(){$(this).show();});
	}
}

function restoreSelectedState()
{
	var fieldIds=null;
	if(_PREVIOUS_VALUE!=""){
		fileIds = _PREVIOUS_VALUE.split(",");
	}

	$(".selectCheck").each(function(){
		var checkValue=false;
		if(_PREVIOUS_VALUE!=""){
			var className=$(this).attr("class");
			for(var i=0;i<fileIds.length;i++){
				var findName="selectCheck "+fileIds[i];
				if(className==findName){
					checkValue=true;
					break;
				}
			}
		}
		$(this).attr("checked",checkValue);
	});
}
function saveSelectPics()
{
	var selectedCount=0;
	var picIds="";
	$(".selectCheck").each(function(){
		if($(this).attr("checked"))
		{
			picIds+=","+$(this).val();
			selectedCount++;
		}		
	});
	
	if(selectedCount>1 && _CAN_MUTIL_CHOICES==false){
		layer.alert("只能选取一个图片。",8);
		return;
	}
	if(picIds!=""){
		$("#"+_SET_TXT_ID).val(picIds.substring(1));
		layer.closeAll();
	}
}
</script>
