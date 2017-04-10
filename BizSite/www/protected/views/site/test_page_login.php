<div class="tips">
	<p>
		<i class="icon_alert"></i>账号登录
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
		</div>
		<div class="f_b">
			<a id="JLoginBtn" href="#" class="btn" onclick="javascript:login();">立即登录</a>
		</div>
		<div class="f_b">
			<a id="JLoginBtn" href="#" class="btn" onclick="javascript:logout();">注销</a>
		</div>	
		<div class="f_b">
			<a id="JLoginBtn" href="#" class="btn" onclick="javascript:showUser();">查看登录账号信息</a>
		</div>	
	</div>
</div>
<script>
function login()
{
	 $.ajax({
         async:false,
         url: "/user/login",
         type: "GET",
         dataType: 'json',
         data: { 
             	"phone":$("#txtPhone").val(),
             	"password":$("#txtPwd").val()
         },
         success: function (data) {
      		if(data.code==0){
          		alert("登录成功");
      		}else{
          		alert(data.message);
      		}
         }
     });
}
function logout()
{
	 $.ajax({
         async:false,
         url: "/user/logout",
         type: "GET",
         dataType: 'json',
         data: {},
         success: function (data) {
             alert("已注销");
         }
     });
}
function showUser()
{
	 $.ajax({
         async:false,
         url: "/user/getLoginInfo",
         type: "GET",
         dataType: 'json',
         data: {},
         success: function (data) {
        	 if(data.code==0){
            		alert("登录的用户:"+data.data.LoginName+" IDX="+data.data.IDX+" 登录来源:"+data.data.AcctSource);
        		}else{
            		alert(data.message);
        		}           
         }
     });	
}
</script>