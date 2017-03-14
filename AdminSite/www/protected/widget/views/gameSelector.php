<style>
#game_ui_window {
	width: 640px;
	height: 520px;
	border: 2px solid #4A84C4;
	border-top: none;
	overflow: auto;
}
</style>
<div id="game_Selector_Div" style="display:none;">
	<div>
		<table class="table table-striped" style="margin-bottom: 0;">
			<tbody>
				<tr>
					<td style="width:220px;"><input type="text" maxlength="50" name="showGameAppId" id="showGameAppId" value="" class="input-large" autofocus="true"/></td>
					<td style="width:40px;"><input type="button" value="过滤" onclick="javascript:filterGame();"  /></td>
					<td><input type="button" value="保存选择的游戏" onclick="javascript:saveSelectGames();" /></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div  id="game_ui_window" style="z-index:100;">
		<table class="table table-striped" id="gameList">
			<thead>
				<tr class="gameHeadList">
					<th width="80px">游戏AppId</th>
					<th width="120px">游戏</th>
					<th width="80px">选择</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr class="flagAppId %s">
	<td>%s</td>
	<td>%s</td>
	<td>
		<input type="checkbox" value="%s" class="selectGameCheck %s" />
	</td>
</tr>
EndOfRowTag;
			 	foreach ($game as $item){
					$rowHtml=sprintf($rowsHtmlTag,
						$item["AppId"],$item["AppId"],
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
var _GAME_FILTER_APPID="";
var _SET_GAME_TXT_ID="";
var _PREVIOUS_GAME_VALUE="";
var _GAME_LIST=[
<?php
	foreach ($game as $item){
		echo sprintf("\t{ AppId:\"%s\",AppName:\"%s\"},\n",$item["AppId"],$item["AppName"]);
	} 
?>               
		{ AppId:"END",AppName:"END" }		               
	];
var _CAN_GAME_MUTIL_CHOICES=false;

function showGameSelector(AppId,TxtId,CurrentValue,CanMutilChoices)
{
	_CAN_GAME_MUTIL_CHOICES=CanMutilChoices;
	_SET_GAME_TXT_ID=TxtId;
	_GAME_FILTER_APPID=AppId;
	_PREVIOUS_GAME_VALUE=CurrentValue;


	$("#showGameAppId").val(_GAME_FILTER_APPID);
	filterGame();

	
	restoreGameSelectedState();
	
	$.layer({
        type: 1,
        title: false, 
        shade: [0.5, '#000'], 
        area: ['640px', '580px'],
        page: {dom:"#game_Selector_Div"}
    });
}
function filterGame()
{
	var showAppId=$("#showGameAppId").val();
	if(showAppId!=""){
		var checkClassName="flagAppId "+showAppId;
		$('#gameList tr').each(function(){
			var className=$(this).attr('class');
			if(className!="gameHeadList"){
				if(className==checkClassName){
					$(this).show();
				}else{
					$(this).hide();
				}
			}
		});
	}else{
		$('#gameList tr').each(function(){$(this).show();});
	}
}

function restoreGameSelectedState()
{
	var fieldIds=null;
	if(_PREVIOUS_GAME_VALUE!=""){
		fileIds = _PREVIOUS_GAME_VALUE.split(",");
	}

	$(".selectGameCheck").each(function(){
		var checkValue=false;
		if(_PREVIOUS_GAME_VALUE!=""){
			var className=$(this).attr("class");
			for(var i=0;i<fileIds.length;i++){
				var findName="selectGameCheck "+fileIds[i];
				if(className==findName){
					checkValue=true;
					break;
				}
			}
		}
		$(this).attr("checked",checkValue);
	});
}
function saveSelectGames()
{
	var selectedCount=0;
	var gameIds="";
	$(".selectGameCheck").each(function(){
		if($(this).attr("checked"))
		{
			gameIds+=","+$(this).val();
			selectedCount++;
		}		
	});
	
	if(selectedCount>1 && _CAN_GAME_MUTIL_CHOICES==false){
		layer.alert("只能选取一个游戏。",8);
		return;
	}
	if(gameIds!=""){
		$("#"+_SET_GAME_TXT_ID).val(gameIds.substring(1));
		layer.closeAll();
	}
}
</script>
