<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写游戏分类信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>类别名称</label> 
				<select name="CategoryId">
				<?php
					foreach ($Category as $key => $name){
						echo sprintf("<option value=\"%s\">%s</option>",$key,$name);
					} 
				?>					
				</select>
				
				<label>类别Logo图片</label> 
				<input type="text" maxlength="50" name="FileId" value=""  id="FileId" onclick="javascript:selectPics('FileId',false);" class="input-xlarge" autofocus="true" required="true" />
				
				<label>位置</label> 
				<input type="text" maxlength="50" name="PositionIndex" value=""   class="input-xlarge" autofocus="true" required="true" />
				
				
				<label>包含游戏</label>
				<textarea id="Games"  name="Games" id="Games" onclick="javascript:selectGames('Games',true);" rows="8" style="width:800px;" class="input-xlarge"  autofocus="true"></textarea>
				
				<input type="hidden" name="submit" value="1" />
				<input type="hidden" name="AppId" id="AppId" value="" />
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
<?php $this->widget('application.widget.GameSelector'); ?>
<script type="text/javascript">
function selectPics(pvTxtId,CanMutilChoice)
{
	showPicSelector($("#AppId").val(),pvTxtId,$("#"+pvTxtId).val(),CanMutilChoice);
}
function selectGames(pvTxtId,CanMutilChoice)
{
	showGameSelector($("#AppId").val(),pvTxtId,$("#"+pvTxtId).val(),CanMutilChoice);
}
</script>