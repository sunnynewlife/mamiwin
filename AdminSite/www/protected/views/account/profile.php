<!-- START 以上内容不需更改，保证该TPL页内的标签匹配即可 -->
		<?php if (isset($alert_message)) echo $alert_message;?>
		<div class="well">
				<ul class="nav nav-tabs">
							<?php if($do == "change_password"){ ?>
								<li ><a href="#home" data-toggle="tab">资料</a></li>
								<li class="active"><a href="#profile" data-toggle="tab">密码</a></li>
							<?php }else{ ?>
								<li class="active"><a href="#home" data-toggle="tab">资料</a></li>
								<li><a href="#profile" data-toggle="tab">密码</a></li>
							<?php } ?>
				</ul>
				<div id="myTabContent" class="tab-content">

						<?php if($do == "change_password"){ ?>
		 					 <div class="tab-pane fade" id="home">
						<?php }else{ ?>
						 	 <div class="tab-pane active in" id="home">
						<?php } ?>
				
						<form id="tab" method="post" action="/account/profile" autocomplete="off">
							<label>登录名 <span class="label label-info">不可修改</span></label> 
							<input type="hidden" name="do" value="modify_profile"> 
							<input type="text" name="user_name" value="<?php echo $user['user_name']; ?>" class="input-xlarge" readonly="true"> 
							<label>姓名</label> <input type="text" name="real_name" value="<?php echo $user['real_name']; ?>" class="input-xlarge" required="true">
							<label>手机</label> <input type="text" name="mobile" value="<?php echo $user['mobile']; ?>" class="input-xlarge" required pattern="\d{11}"> 
							<label>邮件</label> <input type="email" name="email" value="<?php echo $user['email']; ?>" class="input-xlarge" required="true"> 
							<label>描述</label> <textarea name="user_desc" rows="3" class="input-xlarge"><?php echo $user['user_desc'];?></textarea>
							<hr />
							<label>显示QuickNote</label> <select name="show_quicknote"
								class="input-xlarge" id="DropDownTimezone">
								<option value="1" class="input-xlarge option"
									id="DropDownTimezone-0" <?php if($user['show_quicknote'] == 1 ){?>selected="selected"<?php } ?> >显示</option>
								<option value="0" 
									class="input-xlarge option" id="DropDownTimezone-1" <?php if($user['show_quicknote'] == 0){?>selected="selected"<?php } ?> >不显示</option>
							</select>
							<div class="btn-toolbar">
								<button type="submit" class="btn btn-primary">
									<i class="icon-save"></i> 保存
								</button>
								<div class="btn-group"></div>
							</div>
						</form>
					</div>
						<?php if($do == "change_password"){ ?>
						<div class="tab-pane active in" id="profile">
						<?php }else{ ?>
						<div class="tab-pane fade" id="profile">
						<?php } ?>
						<form id="tab2" method="post" action="/account/profile" autocomplete="off">
							<input type="hidden" name="do" value="change_password"> <label>原密码</label>
							<input type="password" name="old" class="input-xlarge"> <label>新密码</label>
							<input type="password" name="new" class="input-xlarge">
							<div>
								<button class="btn btn-primary">更新</button>
							</div>
						</form>
					</div>
				</div>
			</div>