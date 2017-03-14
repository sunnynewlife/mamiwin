<!-- START 以上内容不需更改，保证该TPL页内的标签匹配即可 -->
<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">请填写功能资料</a></li>
    </ul>	
	
	<div id="myTabContent" class="tab-content">
		  <div class="tab-pane active in" id="home">
           <form id="tab" method="post" action="/menu/modify">
				<label>名称</label>
				<input type="text" name="menu_name" value="<?php echo $menu['menu_name']; ?>" class="input-xlarge" required="true">
				<label>链接 <span class="label label-important">不可重复</span></label>
				<input type="text" name="menu_url" value="<?php echo $menu['menu_url']; ?>" class="input-xlarge" required="true" >
				<label>所属模块</label>
				<select name="module_id" class="input-xlarge" id="DropDownTimezone" <?php if ($menu['menu_id'] <=100){ ?>disabled="true"<?php }?>>
						<?php $this->widget('application.widget.ModuleWidget',array('current_module'=>$menu['module_id'])); ?>
				</select>				
				<label>是否显示为左侧菜单</label>
				<select name="is_show" class="input-xlarge" id="DropDownTimezone">
					<option value="1" class="input-xlarge option" id="DropDownTimezone-0" <?php if($menu['is_show'] == 1){?>selected="selected"<?php } ?>>显示</option>
					<option value="0" class="input-xlarge option" id="DropDownTimezone-1" <?php if($menu['is_show'] == 0){?>selected="selected"<?php } ?>>不显示</option>
				</select>
				
				<label>所属菜单</label>
				<select name="father_menu" class="input-xlarge" id="DropDownTimezone">
					<option value="0" selected="selected" class="input-xlarge option" id="DropDownTimezone-0">无</option>
					<?php foreach ($module as $val){
								if (!empty($val['menu_list'])){
					?>
					<optgroup label="<?php echo $val['module_name'];?>">
						<?php foreach ($val['menu_list'] as $v){?>
						<option value="<?php echo $v['menu_id'];?>" class="input-xlarge option" id="DropDownTimezone-<?php echo $val['module_id'].'-'.$v['menu_id'];?>" 
								<?php if($menu['father_menu'] == $v['menu_id']){?>selected="selected"<?php }?>><?php echo $v['menu_name'];?></option>
						<?php }?>
					</optgroup>
					<?php }} ?>
				</select>
				<label>是否有效</label>
				<select name="online" class="input-xlarge" id="DropDownTimezone">
				<option value="1" <?php if($menu['online'] == 1){?>selected="selected"<?php } ?> class="input-xlarge option" id="DropDownTimezone-0">在线</option>
				<option value="0" <?php if($menu['online'] == 0){?>selected="selected"<?php } ?> class="input-xlarge option" id="DropDownTimezone-1">下线</option>
				</select>
				<label>是否允许快捷菜单 <span class="label label-important">修改/ 删除类链接不允许</span></label>
				<select name="shortcut_allowed" class="input-xlarge" id="DropDownTimezone">
				<option value="1"  <?php if($menu['shortcut_allowed'] == 1){?>selected="selected"<?php } ?> class="input-xlarge option" id="DropDownTimezone-0">允许</option>
				<option value="0"  <?php if($menu['shortcut_allowed'] == 0){?>selected="selected"<?php } ?> class="input-xlarge option" id="DropDownTimezone-1">不允许</option>
				</select>
				<label>描述</label>
				<input type="hidden" name="submit" value="1" />
				<input type="hidden" name="menu_id" value="<?php echo $menu['menu_id'];?>" />
				<textarea name="menu_desc" rows="3" class="input-xlarge"><?php echo $menu['menu_desc']; ?></textarea>
				
				<label>所属子分类</label>
				<input type="text" name="menu_group" value="<?php echo $menu['menu_group']; ?>" class="input-xlarge" autofocus="true">				
				
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary"><strong>提交</strong></button>
				</div>
			</form>
        </div>
    </div>
</div>