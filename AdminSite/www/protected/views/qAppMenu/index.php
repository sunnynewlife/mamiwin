<div id="search" class="in collapse">
	<form class="form_search" action="/qAppMenu/index" method="POST" style="margin-bottom: 0px">
		<div style="float: left; margin-right: 5px; margin-left: 15px; margin-top: 25px;">
			<table class="tableApp" style="width:815px;">
					<tr>
						<td>自定义菜单每日调用次数不能超过5000次</td>
					</tr>
					<tr>
						<td colspan="3"><textarea id="EvnContent" maxlength="20000" name="EvnContent" rows="16" style="width:600px;" class="input-xlarge" autofocus="true"><?php echo print_r($menu);?></textarea></td>
					</tr>
			</table>		
		</div>

		<div class="btn-toolbar"
			style="padding-top: 25px; padding-bottom: 0px; margin-bottom: 0px">
			<input type="hidden" name=submit value="1">
			<button type="submit" class="btn btn-primary">创建菜单</button>
		</div>
		<div style="clear: both;"></div>
	</form>
</div>