<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写配置信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>媒体名称</label> 
				<input  name="media_name" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
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
				<br/>
				<label>平台类型</label> 
				<input  type="hidden" name="os_type" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo Yii::app()->request->getParam('os_type','');?>"/>
				<label><?php echo Yii::app()->request->getParam('os_type','')==1?"Android":"iOS";?></label>
				<label>媒体URL</label> 
				<input  name="url" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>缩略图URL</label> 
				<input  name="thumbnail_images" rows="5" class="input-xlarge"  autofocus="true" value=""/>
				<br/>
				<label>媒体序号</label> 
				<input  name="media_no" rows="5" class="input-xlarge"  autofocus="true" value=""/>
				<br/>
				<label>类型</label> 
				<select name="type">
					<option value="1">图片</option>
					<option value="2">视频</option>
					<option value="3">其他</option>
				</select>
				<br/>
				<label>图片是否支持横屏</label> 
				<select name="support_landscape">
					<option value="0">不支持</option>
					<option value="1">支持</option>
				</select>
				<br/>
				<label>状态</label> 
				<select name="state">
					<option value="1">灰度</option>
					<option value="2">上架</option>
					<option value="0">下架</option>
				</select>
				<input type="hidden" name="update_time" value="<?php echo date("Y-m-d H:i:s");?>" />
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
