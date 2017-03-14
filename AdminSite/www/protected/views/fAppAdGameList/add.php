<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写走马灯信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>跳转链接</label> 
				<input type="text" maxlength="50" name="url" class="input-xlarge" autofocus="true" />
				
				<label>图片</label> 
				<input type="text" maxlength="50" name="pic" id="pic" onclick="javascript:selectPics('pic');"  class="input-xlarge" required="true" autofocus="true" />
				
				<label>需要身份</label> 
				<select name="needUserId"  class="input-xlarge">
					<option value="0">不需要</option>
					<option value="1">需要</option>
				</select>
				
				<label>IP白名单</label> 
				<input type="text" maxlength="50" name="ip" class="input-xlarge"  autofocus="true" />
				
				<label>点击图片</label>
				<select name="target"  class="input-xlarge">
					<option value="">打开跳转链接地址</option>
					<option value="game">进入分红游戏介绍</option>
				</select>
				
				<label>游戏AppId</label>
				<select name="gameId"  class="input-xlarge">
					<option value="0"></option>
					<?php 
						foreach ($AppList as $itemRow) {
							echo sprintf("<option value=\"%s\">%s</option>",$itemRow["AppId"],$itemRow["AppName"]);
						}
					?>
				</select>
				
				<input type="hidden" name="AppId" value="" />
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
<?php $this->widget('application.widget.PicSelector'); ?>
<script type="text/javascript">
function selectPics(pvTxtId)
{
	showPicSelector($("#AppId").val(),pvTxtId,$("#"+pvTxtId).val(),false);
}
</script>