<div class="sidebar-nav" style="display:<?php echo $display; ?>;">
<?php

$menuItemHtmlTag=<<< EndOfMenuItemHTMLTag
<li><a %s href="%s">%s</a></li>
EndOfMenuItemHTMLTag;

	$currentItemFull=sprintf("%s_%s",$current_module_id,$current_sub_cat);	

	foreach ($module as $val){
		if (!empty($val['menu_list'])){
?>
			<a href="#sidebar_menu_<?php echo $val['module_id'];?>" class="nav-header collapsed" data-toggle="collapse">
			<i class="<?php echo $val['module_icon'];?>"></i><?php echo $val['module_name'];?> <i class="icon-chevron-up"></i></a>
				<?php if ($val['module_id'] == $current_module_id) {?>
					<ul id="sidebar_menu_<?php echo $val['module_id'];?>" class="nav nav-list collapse in">
				<?php }else{ ?>
					<ul id="sidebar_menu_<?php echo $val['module_id'];?>" class="nav nav-list collapse">
				<?php } ?>
				<?php 
					$lastSubGroupStr="";
					$lastGroupName="";
					
					foreach ($val['menu_list'] as $v){
						$groupName=isset($v["menu_group"])?$v["menu_group"]:"";
						$targetStr= (strtolower(substr($v['menu_url'],0,7))=='http://')?"target=_blank":"";
						if(empty($groupName)){
							echo sprintf($menuItemHtmlTag,$targetStr,$v['menu_url'],$v['menu_name']);
							
							if(empty($lastSubGroupStr)==false){
								echo "</ul>";
							}
							$lastSubGroupStr="";
							$lastGroupName="";
						}else{
							if(empty($lastSubGroupStr)){
								$lastSubGroupStr=sprintf("<a href=\"#sidebar_menu_%s_%s\" style=\"color:#ff007f;padding-left:15px;\" class=\"nav-header collapsed\" data-toggle=\"collapse\"><i class=\"icon-indent-left\"></i>%s<i class=\"icon-chevron-up\"></i></a>",$val['module_id'],$v["menu_id"],$groupName);
								$lastGroupName=$groupName;

								echo $lastSubGroupStr;
								$theItemFull=sprintf("%s_%s",$val['module_id'],$v["menu_group"]);
								echo sprintf("<ul id=\"sidebar_menu_%s_%s\" class=\"nav nav-list collapse%s\">",$val['module_id'],$v["menu_id"],($theItemFull==$currentItemFull?" in":""));
								echo sprintf($menuItemHtmlTag,$targetStr,$v['menu_url'],$v['menu_name']);
							}else {
								if($groupName!=$lastGroupName){	
									$lastSubGroupStr=sprintf("<a href=\"#sidebar_menu_%s_%s\" style=\"color:#ff007f;padding-left:15px;\" class=\"nav-header collapsed\" data-toggle=\"collapse\"><i class=\"icon-indent-left\"></i>%s<i class=\"icon-chevron-up\"></i></a>",$val['module_id'],$v["menu_id"],$groupName);
									$lastGroupName=$groupName;
									echo "</ul>";
									
									echo $lastSubGroupStr;
									$theItemFull=sprintf("%s_%s",$val['module_id'],$v["menu_group"]);
									echo sprintf("<ul id=\"sidebar_menu_%s_%s\" class=\"nav nav-list collapse%s\">",$val['module_id'],$v["menu_id"],($theItemFull==$currentItemFull?" in":""));
									echo sprintf($menuItemHtmlTag,$targetStr,$v['menu_url'],$v['menu_name']);
								}else{
									echo sprintf($menuItemHtmlTag,$targetStr,$v['menu_url'],$v['menu_name']);
								}	
							}
							
						}
					}
					if(empty($lastSubGroupStr)==false){
						echo "</ul>";
					}						
				?>
			</ul>
			<?php } ?>
<?php }?>
</div>
<?php if(Yii::app()->request->getParam('in_version','2.0')=='1.0'){?>
<script type="text/javascript">
	if($("#sidebar_menu_13").hasClass("in")){
		$("#sidebar_menu_14").addClass("in");
		$("#sidebar_menu_13").removeClass("in");
	}
</script>
<?php }?>
<div class="content" style="margin-left:<?php echo $width;?>;">
        <div class="header">
            <div class="stats">
			<p class="stat"><!--span class="number"></span--></p>
			</div>

            <h1 class="page-title"><?php echo $currentMenu['menu_name'];?></h1>
        </div>
		<ul class="breadcrumb">
            <li><a href="<?php echo $current_module['module_url'];?>"><?php echo  $current_module['module_name'];?> </a> <span class="divider">/</span></li>
			<?php if(isset($currentMenu['father_name'])){?>
			<li><a href=" <?php echo $currentMenu['father_url'];?>"> <?php echo $currentMenu['father_name'];?></a> <span class="divider">/</span></li>
			<?php } ?>
			
			<li class="active"><?php echo $currentMenu['menu_name'];?></li>
			<?php if ($currentMenu['shortcut_allowed']){?>
				<?php if(in_array($currentMenu['menu_id'],$user_info['shortcuts'])){?>
					<a title= "移除快捷菜单" href="#"><li class="active"><i class="icon-minus" method="del" url="/account/shortcut?menu_id=<?php echo $currentMenu['menu_id'];?>"></i></li></a>
				<?php }else{ ?>
					<a title= "加入快捷菜单" href="#"><li class="active"><i class="icon-plus" method="add" url="/account/shortcut?menu_id=<?php echo $currentMenu['menu_id'];?>"></i></li></a>
			<?php }} ?>
			
        </ul>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="bb-alert alert alert-info" style="display: none;">
			<span>操作成功</span>
		</div>