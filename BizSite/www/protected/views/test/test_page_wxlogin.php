<div class="tips">
	<p>
		<i class="icon_alert"></i>微信账号进入...,继续登录
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
		<div class="f_b">
			<a id="ShareBtn" href="#" class="btn" onclick="javascript:initWxJSSDK();" style="display:;">微信 JSSDK Init</a>
		</div>
		<div class="f_b">
			<a id="StartShareBtn" href="#" class="btn" onclick="javascript:alert('现在你可以点击右上角按钮进行分享了.');" style="display:none;">微信分享</a>
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
var state=<?php echo $state;?>;

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
          		alert("绑定成功");
          		$("#JLoginBtn").hide();
          		$("#GetUserInfoBtn").show();
          		$("#ShareBtn").show();
          		var loginInfo=data.data;
          		$("#txtOpenId").val(loginInfo.LoginName);
          		if(state==2){
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
         url: "/user/wechatLogin",
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
function initWxJSSDK()
{
	var url=window.location.href;
	alert("current url:"+url);
	$.ajax({
        async:false,
        url: "/user/wxJSAPIConfig",
        type: "GET",
        dataType: 'json',
        data: {url:url},
        success: function (data) {
       	 if(data.code==0){
				var wxConfig=data.data;
        		wx.config({
           		    debug: true,
           		    appId: wxConfig.appId,
           		    timestamp: wxConfig.timestamp,
           		    nonceStr: wxConfig.nonceStr, 
           		    signature: wxConfig.signature,
           		    jsApiList: ['checkJsApi',
                                'onMenuShareTimeline',
                                'onMenuShareAppMessage',
                                'onMenuShareQQ',
                                'onMenuShareWeibo',
                                'hideMenuItems',
                                'showMenuItems',
                                'hideAllNonBaseMenuItem',
                                'showAllNonBaseMenuItem',
                                'translateVoice',
                                'startRecord',
                                'stopRecord',
                                'onRecordEnd',
                                'hideOptionMenu',
                                'showOptionMenu',
                                'closeWindow',
                                'scanQRCode',
                                'chooseWXPay',
                                'openProductSpecificView'] 
           		});
        		wx.ready(function(){
					$("#StartShareBtn").show();
            		
            		var biz_url="http://api.fumuwin.com";
            		var share_title="父母赢-早教&托管";
            		var share_img="http://api.fumuwin.com/static/img/doc_3.jpg";
        			var _wechat_share_url =  "https://open.weixin.qq.com/connect/oauth2/authorize?appid="+wxConfig.appId+"&redirect_uri="+encodeURIComponent(biz_url)+"&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
        	        //分享到朋友圈
        	        wx.onMenuShareTimeline({
        	            title: share_title, 		// 分享标题
        	            link: _wechat_share_url, 	// 分享链接
        	            imgUrl: share_img, 			// 分享图标
        	            success: function () { 
            	            alert("分享到朋友圈 success");
        	            },
        	            cancel: function () { 
       	            	 	alert("分享到朋友圈 cancel");
        	            }
        	        });

        	        //分享给朋友
        	        wx.onMenuShareAppMessage({
        	            title: share_title,					// 分享标题
        	            desc: "专业早教&托管，关注婴幼儿成长", 	// 分享描述
        	            link: _wechat_share_url,			// 分享链接
        	            imgUrl: share_img, 					// 分享图标
        	            type: 'link', 						// 分享类型,music、video或link，不填默认为link
        	            dataUrl: '', 						// 如果type是music或video，则要提供数据链接，默认为空
        	            success: function () {
        	            	alert("分享给朋友 success");
        	            },
        	            cancel: function () { 
        	            	alert("分享给朋友 cancel");
        	            }
        	        });
        	        
        	        //分享到QQ
        	        wx.onMenuShareQQ({
        	            title: share_title, 
        	            desc: "专业早教&托管，关注婴幼儿成长",
        	            link: biz_url, 
        	            imgUrl: share_img,
        	            success: function () {
        	            	alert("分享到QQ success");           	
        	            },
        	            cancel: function () { 
        	            	alert("分享到QQ cancel");
        	            }
        	        });        
        	        
        	        //分享到腾讯微博
        	        wx.onMenuShareWeibo({
        	            title: share_title, 
        	            desc: "专业早教&托管，关注婴幼儿成长",
        	            link: biz_url, 
        	            imgUrl: share_img,
        	            success: function () {
        	            	alert("分享到腾讯微博 success");           	
        	            },
        	            cancel: function () { 
        	            	alert("分享到腾讯微博 cancel"); 
        	            }
        	        });   
            		
            	});
        		wx.error(function(res){
            		alert("wx error:"+res);
            	});       		
       		}else{
           		alert(data.message);
       		}           
        }
    });	
	
}
</script>