<!-- START 以上内容不需更改，保证该TPL页内的标签匹配即可 -->
<?php if (isset($alert_message)) echo $alert_message;?>  
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">菜单模块链接列表</a></li>
    </ul>	
	
	<div id="myTabContent" class="tab-content">
		  <div class="tab-pane active in" id="home">

           <form id="tab" method="post" action="/module/menu">
				 <table class="table table-striped">
              <thead>
                <tr>
					<th><input type="checkbox" id="checkAll" >全选</th>
					<th>#</th>
					<th>名称</th>
					<th>URL</th>
					<th>#Module</th>
					<th >菜单</th>
					<th >是否在线</th>
					<th >快捷菜单</th>
					<th>描述</th>
                </tr>
              </thead>
              <tbody>							  
              <?php foreach($menus as $menu) {?>
					<tr> 
					<td>
					<?php if ($menu["menu_id"] <=1){ ?>
					<input type="checkbox" name="menu_ids[]" value="<?php echo $menu["menu_id"]; ?>" disabled>
					<?php } else{ ?>
					<input type="checkbox" name="menu_ids[]" value="<?php echo $menu["menu_id"]; ?>" >
					<?php } ?>
					</td>
					<td><?php echo $menu["menu_id"]; ?></td>
					<td><?php echo $menu["menu_name"]; ?></td>
					<td><?php echo $menu["menu_url"]; ?></td>
					<td><?php echo $menu["module_id"]; ?></td>
					<td>
					<?php if($menu['is_show']){ ?>
						是
					<?php } else{ ?>
						否
					<?php } ?>
					</td>
					<td>
					<?php if($menu['online']){ ?>
						在线
					<?php } else{ ?>
						已下线
					<?php } ?>
					</td>
					<td>
					<?php if($menu['shortcut_allowed']){ ?>
						允许
					<?php } else{ ?>
						不允许
					<?php } ?>
					</td>
					<td><?php echo $menu['menu_desc'];?></td>
					</tr>
			<?php } ?>
              </tbody>
            </table> 
			<?php if($module_id > 1) {?>
				<label>选择菜单模块</label>
				<select name="module" class="input-xlarge" id="DropDownTimezone">
					<?php $this->widget('application.widget.ModuleWidget',array('current_module'=>$module_id)); ?>
				</select>
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary"><strong>修改菜单模块</strong></button>
				</div>
			<?php } ?>
		   </form>
        </div>
    </div>
</div>

<script type="text/javascript">
$("#checkAll").click(function(){
     if($(this).attr("checked")){
		$("input[name='menu_ids[]']").attr("checked",$(this).attr("checked"));
	 }else{
		$("input[name='menu_ids[]']").attr("checked",false);
	 }
});
</script>