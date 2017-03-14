<style>
#app_ui_window {
	width: 640px;
	height: 520px;
	border: 2px solid #4A84C4;
	border-top: none;
	overflow: auto;
}
</style>
<div id="app_Selector_Div" style="display:none;">
	<div>
		<table class="table table-striped" style="margin-bottom: 0;">
			<tbody>
				<tr>
					<td style="width:220px;"><input type="text" maxlength="50" name="AppSelectorShowAppId" id="AppSelectorShowAppId" value="" class="input-large" autofocus="true"/></td>
					<td style="width:40px;"><input type="button" value="过滤" onclick="javascript:filterApp();"  /></td>
					<td><input type="button" value="保存选择的游戏" onclick="javascript:saveSelectApp();" /></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div  id="app_ui_window" style="z-index:100;">
		<table class="table table-striped" id="appSelectTableList">
			<thead>
				<tr class="appHeadList">
					<th>AppId</th>
					<th>游戏名称</th>
					<th width="80px">选择</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr class="flagSelectAppId %s">
	<td width="40px">%s</td>
	<td width="120px">%s</td>
	<td>
		<input type="checkbox" value="%s" class="selectAppCheck %s" />
	</td>
</tr>
EndOfRowTag;
			 	foreach ($AppInfo as $item){
					$rowHtml=sprintf($rowsHtmlTag,
						$item["AppId"],
						$item["AppId"],
						$item["AppName"],
						$item["AppId"],$item["AppId"]);
					echo $rowHtml; 
				}
			?>
			</tbody>
		</table>
				
	</div>
</div>

<script type="text/javascript">
var _SAVE_CALL_BACK_FUNC=null;			
var _APP_LIST=[
<?php
	foreach ($AppInfo as $item){
		echo sprintf("\t{ AppId:\"%s\",AppName:\"%s\", Prorate:%s },\n",$item["AppId"],$item["AppName"],$item["PromoterProrate"]);
	} 
?>               
		{ AppId:"END",AppName:"END",Prorate:0 }		 
	];
	
function showAppSelector(saveCallbackFunc)
{
	_SAVE_CALL_BACK_FUNC=saveCallbackFunc;
	
	$.layer({
        type: 1,
        title: false, 
        shade: [0.5, '#000'], 
        area: ['640px', '580px'],
        page: {dom:"#app_Selector_Div"}
    });
}
function getMatchedAppIds(pvStr)
{
	var ids=new Array()
	for( var i=0;i<_APP_LIST.length;i++){
		if(_APP_LIST[i].AppId.indexOf(pvStr)>=0 || _APP_LIST[i].AppName.indexOf(pvStr)>=0){
			ids.push("flagSelectAppId "+_APP_LIST[i].AppId);
		}
	}
	return ids;
}
function filterApp()
{
	var showAppId=$("#AppSelectorShowAppId").val();
	if(showAppId!=""){
		var shownIds=getMatchedAppIds(showAppId);
		if(shownIds.length==0){
			$('#appSelectTableList tr').each(function(){$(this).hide();});
		}else{
			$('#appSelectTableList tr').each(function(){
				var className=$(this).attr('class');
				if(className!="appHeadList"){
					var bShownThisTr=false;
					for(var i=0;i<shownIds.length;i++){
						if(className==shownIds[i]){
							bShownThisTr=true;
							break;
						}
					}
					if(bShownThisTr){
						$(this).show();
					}else{
						$(this).hide();
					}
				}
			});
		
		}
	}else{
		$('#appSelectTableList tr').each(function(){$(this).show();});
	}
}

function saveSelectApp()
{
	var selectedCount=0;
	var appIds="";
	$(".selectAppCheck").each(function(){
		if($(this).attr("checked"))
		{
			appIds+=","+$(this).val();
			selectedCount++;
		}		
	});
	
	if(selectedCount>1){
		layer.alert("只能选取一个游戏。",8);
		return;
	}
	var selectAppId=appIds.substring(1);
	for( var i=0;i<_APP_LIST.length;i++){
		if(_APP_LIST[i].AppId==selectAppId){
			_SAVE_CALL_BACK_FUNC(_APP_LIST[i].AppId,_APP_LIST[i].AppName,_APP_LIST[i].Prorate);
		}
	}
	layer.closeAll();
}
</script>
