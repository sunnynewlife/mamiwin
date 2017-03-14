<!-- START 以上内容不需更改，保证该TPL页内的标签匹配即可 -->
<?php if (isset($alert_message)) echo $alert_message;?> 
<select name="group_id" onchange="javascript:location.replace('/group/permission?group_id='+this.options[this.selectedIndex].value)" style="margin:5px 0px 0px">
	<?php $this->widget('application.widget.Group',array('current_user_group'=>$group_id)); ?>
</select>
<form method="post" action="/group/permission">
<?php foreach ($permission as $val){
	if(count($val['menu']) >0) {?>
		<div class="block">
			<a href="#page-stats_<?php echo $val['module_id'];?>" class="block-heading" data-toggle="collapse"><?php echo $val['module_name'];?></a>
			<div id="page-stats_<?php echo $val['module_id'];?>" class="block-body collapse in">
			<?php foreach ($val['menu'] as $v){ ?>
			<label style="display: inline-block;font-size: 12px;width: 180px"><input type="checkbox" name="menu_ids[]" value="<?php echo $v['menu_id'];?>" <?php if(in_array($v['menu_id'], $group_permission)){ ?>checked="checked" <?php } ?>/><?php echo $v['menu_name'];?></label>
			<?php } ?>					
			</div>
		</div>
<?php }}?>										
	<div>
	    <input type="hidden" name="group_id" value="<?php echo $group_id; ?>" />
	    <input type="hidden" name="submit" value="1" />
		<button class="btn btn-primary">更新</button>
	</div>
</form>