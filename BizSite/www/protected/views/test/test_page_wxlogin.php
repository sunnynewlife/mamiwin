<script>
var code="<?php echo $code;?>";
var state=<?php echo $state;?>;

$(document).ready(
    function login()
    {

    	 $.ajax({
             async:false,
             url: "/interface/wechatBind",
             type: "GET",
             dataType: 'json',
             data: { 
                 	"code":code,
                 	"userinfo":(state=="2"?"1":"0")
             },
             success: function (data) {
          		if(data.code==1){
              		// alert("绑定成功");
                    var url = data.data.url;
                    alert(url);
                    window.location.href= url  ;
              		// $("#JLoginBtn").hide();
              		// $("#GetUserInfoBtn").show();
              		// $("#ShareBtn").show();
              		// var loginInfo=data.data;
              		// $("#txtOpenId").val(loginInfo.LoginName);
              		// if(state==2){
    	          	// 	$("#txtNickName").val(loginInfo.OpenUserInfo.nickname);
    	          	// 	$("#btnUserImg").attr("src",loginInfo.OpenUserInfo.headimgurl);
              		// }
          		}else{
              		alert(data.message);
                    window.location.href="http://m.fumuwin.com" ;
          		}
             }
         });
    }
);

</script>