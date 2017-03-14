<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写配置信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<h2>基本信息</h2>
				<label>游戏ID</label> 
				<input  name="gameid" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<br/>
				<label>平台类型</label> 
				<input  type="hidden" name="os_type" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo Yii::app()->request->getParam('os_type','');?>"/>
				<label><?php echo Yii::app()->request->getParam('os_type','')==1?"Android":"iOS";?></label>
				<br/>
				<label>游戏名称</label> 
				<input  name="game_name" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
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
				<label>开发商</label> 
				<input  name="developer" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>关注数</label> 
				<input  name="focus_count" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>游戏推荐列表图片</label> 
				<input  name="game_img" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<br/>
				<label>状态</label> 
				<select name="state">
					<option value="1">灰度</option>
					<option value="2">上架</option>
					<option value="0">下架</option>
				</select>
				<h2>下载信息</h2>
				<label>包名</label> 
				<input  name="download_name" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>下载地址</label> 
				<input  name="android_download_url" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>游戏大小</label> 
				<input  name="size" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<label>下载次数</label> 
				<input  name="download_count" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
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
