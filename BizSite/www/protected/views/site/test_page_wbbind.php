<script>
var code="<?php echo $code;?>";
$(document).ready(
  function login()
  {
  	 $.ajax({
           async:false,
           url: "/interface/wbBind",
           type: "GET",
           dataType: 'jsonp',
           jsonp: "callback",
           data: { 
               	"code":code,
           },
           success: function (data) {
        		if(data.code==1){
                var url = data.data.url;
                location.href= url  ;
            		// alert("登录成功");
            		// $("#JLoginBtn").hide();
            		// $("#GetUserInfoBtn").show();
            		// var loginInfo=data.data;
            		// $("#txtOpenId").val(loginInfo.LoginName);
            		// $("#txtNickName").val(loginInfo.OpenUserInfo.screen_name);
            		// $("#btnUserImg").attr("src",loginInfo.OpenUserInfo.avatar_large);
            		// $("#ShareBtn").show();
        		}else{
            		alert(data.message);
                window.location.href="http://m.fumuwin.com" ;
        		}
           }
       });
  }
);
</script>