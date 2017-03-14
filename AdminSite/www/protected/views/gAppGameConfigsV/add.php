<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写配置信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>平台类型：<?php echo Yii::app()->request->getParam('os',1)==1?"Android":"iOS";?><span style="margin-left:10px;"><?php echo Yii::app()->request->getParam('in_version','2.0')=='2.0'?'  (极管家 6.0.0版本接口)':'';?></span></label>
				<h2>基本信息</h2>
				<label>gameid<span style="font-size: 10px;margin-left:5px">(游戏ID)</span></label>
				<input  name="gameid" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>游戏名称</label> 
				<input  name="game_name" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>游戏类型</label> 
				<select name="game_type">
				<option value="1" <?php echo Yii::app()->request->getParam('game_type',1)==1?"selected":"";?>>手游</option>
				<option value="2" <?php echo Yii::app()->request->getParam('game_type',1)==2?"selected":"";?>>PC游戏</option>
				</select>
				<br/>
				<label>游戏推荐标识<span style="font-size: 10px;margin-left:5px">（该选项影响游戏是否在热门推荐等模块展示）</span></label>  
				<select name="flag">
					<option value="null">null</option>
					<option value="hot" style="color:red">热门</option>
					<option value="new">新上架 </option>
					<option value="first">首发 </option>
					<option value="recom">推荐 </option>
					<option value="other">其他推荐 (在游戏推荐页面显示，无推荐标签)</option>
				</select>
				<br/>
				<label>专题<span style="font-size: 10px;margin-left:5px">（该选项影响游戏是否在游戏专题等模块展示）</span></label> 
			<select name="topic_id">
					<option value=""  >无</option>
					<?php 
					$rowsHtmlTag=<<<EndOfRowTag
					<option value="%s"  >%s</option>
EndOfRowTag;
				$sql="select * from t_game_topic where os_type=? and in_version=?;";
				$game_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(Yii::app()->request->getParam('os_type',1),Yii::app()->request->getParam('in_version','2.0')),PDO::FETCH_ASSOC);
				$game_name_list=array();
			 	foreach ($game_list as $gameItem){
			 		$rowHtml=sprintf($rowsHtmlTag,
			 				$gameItem['id'],
			 				$gameItem['topic_name']);
			 		echo $rowHtml;
			 	}
					?>
				</select>
				<label>专题序号<span style="font-size: 10px;margin-left:5px">（在专题列表中排序）</span></label>  
				<input  name="topic_no" rows="5" class="input-xlarge"  autofocus="true" value=""/>
				<br/>
				<label>专题宣传照<span style="font-size: 10px;margin-left:5px">（在专题分页中做游戏宣传照使用）</span></label>  
				<input  name="topic_pic" rows="5" class="input-xlarge"  autofocus="true" value=""/>
				<br/>
				<label>游戏背景图片<span style="font-size: 10px;margin-left:5px">（在专题分页中做游戏宣传照使用）</span></label>  
				<input  name="game_bg_img" rows="5" class="input-xlarge"  autofocus="true" value=""/>
				<br/>
				<label>游戏LOGO</label> 
				<input  name="game_logo" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>版本号</label> 
				<input  name="version" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>游戏介绍</label> 
				<textarea name="intro" rows="5" class="input-xlarge" required="true" autofocus="true" value=""></textarea>
				<br/>
				<label>游戏内容</label> 
				<textarea name="content" rows="5" class="input-xlarge" required="true" autofocus="true" value=""></textarea>
				<br/>
				<label>游戏标签(用半角逗号　,　分割)</label> 
				<textarea name="label" rows="5" class="input-xlarge"  autofocus="true" value=""></textarea>
				<br/>
				<label>开发商</label> 
				<input  name="developer" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>关注数</label> 
				<input  name="focus_count" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<!--
				<label>游戏推荐列表图片<span style="font-size: 10px;margin-left:5px">（热门推荐模块图片）</span></label> 
				<input  name="game_img" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				  -->
				<br/>
				<label>状态</label> 
				<select name="state">
					<option value="1">灰度</option>
					<option value="2">上架</option>
					<option value="0">下架</option>
				</select>
				<h2>下载信息</h2>
				<label>包名</label> 
				<input  name="download_name" rows="5" class="input-xlarge" autofocus="true" value=""/>
				<br/>
				<label>下载地址</label> 
				<input  name="download_url" rows="5" class="input-xlarge" autofocus="true" value=""/>
				<br/>
				<label>分享地址</label> 
				<input  name="share_url" rows="5" required="true" class="input-xlarge" autofocus="true" value=""/>
				<br/>
				<label>游戏大小</label> 
				<input  name="size" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<label>下载次数<span style="font-size: 10px;margin-left:5px">(可设置初始值，实际下载数依次累加)</span></label> 
				<input  name="download_count" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>app_id<span style="font-size: 10px;margin-left:5px">(G家APP客户端做特殊用途,例如ticket校验等)</span></label> 
				<input  name="app_id" rows="5" class="input-xlarge"  autofocus="true" value=""/>
				<br/>
				<label>活动名称<span style="font-size: 10px;margin-left:5px">(游戏下方活动名称)</span></label> 
				<input  name="hot_act_name" rows="5" class="input-xlarge"  autofocus="true" value=""/>
				<br/>
				<label>活动链接<span style="font-size: 10px;margin-left:5px">(点击游戏下方活动，链接地址)</span></label> 
				<input  name="hot_act_url" rows="5" class="input-xlarge"  autofocus="true" value=""/>
				<br/>
				<label>专区链接<span style="font-size: 10px;margin-left:5px"></span></label> 
				<input  name="area_url" rows="5" class="input-xlarge"  autofocus="true" value=""/>
				<br/>
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
