<div id="search" class="in collapse">
<form class="form_search"  action="/gAppAskDBCheck/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>请输入查询的SQL</label>
		<textarea id="sql" name="sql" rows="8" style="width:800px;" class="input-xlarge" required="true" autofocus="true"><?php echo $sql;?></textarea>
		<input type="hidden" name="submit" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">查询</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<div id="page-stats" class="block-body collapse in">
		<textarea id="cacheResult" name="cacheResult" rows="20" class="input-xlarge" style="width:1250px;"><?php print_r($result)?></textarea>
	</div>
</div>