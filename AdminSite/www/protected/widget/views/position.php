<div class="content">
        <div class="header">
            <div class="stats">
				<p class="stat"><!--span class="number"></span--></p>
			</div>
            <h1 class="page-title"><{$content_header.menu_name}></h1>
        </div>
		<ul class="breadcrumb">
            <li><a href="<{$smarty.const.ADMIN_URL}><{$content_header.module_url}>"> <{$content_header.module_name}> </a> <span class="divider">/</span></li>
			<{if $content_header.father_menu}>
			<li><a href="<{$smarty.const.ADMIN_URL}><{$content_header.father_menu_url}>"> <{$content_header.father_menu_name}> </a> <span class="divider">/</span></li>
			<{/if}>
			<li class="active"><{$content_header.menu_name}></li>
			<{if $content_header.shortcut_allowed}>
				<{if $content_header.menu_id|in_array:$user_info.shortcuts_arr}>
					<a title= "移除快捷菜单" href="#"><li class="active"><i class="icon-minus" method="del" url="<{$smarty.const.ADMIN_URL}>/ajax/shortcut.php?menu_id=<{$content_header.menu_id}>"></i></li></a>
				<{else}>
					<a title= "加入快捷菜单" href="#"><li class="active"><i class="icon-plus" method="add" url="<{$smarty.const.ADMIN_URL}>/ajax/shortcut.php?menu_id=<{$content_header.menu_id}>"></i></li></a>
				<{/if}>
			<{/if}>
        </ul>
	<div class="container-fluid">
	<div class="row-fluid">
		<div class="bb-alert alert alert-info" style="display: none;">
			<span>操作成功</span>
		</div>