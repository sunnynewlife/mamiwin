<!--- START 以上内容不需更改，保证该TPL页内的标签匹配即可 --->
<?php if (isset($alert_message)) echo $alert_message;?>
<div class="block">
        <a href="#page-menu" class="block-heading" data-toggle="collapse">快捷菜单</a>
        <div id="page-menu" class="block-body collapse in">
		<h3>
		<?php if (count($menus) >0){ ?>
			<?php foreach($menus  as $menu){?>
			<span>
				<a href="<?php echo $menu['menu_url'];?>">
					<?php echo $menu['menu_name'];?>
				</a>
			</span>&nbsp;
			
		<?php }}?>
		</h3>
		</div> 
    </div>
	
	<div class="block">
        <a href="#page-stats" class="block-heading" data-toggle="collapse">当前用户信息</a>
        <div id="page-stats" class="block-body collapse in">
               <table class="table table-striped">    
							 <tr>
						        <td>用户名</td>
						        <td>真实姓名</td>
						        <td>手机号</td>
						        <td>Email</td>
						        <td>登录时间</td>
						        <td>登录IP</td>
					          </tr>
						      <tr>
						        <td><?php echo $user_info['user_name'];?></td>
						        <td><?php echo $user_info['real_name'];?></td>
						        <td><?php echo $user_info['mobile'];?></td>
						        <td><?php echo $user_info['email'];?></td>
						        <td><?php echo $user_info['login_time'];?></td>
						        <td><?php echo $user_info['login_ip'];?></td>
					          </tr>
					      </table>
		</div>
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<strong>注意！</strong>请保管好您的个人信息，一旦发生密码泄露请紧急联系管理员。</div>
        </div>
    </div>
	
