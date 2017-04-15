<div class="tips">
	<p>
		<i class="icon_alert"></i>忘记账号密码
	</p>
</div>
<div class="tn_box">
	<div class="f_wrap">
		<div class="f_box">
			<div class="f_item">
				<input id="txtPhone" type="text" placeholder="手机号码">
			</div>
			<div class="f_item">
				<input id="txtPwd" type="text" placeholder="登录密码">
			</div>
			<div class="f_item">
				<input id="txtImgCode" type="text" placeholder="图片验证码" class="input_s" style="width:40%;">
				<a href="javascript:refreshImg();"><img src="http://api.fumuwin.com/user/showImgCode" id="btnImg" /></a>
			</div>	
			<div class="f_item">
				<input id="txtSmsCode" type="text" placeholder="短信验证码" class="input_s" style="width:40%;">
				<a id="JSend" class="btn" href="#" onclick="javascript:sendSmsCode();"style="float:left;">获取短信验证码</a>
			</div>
		</div>
		<div class="f_b">
			<a id="JLoginBtn" href="#" class="btn" onclick="javascript:resetpwd();">重设置密码</a>
		</div>
		<br/>
	</div>
</div>
<script>
function resetpwd()
{
	 $.ajax({
         async:false,
         url: "http://api.fumuwin.com/user/resetpwd",
         type: "GET",
         dataType: 'jsonp',
         jsonp: "callback",
         data: { 
             	"phone":$("#txtPhone").val(),
             	"password":$("#txtPwd").val(),
             	"img_code":$("#txtImgCode").val(),
             	"sms_code":$("#txtSmsCode").val()
         },
         success: function (data) {
      		if(data.code==0){
          		alert("密码重设成功");
      		}else{
          		alert(data.message);
          		refreshImg();
      		}
         }
     });
}
function sendSmsCode()
{
	 $.ajax({
         async:false,
         url: "http://api.fumuwin.com/user/sendSms",
         type: "GET",
         dataType: 'jsonp',
         jsonp: "callback",
         data: {"phone":$("#txtPhone").val()},
         success: function (data) {
      		if(data.code==0){
          		alert("发送短信成功");
      		}else{
          		alert(data.message);
      		}
         }
     });	
}
function refreshImg()
{
	var t=new Date();
	var url="http://api.fumuwin.com/user/showImgCode?_v="+t.getTime();
	$("#btnImg").attr("src",url);
}
</script>