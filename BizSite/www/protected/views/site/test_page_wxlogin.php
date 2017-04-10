<div class="tips">
	<p>
		<i class="icon_alert"></i>微信账号进入...,继续登录
	</p>
</div>
<div class="tn_box">
	<div class="f_wrap">
		<div class="f_b">
			<a id="JLoginBtn" href="#" class="btn" onclick="javascript:login();">完成用户登录</a>
			<a id="GetUserInfoBtn" href="#" class="btn" onclick="javascript:showUser();">查看登录用户信息</a>
			<a id="ShareBtn" href="#" class="btn" onclick="javascript:share();" style="display:none;">微信分享</a>
		</div>
		<div class="f_item">
				<input id="txtOpenId" type="text" placeholder="OpenId">
		</div>
		<div class="f_item">
				<input id="txtNickName" type="text" placeholder="User nickname">
		</div>
		<div class="f_item">
				<img src="" id="btnUserImg" />
		</div>							
	</div>
</div>
<script>
var code="<?php echo $code;?>";
var state=<?php echo $state;?>
function login()
{
	 $.ajax({
         async:false,
         url: "/user/wxLogin",
         type: "GET",
         dataType: 'json',
         data: { 
             	"code":code,
             	"userinfo":(state=="2"?"1":"0")
         },
         success: function (data) {
      		if(data.code==0){
          		alert("登录成功");
          		$("#JLoginBtn").hide();
          		$("#GetUserInfoBtn").show();
          		var loginInfo=data.data;
          		$("#txtOpenId").val(loginInfo.LoginName);
          		if(code==2){
	          		$("#txtNickName").val(loginInfo.OpenUserInfo.nickname);
	          		$("#btnUserImg").attr("src",loginInfo.OpenUserInfo.headimgurl);
          		}
      		}else{
          		alert(data.message);
      		}
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