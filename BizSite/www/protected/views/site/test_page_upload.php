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
				<br/>				
				<input type="file"  enctype="multipart/form-data" name="File_Content" class="input-xlarge"   autofocus="true" id="txtSelectFile" style="display:block;">
				
				
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="button" class="btn btn-primary" onclick="return uploadImage();">
						<strong>提交</strong>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
  <script>  	
	function uploadImage() {		
		var files = document.tab.file;
		  console.log("image upload...");
		  var formData = new FormData();
          formData.append('file',files[0]);
          $.ajax({
              url : '/interface/uploadFile',//后台文件上传接口
              type : 'POST',
              data : formData,
              processData : false,
              contentType : false,
              dataType:"json",
              success : function(data) {
                  if(data.return_code==0){
                  	// $('#summernote').summernote('insertImage',data.url,'img');
                  	alert(data.url);
                  }else{
                  	alert(data.message);
                  }
              }
          });		    		  
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
  
  </script>
