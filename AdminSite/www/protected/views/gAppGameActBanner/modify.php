<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写游戏配置信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>游戏名称</label> 
			 	<select name="gameid">
					<?php 
					$rowsHtmlTag=<<<EndOfRowTag
					<option value="%s" %s >%s</option>
EndOfRowTag;
				$os_type=$t_activity_banner['os_type'];
				$sql="select gameid,game_name from t_game_config where os_type=".$os_type.";";
				$game_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
				$game_name_list=array();
			 	foreach ($game_list as $gameItem){
			 		$rowHtml=sprintf($rowsHtmlTag,
			 				$gameItem['gameid'],
			 				$t_activity_banner['gameid']==$gameItem['gameid']?'selected':'',
			 				$gameItem['game_name']);
			 		echo $rowHtml;
			 	}
					?>
				</select>
				<br/>
				<label>图片URL</label> 
				<input  name="img" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_activity_banner["img"];?>"/>
				<br/>
				<label>游戏官网</label> 
				<input  name="url" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_activity_banner["url"];?>"/>
				<br/>
				<label>活动详情页链接打开方式</label> 
				<select id="url_type" name="url_type">
					<option value="1" <?php echo $t_activity_banner["url_type"]==1?"selected":"";?>>webView方式</option>
					<option value="2" <?php echo $t_activity_banner["url_type"]==2?"selected":"";?>>APP原生</option>
					<option value="3" <?php echo $t_activity_banner["url_type"]==3?"selected":"";?>>浏览器</option>
					<option value="4" <?php echo $t_activity_banner["url_type"]==4?"selected":"";?>>webView方式(盛大账号方式)</option>
					<option value="5" <?php echo $t_activity_banner["url_type"]==5?"selected":"";?>>APP原生(盛大账号方式)</option>
					<option value="6" <?php echo $t_activity_banner["url_type"]==6?"selected":"";?>>浏览器(盛大账号方式)</option>
				</select>
				<br/>
				<div class="act_android_ext app_entrance app_package" style="display:none">
					<h5><?php echo $t_activity_banner["url_type"]==5?"Android APP原生(盛大账号方式)，附加信息":"Android APP原生，附加信息";?></h5>
					<label>APP入口地址</label> 
					<input  name="app_entrance" rows="5" class="input-xlarge"  value="<?php echo $t_activity_banner['app_entrance'];?>"/>
					<br/>
					<label>包名</label> 
					<input  name="pack_name" rows="5" class="input-xlarge"  value="<?php echo $t_activity_banner["pack_name"];?>"/>
					<br/>
					<label>下载地址</label> 
					<input  name="pack_download_url" rows="5" class="input-xlarge"  value="<?php echo $t_activity_banner["pack_download_url"];?>"/>
					<br/>
				</div>
				<div class="act_ios_ext" id="act_ios_ext" style="display:none">
					<h5>iOS APP原生方式打开，附加信息</h5>
					<label>Schema</label> 
					<input  name="schema_info" rows="5" class="input-xlarge" value="<?php echo $t_activity_banner["schema_info"];?>"/>
					<br/>
					<label>AppStore URL</label> 
					<input  name="appstore_url" rows="5" class="input-xlarge"  value="<?php echo $t_activity_banner["appstore_url"];?>"/>
					<br/>
					<br/>
				</div>
				<label>app_id</label> 
				<input  name="app_id" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_activity_banner["app_id"];?>"/>
				<br/>
				<label>序号</label> 
				<select name="no">
					<option value="1" <?php echo $t_activity_banner["no"]==1?"selected":"";?>>1</option>
					<option value="2" <?php echo $t_activity_banner["no"]==2?"selected":"";?>>2</option>
					<option value="3" <?php echo $t_activity_banner["no"]==3?"selected":"";?>>3</option>
					<option value="4" <?php echo $t_activity_banner["no"]==4?"selected":"";?>>4</option>
					<option value="5" <?php echo $t_activity_banner["no"]==5?"selected":"";?>>5</option>
					<option value="6" <?php echo $t_activity_banner["no"]==6?"selected":"";?>>6</option>
				</select>
				<br/>
				<label>状态</label> 
				<select name="state">
					<option value="1" <?php echo $t_activity_banner["state"]==1?"selected":"";?> >灰度</option>
					<option value="2" <?php echo $t_activity_banner["state"]==2?"selected":"";?>>上架</option>
					<option value="0" <?php echo $t_activity_banner["state"]==0?"selected":"";?>>下架</option>
				</select>
				<br/>
				<input type="hidden" name="os_type" value="<?php echo $t_activity_banner['os_type']?>" />
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary">
						<strong>提交</strong>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php $this->widget('application.widget.JsonEditor'); ?>
<script type="text/javascript">
var os_type="<?php echo $t_activity_banner['os_type'];?>";
var url_type="<?php echo $t_activity_banner["url_type"];?>";
function show_ext(){
	var type=jQuery("#url_type").val();
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

</script>
