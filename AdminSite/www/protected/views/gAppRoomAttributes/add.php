<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写聊天室属性</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>聊天室名字</label> 
				<input name="chat_room_name" rows="5" class="input-xlarge" required="true" autofocus="true" />
				
				<label>所属游戏ID</label> 
				<input name="game_id" rows="5" class="input-xlarge" required="true" autofocus="true" />
				
				<label>大图片</label>
				<input name="cover_url" style="width:600px" class="input-xlarge" required="true" autofocus="true" />
				 
				<label>小图片</label>
				<input name="cover_url_small" style="width:600px" class="input-xlarge" required="true" autofocus="true" /> 
				
				<label>广告Url</label> 
				<textarea name="advertisement_url" style="width:600px" rows="3" class="input-xlarge" required="true" autofocus="true"></textarea>
				
				<label>广告内容</label> 
				<input name="advertisement_text" style="width:600px" class="input-xlarge" required="true" autofocus="true" />
				
				<label>排序</label> 
				<input name="sort_id" rows="5" class="input-xlarge" required="true" autofocus="true" />
				
				<label>状态</label> 
				<select name="status" class="input-xlarge">
					<option value="1" >有效</option>
					<option value="0">无效</option>
				</select>		
				<label>推荐</label> 
				<select name="is_recommend" class="input-xlarge">
					<option value="1" >推荐</option>
					<option value="0" >不推荐</option>
				</select>	
				<label>开放</label> 
				<select name="is_open" class="input-xlarge">
					<option value="1" >开放</option>
					<option value="0" >关闭</option>
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