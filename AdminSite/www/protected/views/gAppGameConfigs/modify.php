<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写游戏配置信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<h2>基本信息</h2>
				<label>游戏ID</label> 
				<input  name="gameid" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_config["gameid"];?>"/>
				<br/>
				<br/>
				<label>平台类型</label> 
				<input  type="hidden" name="os_type" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_config["os_type"];?>"/>
				<label><?php echo $t_game_config["os_type"]==1?"Android":"iOS";?></label>
				<!-- 
				<select name="os_type" readonly onfocus="this.blur()">
					<option value="1" <?php echo $t_game_config["os_type"]==1?"selected":"";?>>Android</option>
					<option value="2" <?php echo $t_game_config["os_type"]==2?"selected":"";?>>iOS</option>
				</select>
				 -->
				<br/>
				<label>游戏名称</label> 
				<input  name="game_name" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_config["game_name"];?>"/>
				<br/>
				<label>游戏LOGO</label> 
				<input  name="game_logo" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_config["game_logo"];?>"/>
				<br/>
				<label>版本号</label> 
				<input  name="version" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_config["version"];?>"/>
				<br/>
				<label>游戏介绍</label> 
				<textarea name="intro" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_config["intro"];?>"><?php echo $t_game_config["intro"];?></textarea>
				<br/>
				<label>游戏内容</label> 
				<textarea name="content" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_config["content"];?>"><?php echo $t_game_config["content"];?></textarea>
				<br/>
				<label>开发商</label> 
				<input  name="developer" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_config["developer"];?>"/>
				<br/>
				<label>关注数</label> 
				<input  name="focus_count" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_config["focus_count"];?>"/>
				<br/>
				<label>游戏推荐列表图片</label> 
				<input  name="game_img" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_config["game_img"];?>"/>
				<br/>
				<br/>
				<label>状态</label> 
				<select name="state">
					<option value="1" <?php echo $t_game_config["state"]==1?"selected":"";?> >灰度</option>
					<option value="2" <?php echo $t_game_config["state"]==2?"selected":"";?>>上架</option>
					<option value="0" <?php echo $t_game_config["state"]==0?"selected":"";?>>下架</option>
				</select>
				<h2>下载信息</h2>
				<label>包名</label> 
				<input  name="download_name" rows="5" class="input-xlarge" required="true" value="<?php echo $t_game_config["download_name"];?>"/>
				<br/>
				<label>下载地址</label> 
				<input  name="download_url" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_config["download_url"];?>"/>
				<br/>
				<label>分享地址</label> 
				<input  name="share_url" rows="5" class="input-xlarge"  required="true" autofocus="true" value="<?php echo $t_game_config["share_url"];?>"/>
				<br/>
				<label>游戏大小</label> 
				<input  name="size" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_config["size"];?>"/>
				<label>下载次数</label> 
				<input  name="download_count" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_config["download_count"];?>"/>
				<br/>
				<label>app_id</label> 
				<input  name="app_id" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_config["app_id"];?>"/>
				<br/>
				<input type="hidden" name="recommend_no" value="<?php echo $t_game_config["recommend_no"];?>" />
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
