<?php if (isset($alert_message)) echo $alert_message;?>

<link href="/static/summernote/bootstrap.css" rel="stylesheet">
<script src="/static/summernote/jquery.js"></script> 
<script src="/static/summernote/bootstrap.js"></script> 
<link href="/static/summernote/summernote.css" rel="stylesheet">
<script src="/static/summernote/summernote.js"></script>
<script src="/static/summernote/lang/summernote-zh-CN.js"></script>


<div class="well">

	<div id="myTabContent">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="" enctype="multipart/form-data">
				<br/>
				<label>题集名称</label> 
				<input type="text" maxlength="200" name="Set_Name" value="<?php echo $Evaluation_Quesiton_Set["Set_Name"];?>" class="input-xlarge" required="true" autofocus="true" />
				<br/>
				<label>题目数量</label> 
				<input type="text" maxlength="50" name="Set_Qty" value="<?php echo $Evaluation_Quesiton_Set["Set_Qty"];?>" class="input-large" required="true" autofocus="true" />
				<br/>
				

				<div id="summernote"><p><?php echo $Evaluation_Quesiton_Set["Remark"]; ?></p></div>
				
				<br/>	
				<br/>
				
				
				<input type="hidden" name="submit" value="1" />
				<input type="hidden" name="Set_Remark" value="" id="Set_Remark" />
				
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
		var Remark=$('#summernote').summernote('code');
		if(Remark!="" && Remark!=null ){
			$("#Set_Remark").val(Remark);
			return true;
		}
  // 		var locaitonType=$("#Location_Type").val();
		// switch(locaitonType){
		// 	case "1":
		// 		var article_txt=$('#summernote').summernote('code');
		// 		if(article_txt!="" && article_txt!=null ){
		// 			$("#Article_Content").val(article_txt);
		// 			return true;
		// 		}
		// 		alert("文章内容不能为空！");
		// 		break;
		// 	case "2":
		// 		return true;				
		// 	case "3":
		// 		var file_Url=$("#txtInputUrl").val();
		// 		if(file_Url!="" && file_Url!=null ){
		// 			return true;
		// 		}
		// 		alert("请输入外部资源地址！");
		// 		break;
		// 	default:
		// 		break;
		// }  	  	
  // 	  	return false;
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
    	showTextEditer();          	
    });
</script>