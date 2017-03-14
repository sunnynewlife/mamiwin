<!-- START 以上内容不需更改，保证该TPL页内的标签匹配即可 -->
<?php if (isset($alert_message)) echo $alert_message;?> 
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">请填写功能资料</a></li>
    </ul>	
	<div id="myTabContent" class="tab-content">
		  <div class="tab-pane active in" id="home">
           <form id="tab" method="post" action="">
				<label>名称</label>
				<input type="text" name="menu_name" value="" class="input-xlarge" required="true" autofocus="true">
				<label>链接 <span class="label label-important">不可重复，以/开头的相对路径或者http网址</span></label>
				<input type="text" name="menu_url" value="" class="input-xlarge" placeholder="/"  required="true" >
				<label>所属模块</label>
				<select name="module_id" class="input-xlarge" id="DropDownTimezone">
						<?php $this->widget('application.widget.ModuleWidget') ?>
				</select>
				<label>是否左侧菜单栏显示</label>
				<select name="is_show" class="input-xlarge" >
					<option value="1" selected >是</option>
					<option value="0">否</option>
				</select>
				<label>所属菜单</label>
				<select name="father_menu" class="input-xlarge" id="DropDownTimezone">
					<option value="0" selected="selected" class="input-xlarge option" id="DropDownTimezone-0">无</option>
					<?php foreach ($module as $val){
								if (!empty($val['menu_list'])){
					?>
					<optgroup label="<?php echo $val['module_name'];?>">
						<?php foreach ($val['menu_list'] as $v){?>
						<option value="<?php echo $v['menu_id'];?>" class="input-xlarge option" id="DropDownTimezone-<?php echo $val['module_id'].'-'.$v['menu_id'];?>" ><?php echo $v['menu_name'];?></option>
						<?php }?>
					</optgroup>
					<?php }} ?>
				</select>				
				<label>是否允许快捷菜单 <span class="label label-important">修改/ 删除类链接不允许</span></label>
				<select name="shortcut_allowed" class="input-xlarge" >
					<option value="1" selected>是</option>
					<option value="0">否</option>
				</select>				
				<label>描述</label>
				<input type="hidden" name="submit" value="1" />
				<textarea name="menu_desc" rows="3" class="input-xlarge"></textarea>
				
				<label>所属子分类</label>
				<input type="text" name="menu_group" value="" class="input-xlarge" autofocus="true">				
				
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary"><strong>提交</strong></button>
				</div>
			</form>
        </div>
    </div>
</div>