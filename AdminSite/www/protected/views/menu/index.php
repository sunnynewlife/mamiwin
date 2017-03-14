<!--- START 以上内容不需更改，保证该TPL页内的标签匹配即可 --->
<div class="btn-toolbar"  style="margin-bottom:2px;">
    <a href="/menu/add"  class="btn btn-primary"><i class="icon-plus"></i> 功能</a>
	<a data-toggle="collapse" data-target="#search"  href="#" title= "检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search"></i></button></a>
</div>
<div id="search" class="collapse">
<form class="form_search"  action="" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>选择菜单模块</label>
		<select name="module_id" class="input-xlarge" id="DropDownTimezone">
<option value="0" class="input-xlarge option" id="DropDownTimezone-0">全部</option>
<?php $this->widget('application.widget.ModuleWidget',array('current_module'=>$module_id)); ?>
</select>

	</div>
	<div style="float:left;margin-right:5px">
		<label>查询所有请留空</label>
		<input type="text" name="menu_name" value="" placeholder="输入菜单名称" > 
		<input type="hidden" name="search" value="1" >
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>

    <div class="block">
        <a href="#page-stats" class="block-heading" data-toggle="collapse">功能列表</a>
        <div id="page-stats" class="block-body collapse in">

               <table class="table table-striped">
              <thead>
                <tr>
					<th style="width:30px">#</th>
					<th style="width:90px">名称</th>
					<th style="width:180px">URL</th>
					<th style="width:80px">所属模块</th>
					<th style="width:80px">菜单</th>
					<th style="width:80px">所属菜单</th>
					<th style="width:80px">是否在线</th>
					<th style="width:80px">快捷菜单</th>
					<th style="width:180px">描述</th>
					<th style="width:80px">操作</th>
                </tr>
              </thead>
              <tbody>							  			 
				 <?php foreach ($menus as $menu ) {?>	 
					<tr>
					<td><?php echo $menu["menu_id"];?></td>
					<td><?php echo $menu["menu_name"];?></td>
					<td><?php echo $menu["menu_url"];?></td>
					<td><?php echo $menu["module_name"];?></td>
					<td>
					<?php if($menu['is_show']){ ?>
						是
					<?php }else{?>
						否
					<?php }?>
					</td>
					<td><?php if($menu['father_menu'] > 0){ echo $menu["fater_menu_name"];}?></td>
					
					<td>
					<?php  if($menu['online']){ echo '在线';}else{echo '已下线';}?>
					</td>
					<td><?php  if($menu['shortcut_allowed']){ echo '允许';}else{echo '不允许';}?>
					</td>
					<td><?php echo $menu["menu_desc"];?></td>
					<td>
					<a href="/menu/modify?menu_id=<?php echo $menu["menu_id"];?>" title= "修改" ><i class="icon-pencil"></i></a>
					<?php if($menu["menu_id"] > 100){ ?>
					&nbsp;
					<a data-toggle="modal" href="#myModal" title= "删除" ><i class="icon-remove" href="/menu/del?page_no=<?php echo $page_no;?>&menu_id=<?php echo $menu["menu_id"];?>" ></i></a>
					<?php } ?>
					</td>
					</tr>
				<?php } ?>
              </tbody>
            </table> 
<!--- START 分页模板 --->
               <?php echo $page;?>
<!--- END 分页--->			   
        </div>
    </div>
	
<!---操作的确认层，相当于javascript:confirm函数--->
<script>
				$('.icon-remove').click(function(){
						
						var href=$(this).attr('href');
						bootbox.confirm('确定要这样做吗？', function(result) {
							if(result){

								location.replace(href);
							}
						});		
					})
					
				</script>