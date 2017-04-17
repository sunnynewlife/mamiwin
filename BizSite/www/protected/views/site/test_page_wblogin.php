<div class="tips">
	<p>
		<i class="icon_alert"></i>新浪微博账号进入...,继续登录
	</p>
</div>
<div class="tn_box">
	<div class="f_wrap">
		<div class="f_b">
			<a id="JLoginBtn" href="#" class="btn" onclick="javascript:login();">完成用户登录</a>
		</div>
		<div class="f_b">
			<a id="GetUserInfoBtn" href="#" class="btn" onclick="javascript:showUser();" style="display:none;">查看登录用户信息</a>
		</div>
		<div class="f_box">		
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
</div>
<script>
var code="<?php echo $code;?>";

function login()
{
	 $.ajax({
         async:false,
         url: "/user/wbLogin",
         type: "GET",
         dataType: 'json',
         data: { 
             	"code":code,
         },
         success: function (data) {
      		if(data.code==0){
          		alert("登录成功");
          		$("#JLoginBtn").hide();
          		$("#GetUserInfoBtn").show();
          		var loginInfo=data.data;
          		$("#txtOpenId").val(loginInfo.LoginName);
          		$("#txtNickName").val(loginInfo.OpenUserInfo.screen_name);
          		$("#btnUserImg").attr("src",loginInfo.OpenUserInfo.avatar_large);
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