<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写游戏配置信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>平台类型：<?php echo Yii::app()->request->getParam('os',1)==1?"Android":"iOS";?><span style="margin-left:10px;"><?php echo Yii::app()->request->getParam('in_version','2.0')=='2.0'?'  (极管家 6.0.0版本接口)':'';?></span></label>
				<h2>基本信息</h2>
				<label>gameAssid</label>
				<input  placeholder="助手id" name="gameid" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_tool["gameid"];?>"/>
				<br/>
				<label>游戏助手名称</label> 
				<input  name="tool_name" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_tool["tool_name"];?>"/>
				<br/>
				<br/>
				<!-- 
				<label>推荐标识<span style="font-size: 10px;margin-left:5px">（该选项影响游戏是否在热门推荐等模块展示）</span></label>  
				<select name="flag">
					<option value="null">null</option>
					<option value="hot" <?php echo $t_game_tool["flag"]=="hot"?"selected":"";?> style="color:red">热门推荐</option>
					<option value="new" <?php echo $t_game_tool["flag"]=="new"?"selected":"";?> >新上架推荐 </option>
					<option value="first" <?php echo $t_game_tool["flag"]=="first"?"selected":"";?>>首发推荐 </option>
					<option value="other" <?php echo $t_game_tool["flag"]=="other"?"selected":"";?> >其他推荐 (在游戏推荐页面显示，无推荐标签)</option>
				</select>
				 -->
				<label>游戏助手LOGO</label> 
				<input  name="game_logo" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_tool["game_logo"];?>"/>
				<br/>
				<label>游戏助手版本号</label> 
				<input  name="version" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_tool["version"];?>"/>
				<br/>
				<label>游戏助手介绍</label> 
				<textarea name="intro" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_tool["intro"];?>"><?php echo $t_game_tool["intro"];?></textarea>
				<br/>
				<label>游戏助手内容</label> 
				<textarea name="content" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_tool["content"];?>"><?php echo $t_game_tool["content"];?></textarea>
				<br/>
				<label>游戏助手标签</label> 
				<textarea placeholder="官方.用半角逗号　,　分割" name="label" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_tool["label"];?>"><?php echo $t_game_tool["label"];?></textarea>
				<br/>
				<label>游戏助手开发商</label> 
				<input  name="developer" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_tool["developer"];?>"/>
				<br/>
				<label>游戏助手关注数</label> 
				<input  name="focus_count" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_tool["focus_count"];?>"/>
				<br/>
				<!--
				<label>游戏助手推荐列表图片<span style="font-size: 10px;margin-left:5px">（热门推荐模块图片）</span></label> 
				<input  name="game_img" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_tool["game_img"];?>"/>
				  -->
				<br/>
				<label>状态</label> 
				<select name="state">
					<option value="1" <?php echo $t_game_tool["state"]==1?"selected":"";?>>灰度</option>
					<option value="2" <?php echo $t_game_tool["state"]==2?"selected":"";?>>上架</option>
					<option value="0" <?php echo $t_game_tool["state"]==0?"selected":"";?>>下架</option>
				</select>
				<label>账号类型</label> 
				<select name="account_type">
					<option value="0" >不需要</option>
					<option value="1" <?php echo $t_game_tool["account_type"]==1?"selected":"";?>>盛大通行证账号</option>
					<option value="2" <?php echo $t_game_tool["account_type"]==2?"selected":"";?>>手机账号</option>
				</select>
				<label>入口地址</label> 
				<input  placeholder="助手开发商提供" name="entry_url" rows="5"  class="input-xlarge" autofocus="true" value="<?php echo $t_game_tool["entry_url"];?>"/>
				<br/>
				<label>游戏助手大小</label> 
				<input  name="size" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_tool["size"];?>"/>
				<label>下载次数</label> 
				<input  placeholder="可设置初始值，实际下载数依次累加" name="download_count" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_tool["download_count"];?>"/>
				<br/>
				<label>下载包名<span style="font-size: 10px;margin-left:5px"></span></label> 
				<input  name="download_package_name" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_tool["download_package_name"];?>"/>
				<br/>
				<label>下载链接地址<span style="font-size: 10px;margin-left:5px"></span></label> 
				<input  name="download_url" rows="5" class="input-xlarge"   value="<?php echo $t_game_tool["download_url"];?>"/>
				<br/>
				<label>游戏助手列表排序<span></span></label> 
				<input  name="index_no" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_tool["index_no"];?>"/>
				<br/>
				<label>app_id</label> 
				<input  placeholder="G家APP客户端做特殊用途,例如ticket校验等" name="app_id" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_tool["app_id"];?>"/>
				<br/>
				<input type="hidden" name="in_version" value="2.0"> 
				<input type="hidden" name="os_type" value="<?php echo $t_game_tool["os_type"];?>"> 
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
