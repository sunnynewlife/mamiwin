<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">礼包信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" enctype="multipart/form-data">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">礼包归属</td>
						<td><input type="text" maxlength="50" name="AppName" id="AppName" value="<?php echo $QGiftItem["AppName"];?>" onclick="javascript:selectApp();" class="input-xlarge" autofocus="true"  disabled /></td>
						<td style="width:120px;">礼包标签</td>
						<td>
							<label for="TagType1" style="display:inline;"><input type="checkbox" value="1" name="TagType1" id="TagType1" style="margin-top:-2px;" <?php echo $QGiftItem["TagType1"]=="1"?" checked":""; ?> disabled  />&nbsp;荐</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<label for="TagType2" style="display:inline;"><input type="checkbox" value="1" name="TagType2" id="TagType2" style="margin-top:-2px;" <?php echo $QGiftItem["TagType2"]=="1"?" checked":""; ?> disabled  />&nbsp;独</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<label for="TagType3" style="display:inline;"><input type="checkbox" value="1" name="TagType3" id="TagType3" style="margin-top:-2px;" <?php echo $QGiftItem["TagType3"]=="1"?" checked":""; ?> disabled  />&nbsp;新</label>
						</td>
					</tr>
					<tr>
						<td>礼包名称&nbsp;<font color=red>*</font></td>
						<td><input type="text" maxlength="50" name="Name" id="Name" value="<?php echo $QGiftItem["Name"];?>"  class="input-xlarge" autofocus="true"  disabled /></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>页面显示总量</td>
						<td><input type="text" maxlength="50" name="TotalCount" id="TotalCount" value="<?php echo $QGiftItem["TotalCount"];?>" class="input-xSmall" autofocus="true"  disabled /></td>
						<td>页面显示剩余量</td>
						<td><input type="text" maxlength="50" name="RestCount" id="RestCount" value="<?php echo $QGiftItem["RestCount"];?>" class="input-xSmall" autofocus="true"  disabled /></td>
					</tr>
					<tr>
						<td>选择礼包码文件</td>
						<td colspan=3><input type="file"  enctype="multipart/form-data" name="GiftCodeFile" class="input-xlarge" required="true" autofocus="true" ></td>
					</tr>
					<tr>
						<td></td>
						<td colspan=3>
							<input type="hidden" name="submit" value="1" />
							<input type="hidden" name="AppId" Id="AppId" value="<?php echo $QGiftItem["AppId"];?>" />
							<div class="btn-toolbar">
								<button type="submit" class="btn btn-primary">
									<strong>导入</strong>
								</button>
							</div>
						</td>
					</tr>
				</table>
			</form>
				<table class="tableApp" style="width:815px;display:<?php echo $show_result=="1"?"":"none";?>;">
					<tr>
						<td style="width:120px; text-align:right;">本次导入结果</td>
						<td style="width:40px;"></td>
						<td>文件中礼包个数: <font color=red><?php echo $file_count;?></font>个，有效导入：<font color=red><?php echo $import_count;?></font>个。</td>
					</tr>
					<tr>
						<td style="text-align:right;">导入后</td>
						<td></td>
						<td>礼包总量:<font color=red><?php echo $all_count;?></font>个,可领取量:<font color=red><?php echo $rest_count;?></font>个。</td>
					</tr>
				</table>
		</div>
	</div>
</div>
