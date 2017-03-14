<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写游戏配置信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>媒体名称</label> 
				<input  name="media_name" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_media["media_name"];?>"/>
				<br/>
				<label>游戏名称</label> 
			 	<select name="gameid">
					<?php 
					$rowsHtmlTag=<<<EndOfRowTag
					<option value="%s" %s >%s</option>
EndOfRowTag;
				$os_type=$t_game_media['os_type'];
				$sql="select gameid,game_name from t_game_config where os_type=".$os_type.";";
				$game_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
				$game_name_list=array();
			 	foreach ($game_list as $gameItem){
			 		$rowHtml=sprintf($rowsHtmlTag,
			 				$gameItem['gameid'],
			 				$t_game_media['gameid']==$gameItem['gameid']?'selected':'',
			 				$gameItem['game_name']);
			 		echo $rowHtml;
			 	}
					?>
				</select>
				<br/>
				<label>平台类型</label> 
				<input  type="hidden" name="os_type" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_media["os_type"];?>"/>
				<label><?php echo $t_game_media["os_type"]==1?"Android":"iOS";?></label>
				<label>媒体URL</label> 
				<input  name="url" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_media["url"];?>"/>
				<br/>
				<label>缩略图URL</label> 
				<input  name="thumbnail_images" rows="5" class="input-xlarge" autofocus="true" value="<?php echo $t_game_media["thumbnail_images"];?>"/>
				<br/>
				<label>媒体序号</label> 
				<input  name="media_no" rows="5" class="input-xlarge" autofocus="true" value="<?php echo $t_game_media["media_no"];?>"/>
				<br/>
				<label>类型</label> 
				<select name="type">
					<option value="1" <?php echo $t_game_media["type"]==1?"selected":"";?>>图片</option>
					<option value="2" <?php echo $t_game_media["type"]==2?"selected":"";?>>视频</option>
					<option value="3" <?php echo $t_game_media["type"]==3?"selected":"";?>>其他</option>
				</select>
				<br/>
				<label>图片是否支持横屏</label> 
				<select name="support_landscape">
					<option value="0" <?php echo $t_game_media["support_landscape"]==0?"selected":"";?>>不支持</option>
					<option value="1" <?php echo $t_game_media["support_landscape"]==1?"selected":"";?> >支持</option>
				</select>
				<br/>
				<label>状态</label> 
				<select name="state">
					<option value="1" <?php echo $t_game_media["state"]==1?"selected":"";?> >灰度</option>
					<option value="2" <?php echo $t_game_media["state"]==2?"selected":"";?>>上架</option>
					<option value="0" <?php echo $t_game_media["state"]==0?"selected":"";?>>下架</option>
				</select>
				<br/>
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
