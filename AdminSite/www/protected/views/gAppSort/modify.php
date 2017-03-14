<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写游戏配置信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<img disabled="disabled" src="/static/img/20141127155657.png" width="100" >
			<form id="tab" method="post" action="">
				<input  type="hidden"  name="gameid" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_config["gameid"];?>"/>
				<img disabled="disabled" src="<?php echo $t_game_config["game_img"];?>" width="100" >
				<label>游戏名称</label> 
								<input  name="game_name" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_config["game_name"];?>"/>
				<label>序号</label> 
				<select name="recommend_no">
					<option value="1" <?php echo (int)$t_game_config["recommend_no"]==1?"selected":"";?>>A</option>
					<option value="2" <?php echo (int)$t_game_config["recommend_no"]==2?"selected":"";?>>B</option>
					<option value="3" <?php echo (int)$t_game_config["recommend_no"]==3?"selected":"";?>>C</option>
					<option value="4" <?php echo (int)$t_game_config["recommend_no"]==4?"selected":"";?>>D</option>
					<option value="5" <?php echo (int)$t_game_config["recommend_no"]==5?"selected":"";?>>E</option>
					<option value="6" <?php echo (int)$t_game_config["recommend_no"]==6?"selected":"";?>>F</option>
				</select>
				<label>状态</label> 
				<select name="state">
					<option value="1" <?php echo $t_game_config["state"]==1?"selected":"";?> >灰度</option>
					<option value="2" <?php echo $t_game_config["state"]==2?"selected":"";?>>上架</option>
					<option value="0" <?php echo $t_game_config["state"]==0?"selected":"";?>>下架</option>
				</select>
				<br/>
				<input type="hidden" name="os_type" value="<?php echo $t_game_config['os_type'];?>" />
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
