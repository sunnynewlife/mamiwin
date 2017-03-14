<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">推广员反馈信息</a>
	<div id="page-stats" class="block-body collapse in">
		<form class="form_search"  method="POST" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">推广员账号：</td>
						<td><input type='text' maxlength='20'  name="Phone" readonly value="<?php echo $Feedback["PhoneNo"]; ?>" /></td>
						<td style="width:120px;">反馈时间：</td>
						<td><?php echo $Feedback["CreateDt"];?></td>
					</tr>
					<tr>
						<td>QQ号：</td>
						<td><input type='text' maxlength='20'  name="Phone" readonly value="<?php echo $Feedback["ContactQQ"];?>" /></td>
						<td>处理时间</td>
						<td><?php echo $Feedback["UpdateDt"];?></td>						
					</tr>
					<tr>
						<td>反馈内容</td>
						<td colspan=3>
							<textarea id="Content" maxlength="2000" name="Content" rows="8" style="width:600px;" class="input-xlarge" required="true" autofocus="true" readonly><?php echo $Feedback["Content"];?></textarea>
						</td>
					<tr>
					<tr>
						<td>回复内容</td>
						<td colspan=3>
							<textarea id="ReplyContent" maxlength="2000" name="ReplyContent" rows="8" style="width:600px;" class="input-xlarge" required="true" autofocus="true" ><?php echo $Feedback["ReplyContent"];?></textarea>
						</td>
					<tr>					
				</table>	
				<ul class="nav nav-tabs" style="border-bottom:0px;">
					<li style="padding-top:5px;padding-left:280px;"><a href="/fAppPromoter/feedbackIndex">取消</a></li>
					<li> 
						<div class="btn-toolbar">
							<button type="submit" class="btn btn-primary">
								<input type="hidden" name="submit" value="1" >							
								<strong>递交并通知用户</strong>
							</button>
					
						</div>
					</li>
				</ul>						
			</div>
			<div style="clear:both;"></div>
		</form>
	</div>
</div>