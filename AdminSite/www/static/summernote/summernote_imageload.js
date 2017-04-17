<script>
function showTextEditer()
	{
		$('#summernote').summernote({
	      	  height: 300,                 // set editor height
	    	  minHeight: null,             // set minimum height of editor
	    	  maxHeight: null,
	    	  lang: 'zh-CN',
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