<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写文件信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="" enctype="multipart/form-data">
				<label>资料文件类型</label> 
				<select name="File_Type">
					<option value="1" <?php echo $Material_Files["File_Type"]==DictionaryData::Material_Files_File_Type_Text?"selected":""; ?>>文本</option>
					<option value="2" <?php echo $Material_Files["File_Type"]==DictionaryData::Material_Files_File_Type_Audio?"selected":""; ?>>音频</option>
					<option value="3" <?php echo $Material_Files["File_Type"]==DictionaryData::Material_Files_File_Type_Video?"selected":""; ?>>视频</option>								
				</select>						
				
				<label>文件内容标题</label> 
				<input type="text" maxlength="50" name="File_Title" value="<?php echo $Material_Files["File_Title"];?>" class="input-xlarge" required="true" autofocus="true" />
								
				<label><font color="red">如果需要修改文件请重新上传文件</font></label> 
				<input type="file"  enctype="multipart/form-data" name="File_Content" class="input-xlarge"  autofocus="true" >
				
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary">
						<strong>提交</strong>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>