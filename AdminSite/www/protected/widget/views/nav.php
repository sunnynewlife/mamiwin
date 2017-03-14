<div class="navbar">
        <div class="navbar-inner">
                <ul class="nav pull-right">
                    
                    <!-- li><a href="#" class="hidden-phone visible-tablet visible-desktop" role="button">设置模板</a></li -->
					 
					                    <li id="fat-menu" class="dropdown">
                        <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-cog"></i>设置<i class="icon-caret-down"></i>
						</a>
                        <ul class="dropdown-menu">
                            <li><a href="/admin/setting">系统设置</a></li>
                        </ul>
                    </li>
										
					<li id="fat-menu" class="dropdown">
                        <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
							
                            选择模板
                            <i class="icon-caret-down"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a href="/admin/setskin?skin=default">默认模板</a></li>
                            <li><a href="/admin/setskin?skin=blacktie">黑色领结</a></li>
                            <li><a href="/admin/setskin?skin=wintertide">冰雪冬季</a></li>
							<li><a href="/admin/setskin?skin=schoolpainting">青葱校园</a></li>
                        </ul>
                    </li>
					
					<li id="fat-menu" class="dropdown">
                        <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-user"></i> <?php echo Yii::app()->session['userName'];?>
                            <i class="icon-caret-down"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a tabindex="-1" href="/account/profile">我的账号</a></li>
                            <li><a tabindex="-1" href="/user/Logout">登出</a></li>
                        </ul>
                    </li>
                    
                </ul>
                <a class="brand" href="javascript:toggleNavMenu();"><span class="first"></span> <span class="second">Home Platform Management</span></a>
        </div>
</div>
<script type="text/javascript">
	function toggleNavMenu()
	{
		if($(".sidebar-nav").is(":visible")){
			$(".sidebar-nav").hide();
			$(".content").css("margin-left","0px");
			setCookie("sidebar","0px");
		}
		else{
			$(".sidebar-nav").show();
			$(".content").css("margin-left","240px");
			setCookie("sidebar","240px");
		}
	}
	function setCookie(c_name,value,expiredays)
	{
		var exdate=new Date()
		exdate.setDate(exdate.getDate()+expiredays)
		document.cookie=c_name+ "=" +escape(value)+
		((expiredays==null) ? "" : ";expires="+exdate.toGMTString())
	}
</script>