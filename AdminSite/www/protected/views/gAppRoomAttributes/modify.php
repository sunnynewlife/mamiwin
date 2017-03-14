<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写聊天室信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>聊天室ID</label> 
				<input name="chat_room_id" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_chat_room_attributes["chat_room_id"];?>" readonly />
				
				<label>聊天室名字</label> 
				<input name="chat_room_name" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_chat_room_attributes["chat_room_name"];?>" />
				
				<label>所属游戏ID</label> 
				<input name="game_id" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_chat_room_attributes["game_id"];?>" />
				
				<label>大图片</label> 
				<input name="cover_url" style="width:600px" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_chat_room_attributes["cover_url"];?>" />
				
				<label>小图片</label> 
				<input name="cover_url_small" style="width:600px" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_chat_room_attributes["cover_url_small"];?>" />
				
				<label>广告Url</label> 
				<textarea name="advertisement_url" style="width:600px" rows="3" class="input-xlarge" required="true" autofocus="true"><?php echo $t_chat_room_attributes["advertisement_url"];?></textarea>
				
				<label>广告内容</label>
				<input name="advertisement_text" style="width:600px" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_chat_room_attributes["advertisement_text"];?>" /> 
				
				<label>排序</label> 
				<input name="sort_id" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_chat_room_attributes["sort_id"];?>" />
				<label>状态</label> 
				<select name="status" class="input-xlarge">
					<option value="1" <?php echo $t_chat_room_attributes["status"]==1?" selected":" ";?> >有效</option>
					<option value="0" <?php echo $t_chat_room_attributes["status"]==1?" ":" selected";?> >无效</option>
				</select>		
				<label>推荐</label> 
				<select name="is_recommend" class="input-xlarge">
					<option value="1" <?php echo $t_chat_room_attributes["is_recommend"]==1?" selected":" ";?> >推荐</option>
					<option value="0" <?php echo $t_chat_room_attributes["is_recommend"]==1?" ":" selected";?> >不推荐</option>
				</select>	
				<label>开放</label> 
				<select name="is_open" class="input-xlarge">
					<option value="1" <?php echo $t_chat_room_attributes["is_open"]==1?" selected":" ";?> >开放</option>
					<option value="0" <?php echo $t_chat_room_attributes["is_open"]==1?" ":" selected";?> >关闭</option>
				</select>
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
