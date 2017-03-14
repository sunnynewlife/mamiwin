<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写配置信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>游戏名称</label> 
			 	<select name="gameid">
					<?php 
					$rowsHtmlTag=<<<EndOfRowTag
					<option value="%s">%s</option>
EndOfRowTag;
				$os_type=Yii::app()->request->getParam('os_type',1);
				$sql="select gameid,game_name from t_game_config where os_type=".$os_type.";";
				$game_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
				$game_name_list=array();
			 	foreach ($game_list as $gameItem){
			 		$game_name_list[$gameItem['gameid']]=$gameItem['game_name'];
			 		$rowHtml=sprintf($rowsHtmlTag,
			 				$gameItem['gameid'],
			 				$gameItem['game_name']);
			 		echo $rowHtml;
			 	}
					?>
				</select>
				<label>活动标题</label> 
				<input  name="activity_title" rows="5" class="input-xlarge" autofocus="true" value=""/>
				<br/>
				<label>图片</label> 
				<input  name="img" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>url</label> 
				<input  name="url" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>活动详情页链接打开方式</label> 
				<select id="url_type" name="url_type">
					<option value="1">webView方式</option>
					<option value="2">APP原生</option>
					<option value="3">浏览器</option>
					<option value="4">webView方式(盛大账号方式)</option>
					<option value="5">APP原生(盛大账号方式)</option>
					<option value="6">浏览器(盛大账号方式)</option>
				</select>
				<div class="act_android_ext app_entrance app_package" style="display:none">
					<h5></h5>
					<label>APP入口地址</label> 
					<input  name="app_entrance" rows="5" class="input-xlarge"  value=""/>
					<br/>
					<label>包名</label> 
					<input  name="pack_name" rows="5" class="input-xlarge"  value=""/>
					<br/>
					<label>下载地址</label> 
					<input  name="pack_download_url" rows="5" class="input-xlarge"  value=""/>
					<br/>
				</div>
				<div class="act_ios_ext" id="act_ios_ext" style="display:none">
					<h5>iOS APP原生方式打开，附加信息</h5>
					<label>Schema</label> 
					<input  name="schema_info" rows="5" class="input-xlarge" value=""/>
					<br/>
					<label>AppStore URL</label> 
					<input  name="appstore_url" rows="5" class="input-xlarge"  value=""/>
					<br/>
					<br/>
				</div>
				<label>app_id</label> 
				<input  name="app_id" rows="5" class="input-xlarge"  autofocus="true" value=""/>
				<br/>
				<label>序号</label> 
				<select name="index_no">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
				</select>
				<br/>
				<label>状态</label> 
				<select name="state">
					<option value="1">灰度</option>
					<option value="2">上架</option>
					<option value="0">下架</option>
				</select>
				<br/>
				<input type="hidden" id="activity_url_type" name="activity_url_type" value="1" />
				<input type="hidden" name="os_type" value="<?php echo $os_type;?>" />
				<input type="hidden" name="banner_type" value="<?php echo Yii::app()->request->getParam('banner_type',1);?>" />
				<input type="hidden" name="in_version" value="<?php echo Yii::app()->request->getParam('in_version','2.0');?>"> 
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary" onclick="doConvert();">
						<strong>提交</strong>
					</button>
				</div>
			
			</form>
		</div>
	</div>
</div>
<?php $this->widget('application.widget.JsonEditor'); ?>
<script type="text/javascript">
var os_type="<?php echo Yii::app()->request->getParam('os_type',1);?>";
function show_ext(){
	var type=jQuery("#url_type").val();
	jQuery("#activity_url_type").val(type);
	jQuery(".act_ios_ext").hide();
	jQuery(".act_android_ext").hide();
	if(os_type==1){
		switch(type){
			case '2':
				jQuery(".act_android_ext.app_package").show();
				jQuery(".act_android_ext h5").text('Android APP原生，附加信息');
				break;
			case '5':
				jQuery(".act_android_ext.app_entrance").show();
				jQuery(".act_android_ext h5").text('Android APP原生(盛大账号方式)，附加信息');
				break;
			default:
				break;
		}
	}else{
		switch(type){
			case '2':
				jQuery(".act_ios_ext").show();
				break;
			default:
				break;
		}
	}
}
show_ext();
jQuery("#url_type").bind("change",function(){
	show_ext();
});
function jsonFormatChange()
{
	if($("#jsonFormater").attr("checked")=="checked"){
		$("#btnJsonEdit").show();
	}else{
		$("#mask").hide();
		$("#btnJsonEdit").hide();
	}
}
function doJsonEdit()
{
	showJsonEditor($("#config_value").val());   
}
</script>
