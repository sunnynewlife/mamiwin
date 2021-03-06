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
	<div id="myTabContent">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="" enctype="multipart/form-data">
				<br/>
				<label>文件内容标题</label> 
				<input type="text" maxlength="50" name="File_Title" value="<?php echo $Material_Files["File_Title"];?>" class="input-xlarge" required="true" autofocus="true" />
				<br/>
				<label>资料文件类型</label> 
				<select name="File_Type">
					<option value="1" <?php echo $Material_Files["File_Type"]==DictionaryData::Material_Files_File_Type_Text?"selected":""; ?>>文本</option>
					<option value="2" <?php echo $Material_Files["File_Type"]==DictionaryData::Material_Files_File_Type_Audio?"selected":""; ?>>音频</option>
					<option value="3" <?php echo $Material_Files["File_Type"]==DictionaryData::Material_Files_File_Type_Video?"selected":""; ?>>视频</option>								
				</select>						
				
				<label>文件存储</label> 
				<select name="Location_Type" onchange="javascript:locationTypeChange();" id="Location_Type">
					<option value="1" <?php echo $Material_Files["Location_Type"]==DictionaryData::Material_Files_Location_Type_Text?"selected":""; ?>>站点内文本</option>
					<option value="2" <?php echo $Material_Files["Location_Type"]==DictionaryData::Material_Files_Location_Type_Binary?"selected":""; ?>>站点内二进制文件</option>
					<option value="3" <?php echo $Material_Files["Location_Type"]==DictionaryData::Material_Files_Location_Type_OutUrl?"selected":""; ?>>外站点URL</option>								
				</select>		

				<div id="summernote" style="display:<?php echo $Material_Files["Location_Type"]==DictionaryData::Material_Files_Location_Type_Text?"":"none"; ?>;"><p><?php echo $Material_Files["Location_Type"]==1?$Material_Content:""; ?></p></div>
				
				<br/>	
				<br/>
						
				<label id="lblSelectFile" style="display:<?php echo $Material_Files["Location_Type"]==DictionaryData::Material_Files_Location_Type_Binary?"":"none"; ?>;"><font color="red">如果需要修改文件请重新上传文件</font></label> 
				<input type="file"  enctype="multipart/form-data" name="File_Content" class="input-xlarge"  autofocus="true" id="txtSelectFile" style="display:<?php echo $Material_Files["Location_Type"]==DictionaryData::Material_Files_Location_Type_Binary?"":"none"; ?>;">
				
				<label id="lblUrl" style="display:<?php echo $Material_Files["Location_Type"]==DictionaryData::Material_Files_Location_Type_OutUrl?"":"none"; ?>;">外站点URL</label> 
				<input type="text" maxlength="500" name="File_Content_URL" value='<?php echo $Material_Files["Location_Type"]==3? $Material_Content:""; ?>' class="input-xlarge"  autofocus="true"  style="height:35px;width:520px;display:<?php echo $Material_Files["Location_Type"]==DictionaryData::Material_Files_Location_Type_OutUrl?"":"none"; ?>;" id="txtInputUrl"  />
				
				
				<input type="hidden" name="submit" value="1" />
				<input type="hidden" name="Article_Content" value="" id="Article_Content" />
				
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
				return true;				
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
				$("#lblSelectFile").hide();
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
				$("#lblSelectFile").show();		
				$("#txtSelectFile").show();
				break;
			case "3":
				$("#lblSelectFile").hide();
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
	    	  lang: 'zh-CN',
	    	  toolbar:[
		   				['FontStyle', ['bold', 'italic', 'underline', 'clear']],
		   				['font', ['strikethrough', 'superscript', 'subscript']],
		   			    ['fontsize', ['fontsize']],
		   			    ['color', ['color']],
		   			    ['table',['table']],
		   			    ['style',['height','style','normal','blockquote','pre','h1','h2','h3','h4']],
		   			    ['para', ['ul', 'ol', 'paragraph']],
		   			    ['misc',['undo','redo']],
		   			    ['options',['fullscreen','codeview']]	   
			  ],	    	  
	    	  callbacks:{
		    	  onImageUpload: function(files) {
		    		  console.log("image upload...");
		    		  var formData = new FormData();
		              formData.append('file',files[0]);
		              $.ajax({
		                  url : '/mWFiles/uploadImage',//后台文件上传接口
		                  type : 'POST',
		                  data : formData,
		                  processData : false,
		                  contentType : false,
		                  dataType:"json",
		                  success : function(data) {
			                  if(data.return_code==0){
		                      	$('#summernote').summernote('insertImage',data.url,'img');
			                  }else{
				                  alert(data.message);
			                  }
		                  }
		              });		    		  
		    	  }
				},
	    	});	
	}
  
    $(document).ready(function() {
<?php 
		if($Material_Files["Location_Type"]==DictionaryData::Material_Files_Location_Type_Text){
			echo "showTextEditer();";
		}
?>           	
    });
</script>