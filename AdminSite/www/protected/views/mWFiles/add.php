<?php if (isset($alert_message)) echo $alert_message;?>

<link href="/static/summernote/bootstrap.css" rel="stylesheet">
<script src="/static/summernote/jquery.js"></script> 
<script src="/static/summernote/bootstrap.js"></script> 
<link href="/static/summernote/summernote.css" rel="stylesheet">
<script src="/static/summernote/summernote.js"></script>
<script src="/static/summernote/lang/summernote-zh-CN.js"></script>

<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写文件信息</a></li>
	</ul>
	<div id="myTabContent" >
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="" enctype="multipart/form-data">
				<br/>
				<label>文件内容标题</label> 
				<input type="text" maxlength="50" name="File_Title" value="" class="input-xlarge" required="true" autofocus="true"  style="height:35px;" />
				<br/>
			
				<label>资料文件类型</label> 
				<select name="File_Type">
					<option value="1">文本</option>
					<option value="2">音频</option>
					<option value="3">视频</option>								
				</select>						
				<label>文件存储</label> 
				<select name="Location_Type" onchange="javascript:locationTypeChange();" id="Location_Type">
					<option value="1">站点内文本</option>
					<option value="2">站点内二进制文件</option>
					<option value="3">外站点URL</option>								
				</select>	
				
				<div id="summernote"></div>
				
				<br/>	
				<br/>				
				<input type="file"  enctype="multipart/form-data" name="File_Content" class="input-xlarge"   autofocus="true" id="txtSelectFile" style="display:none;">
				
				<label id="lblUrl" style="display:none;">外站点URL</label> 
				<input type="text" maxlength="50" name="File_Content_URL" value="" class="input-xlarge"  autofocus="true"  style="height:35px;width:520px;display:none;" id="txtInputUrl"  />
				
				<input type="hidden" name="submit" value="1" />
				<input type="hidden" name="Article_Content" value=""  id="Article_Content" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary" onclick="return checkUserInput();">
						<strong>提交</strong>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
  <script>
  	function checkUserInput()
  	{
  		var locaitonType=$("#Location_Type").val();
		switch(locaitonType){
			case "1":
				var article_txt=$('#summernote').summernote('code');
				if(article_txt!="" && article_txt!=null ){
					$("#Article_Content").val(article_txt);
					return true;
				}
				alert("文章内容不能为空！");
				break;
			case "2":
				var fileName=$("#txtSelectFile").val();
				if(fileName!="" && fileName!=null ){
					return true;
				}
				alert("请选择要上传的文件！");
				break;
			case "3":
				var file_Url=$("#txtInputUrl").val();
				if(file_Url!="" && file_Url!=null ){
					return true;
				}
				alert("请输入外部资源地址！");
				break;
			default:
				break;
		}  	  	
  	  	return false;
  	}
	function locationTypeChange()
	{
		var locaitonType=$("#Location_Type").val();
		switch(locaitonType){
			case "1":
				$("#txtSelectFile").hide();
				$("#lblUrl").hide();
				$("#txtInputUrl").hide();
				showTextEditer();
				break;
			case "2":
				$("#lblUrl").hide();
				$("#txtInputUrl").hide();
				$('#summernote').summernote('destroy');
				$('#summernote').hide();		
				$("#txtSelectFile").show();
				break;
			case "3":
				$("#txtSelectFile").hide();
				$('#summernote').summernote('destroy');
				$('#summernote').hide();
				$("#lblUrl").show();
				$("#txtInputUrl").show();								
				break;
			default:
				break;
		}
	}
	function showTextEditer()
	{
		$('#summernote').summernote({
	      	  height: 300,                 // set editor height
	    	  minHeight: null,             // set minimum height of editor
	    	  maxHeight: null,
	    	  lang: 'zh-CN'
	    	});	
	}
  
    $(document).ready(function() {
    	showTextEditer();
    });
  </script>
