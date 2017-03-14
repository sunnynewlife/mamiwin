    /** Server请求 **/
     var BASE_URL = "/";

    //当前页面浏览器链接地址
     var CURRENT_PAGELINK = document.location.href;
    //增加分享判断，分享出去的不显示下部菜单 
    if(CURRENT_PAGELINK.indexOf('?') >= 0){
        CURRENT_PAGELINK = CURRENT_PAGELINK + "&isShared=1";
    }else{
        CURRENT_PAGELINK = CURRENT_PAGELINK + "?isShared=1";
    }
    
    //抽奖转盘的标志
    var click=false; 

    //转盘抽奖抽中的索引
    var LAST_SELECT_INDEX = 0;
    //转盘抽奖中抽中的奖品
    var LAST_SELECT_AWARD = "";
    //转盘抽奖中抽中的奖品
    var LAST_SELECT_REMARK = "";

    var STATIC_URL = {
        Sms:"site/sendCode",                       //1.3.1获取短信验证码|----| 参数 ?phone={mobileNo}&countrycode={countryCode}
        Verify:"site/validCode",                  //1.3.2验证手机信息|----| 参数 ?countryCode={countryCode}&mobileNo={mobileNo}&smsCode={smsCode}&deviceId={deviceId}&phoneType={phoneType}&versionName={versionName}&channel={channel}
        User:"user",                            //1.5   获取用户信息
        Share:"wx/share",                       //微信分享后记录日志
        Lottery:"wx/drawlottery"               //抽奖大转盘
    };


    //参数名
   var STATIC_PARAM = window.STATIC_PARAM = {
        SMS_PHONE:"phone",
        SMS_COUNTRYCODE:"countrycode",
        VERIFY_PHONE:"mobileNo",
        VERIFY_COUNTRYCODE:"countryCode",
        VERIFY_SMSCODE:"smsCode",
        VERIFY_DEVICEID:"deviceId",
        VERIFY_CHANNEL:"channel",
        VERIFY_APNSTOKEN:"APNStoken",
        CLIENT_TYPE:"clientType",
        BIND_ALIPAY:"alipayAccount",
        BIND_NAME:"name",
        BIND_OLDACCOUNT:"oldAccount",
        BIND_NEWACCOUNT:"newAccount",
        AMOUNT:"amount",
        EMAIL:"email",
        WPWD:"wpwd",
        OLDPWD:"oldPwd",
        NEWPWD:"newPwd",
        FEEDBACK_MSG:"content",
        FEEDBACK_QQ:"qq",
        TYPE:"type",
        GAME_ID:"gameId",
        GAME_APPID:"appid",
        GAME_VERSIONID:"versionid",
        DATE_RANGE:"dateRange",
        PAGE_INDEX:"pageIndex",
        PAGE_INDEX2:"page",
        SESSION_ID:"Session-Id",
        SHARE_TYPE:"shareType",
        PAGE_ID:"page_id" 
   }


    //静态变量
    window.STATIC = STATIC = {
         //全局页面id
        JHead:"J_head",
        JHeadRight:"JHeadRight",
        JWrapper:"J_wrapper",
        JOverlay:"J_overlay",
        JBack:"J_back",
        JTitle:"J_title",
        JSend:"JSend",
        JMask:"mask",
        JGetMore:"moreData",
        JOverClose:"overClose",
        //cookie使用
        CSmsTime:"smsTime",      //登录获取验证码倒计时使用,目前统一使用一个session
        CAlipay:"alipay",
        CUser:"user",
        CLogininfo:"logininfo",
        CSessionId:"SessionId",
        CUnreadCount:"unreadCount",
        CPhone:"mobileNo",
        CPayAccount:"account",
        CPayName:"name",
        CPayHasWpwd:"hasCreateWpwd",
        CMessageList:"messageList",
        //中国区号
        PhoneCode:86,
        AppChannel:"webapp",
        IsLogin:"isLogin",
        MineMenu:"mineMenu",             //底部“我的”菜单
        UpPop:"upPop",                  //底部"我的"菜单弹出框
        DownLoadGame:"downLoadGame",            //游戏详情“下载”按钮
        TipFloat:"tipFloat",                   //下载“浮层”
        TipFloat_box:"tipFloat_box",          //下载“浮层”
        TipFloat2:"tipFloat2",               //透明的浮层
        J_Iframe:"J_Iframe",                //游戏详情页面隐藏的iframe
        BTN_GET:"btn_get",                  //转盘中的抽奖按钮
        Lottery_mask:"lottery_mask",       //转盘中浮层
        Lottery_tc_box1:"lottery_tc_box1",  //转盘中弹出层1
        Lottery_tc_box2:"lottery_tc_box2",  //转盘中弹出层2
        Lottery_pp_close:"lottery_pp_close",  //转盘中抽中的弹出层
        Lottery_tc_content:"lottery_tc_content",  //转盘中未抽中的弹出层的消息框
        Lottery_tc_content2:"lottery_tc_content2"  //转盘中抽中的弹出层的消息框
    };


    //返回参数
    var SERVER_JSON = window.SERVER_JSON = {
        ReturnCode:"return_code",
        ReturnMessage:"return_message",
        Data:"data"
    };



     //错误编号
    var SERVER_ERROR_CODE = window.SERVER_ERROR_CODE = {
         SUCCESS:0                      //成功
        ,NOT_LOGIN:2002                 //未登录
        ,VERIFYCODE_ERROR:4003         //验证码错误
        ,VERIFYCODE_ERROR2:4004         //验证码验证失败
    }



    var SERVER_ERROR_MSG = {
        NoData:"服务器繁忙，请稍后！"
    };


    //正则验证
   var regexEnum = window.regexEnum = {
        mobile:"^(13|14|15|17|18)[0-9]{9}$",
        mail:/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/,
        url:"^((https|http|ftp|rtsp|mms)?://)",
        password:"[0-9]{6}$",
        amount:/^([1-9][\d]{0,11}|0)(\.[\d]{1,2})?$/,
        number:/^[0-9]*$/
    };



     /**
     * 0出现的概率为%23
     */
    var rate0 = 0.23;
    /**
     * 1出现的概率为%8
     */
    var rate1 = 0.08;
    /**
     * 2出现的概率为%3
     */
    var rate2 = 0.03;
    /**
     * 3出现的概率为%3
     */
    var rate3 = 0.03;
    /**
     * 4出现的概率为%3
     */
    var rate4 = 0.03;
    /**
     * 5出现的概率为%8
     */
    var rate5 = 0.08;
    /**
     * 6出现的概率为%23
     */
    var rate6 = 0.23;
    /**
     * 7出现的概率为%23
     */
    var rate7 = 0.23;

    


    $(function(){



        //底部“我的”菜单
        $('#'+STATIC.MineMenu).on(getTapName(),function(){
            toggleChildMenu();
        });


        //游戏详情点击“下载”,显示浮层
        $('#'+STATIC.DownLoadGame).on(getTapName(),function(){
            // toggleFloat.showFloat();
            downLoadGame();
        });


        //游戏详情点击“浮层”，将浮层隐藏起来
        $('#'+STATIC.TipFloat).on(getTapName(),function(){
            toggleFloat.hideFloat();
        });


        //点击透明的浮层，隐藏“我的”子菜单
        $('#'+STATIC.TipFloat2).on(getTapName(),function(){
            hideTransparentFloat();
        });



        //针对于游戏详情页面，在非微信浏览器中，不用再二次点击“下载”链接进行下载游戏
        initDownloadGame();


       
        //绑定“抽奖”按钮的点击事件
        $('#'+STATIC.BTN_GET).on(getTapName(),function(){
             initLottery.init();
        });


        //抽奖弹出框的关闭事件
        $('#'+STATIC.Lottery_mask).on(getTapName(),function(e){
            $('#'+STATIC.Lottery_mask).hide();
            $('#'+STATIC.Lottery_tc_box1).hide();
        });


        //转盘中抽中奖品的弹出框的关闭事件
        $('#'+STATIC.Lottery_pp_close).on(getTapName(),function(e){
            $('#'+STATIC.Lottery_tc_box2).hide();
        });

        

        //通过config接口注入权限验证配置
        wx.config({
            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: _JSSDK_CONFIG_DATA.appId, // 必填，公众号的唯一标识
            timestamp: _JSSDK_CONFIG_DATA.timestamp, // 必填，生成签名的时间戳
            nonceStr: _JSSDK_CONFIG_DATA.nonceStr, // 必填，生成签名的随机串
            signature: _JSSDK_CONFIG_DATA.signature,// 必填，签名，见附录1
            jsApiList: [
                        'checkJsApi',
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
                        'openProductSpecificView'
                        ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });
    });






    /******************************************** S 公用方法**********************************************/
     //发送验证码倒计时
    var Countdown = window.Countdown = {
        maxTime:60,
        limitTime:0,
        isCountdown:function(){
            var _oldTime = 0;
            var _nowTime = new Date().getTime();
            this.limitTime = (_nowTime - _oldTime)/1000;
            if(this.limitTime > 60){
                return true;
            }else{
                return false;
            }
        },
        getTime:function(){
            return this.maxTime - this.limitTime;
        },
        doCountDown:function(_time){
            _time = Math.ceil(_time);
            if(_time>0){
                window.canGetSMS = false;
                $('#'+STATIC.JSend).addClass('btn_gray');
                $('#'+STATIC.JSend).html(_time+"秒后重发");

                clearInterval(window.timerC);
                window.timerC = setInterval(function(){
                    _time = _time - 1;
                    if(_time<=0){
                        $('#'+STATIC.JSend).removeClass('btn_gray');
                        $('#'+STATIC.JSend).html("发送验证码");
                        clearInterval(window.timerC);
                        window.canGetSMS = true;
                    }else{
                        $('#'+STATIC.JSend).html(_time+"秒后重发");
                    }

                },1000);
            }
        }

    };



    //用于判断是移动还是PC
    var getTapName = window.getTapName = function(){
        if(isMobile()){
            return "click";
//            return "tap";
        }else{
            return "click";
        }

    }



     var Storage = window.Storage = {
        expires:365,
        init:function() {
            //检测浏览器是否支持localStorage
            if(typeof window.localStorage == 'undefined'){
                return false;
            }else{
                return true;
            }
        },
        get:function(name){
            if(this.init()){
                return localStorage.getItem(name);
            }else{
                return this.getCookie(name);
            }
        },
        set:function(name,value){
            if(this.init()){
                try{
                    localStorage.setItem(name,value);
                }catch(e){
                    if(e.name == 'QuotaExceededError'){
                        localStorage.clear();
                        try{
                            localStorage.setItem(key,value);
                        }catch(e){
                            alert('请关闭浏览器无痕浏览模式');
                        }
                    }
                }


            }else{
                this.getCookie(name,value);
            }
        },
        getCookie:function(name){
            return $.cookie(name);
        },
        setCookie:function(name,value){
            $.cookie(name,value,this.expires);
        }
    };



      /**
     * @title json格式转string
     * @type {JsonToStr}
     */
    var JsonToStr = window.JsonToStr = function (o) {
        var r = [];
        if (typeof o == "string" || o == null) {
            return '"' + o + '"';
        }
        if (typeof o == "object") {
            if (!o.sort) {
                r[0] = "{"
                var oLength = 0;
                for (var i in o) {
                    r[r.length] = '"' + i + '"';
                    r[r.length] = ":";
                    r[r.length] = JsonToStr(o[i]);
                    r[r.length] = ",";
                    oLength++;
                }
                if (oLength)
                    r[r.length - 1] = "}";
                else
                    r[r.length - 1] = "{}";
            } else {
                r[0] = "["
                for (var i = 0; i < o.length; i++) {
                    r[r.length] = JsonToStr(o[i]);
                    r[r.length] = ",";
                }
                if (o.length)
                    r[r.length - 1] = "]";
                else
                    r[r.length - 1] = "[]";
            }
            return r.join("");
        }
        return o.toString();
    }



    //弹出框
    var UIDialog = window.UIDialog = function(msg,fn){
        var modalTPL = '<div class="ui-dialog">\
            <div class="ui-dialog-content">\
                <span class="close L_sure_btn"></span>\
                <h2 class="title">信息提示</h2>\
                <div id="dialog1" title="登陆提示" style="display: block; padding-top:20px; text-align:center; color:#353535">\
                    <p>' + msg + '</p>\
                </div>\
            </div>\
            <div class="ui-dialog-btns">\
                <a class="ui-btn ui-btn-1 L_sure_btn" data-key="">确认</a>\
            </div>\
        </div>';

        var dialog = Notification.confirm('', modalTPL, function() {});
        dialog.show();

        $('.L_modal_close').on('tap', function(e) {
            e && e.preventDefault();
            dialog.hide();
        });

        $('.L_sure_btn').on(getTapName(),function(e){
            e && e.preventDefault();
            dialog.hide();
            if(!IsEmpty(fn)){
                fn();
            }
        });
    };



    //判断对象是否为空。包括"" null undefined
    var IsEmpty = window.IsEmpty = function(obj){
//        console.log(typeof obj);
        //如果是布尔型,则直接返回有值
        if(typeof obj == "boolean"){
//            console.log('进到这里了');
            return false;
        }

        if(typeof obj == "undefined"){
//            console.log('undefined');
          return true;
        }

        if(typeof obj == "string" && obj == ""){
//            console.log('空');
          return true;
        }

        if(obj == null){
//            console.log('非字符串null');
          return true;
        }

        if(obj == "null"){
//            console.log('字符串null');
            return true;
        }


        return false;

    };



    //判断是否是移动端
    window.isMobile = function(){
        var a = navigator.userAgent||navigator.vendor||window.opera;
        if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))){
            return true;
        }else{
            return false;
        }
    }

    //判断是否是ios
    window.isIos = function(){
        var a = navigator.userAgent||navigator.vendor||window.opera;
        var i = !!a.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
        return i;
    };

    //判断浏览器版本过低
    window.isLowBrowser = function(){
        var a = navigator.userAgent||navigator.vendor||window.opera;
        var i = !!a.match(/(MSIE 6.0|MSIE 7.0|MSIE 8.0|MSIE 9.0)/ig);
        return i;
    }



    /******************************************** E 公用方法**********************************************/






    /******************************************** S 微信操作 **********************************************/
    wx.ready(function(){
        var _wechat_share_url =  "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8a417e8a8315d161&redirect_uri="+encodeURIComponent(CURRENT_PAGELINK)+"&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
        //分享到朋友圈
        wx.onMenuShareTimeline({
            title: _JSSDK_SHARE_DATA.share, // 分享标题
            link: _wechat_share_url, // 分享链接
            imgUrl: _JSSDK_SHARE_DATA.img, // 分享图标
            success: function () { 
                 var _param = {};
                _param[STATIC_PARAM.SHARE_TYPE] = 2;
                _param[STATIC_PARAM.PAGE_ID] = _JSSDK_SHARE_DATA.page_id;
                Server.Share(_param,function(data){
                })
            },
            cancel: function () { 
                // 用户取消分享后执行的回调函数
            }
        });

        //分享给朋友
        wx.onMenuShareAppMessage({
            title: _JSSDK_SHARE_DATA.send_title, // 分享标题
            desc: _JSSDK_SHARE_DATA.send_desc, // 分享描述
            link: _wechat_share_url, // 分享链接
            imgUrl: _JSSDK_SHARE_DATA.img, // 分享图标
            type: 'link', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                 var _param = {};
                _param[STATIC_PARAM.SHARE_TYPE] = 1;
                _param[STATIC_PARAM.PAGE_ID] = _JSSDK_SHARE_DATA.page_id;
                Server.Share(_param,function(data){
                })
            },
            cancel: function () { 
            }
        });
        
        //分享到QQ
        wx.onMenuShareQQ({
            title: _JSSDK_SHARE_DATA.send_title, 
            desc: _JSSDK_SHARE_DATA.send_desc,
            link: CURRENT_PAGELINK, 
            imgUrl: _JSSDK_SHARE_DATA.img,
            success: function () {
                var _param = {};
                _param[STATIC_PARAM.SHARE_TYPE] = 3;
                _param[STATIC_PARAM.PAGE_ID] = _JSSDK_SHARE_DATA.page_id;
                Server.Share(_param,function(data){
                })            	
            },
            cancel: function () { 
            }
        });        
        
        //分享到腾讯微博
        wx.onMenuShareWeibo({
            title: _JSSDK_SHARE_DATA.send_title, 
            desc: _JSSDK_SHARE_DATA.send_desc,
            link: CURRENT_PAGELINK, 
            imgUrl: _JSSDK_SHARE_DATA.img,
            success: function () {
                var _param = {};
                _param[STATIC_PARAM.SHARE_TYPE] = 4;
                _param[STATIC_PARAM.PAGE_ID] = _JSSDK_SHARE_DATA.page_id;
                Server.Share(_param,function(data){
                })            	
            },
            cancel: function () { 
            }
        });        


        if(CURRENT_PAGELINK.indexOf("myMoney")>0){
             wx.hideOptionMenu();
        }else if(CURRENT_PAGELINK.indexOf("myGift")>0){
             wx.hideOptionMenu();
        }else if(CURRENT_PAGELINK.indexOf("getRedEnvelope")>0){
             wx.hideOptionMenu();
        }else if(CURRENT_PAGELINK.indexOf("myCoupon")>0){
             wx.hideOptionMenu();
        }else if(CURRENT_PAGELINK.indexOf("lottery")>0){
            wx.hideOptionMenu();
                wx.showMenuItems({
                    menuList: [
                        'menuItem:share:appMessage', // 发送给朋友
                        'menuItem:share:timeline' // 分享到朋友圈
                      ]
                });
        }


        // var _start = CURRENT_PAGELINK.lastIndexOf("/")+1;
        // var _splitUrl = "";
        // if(_start!=0){
        //     _splitUrl = CURRENT_PAGELINK.substr(_start,CURRENT_PAGELINK.length);
        //     if(_splitUrl=="myMoney"||_splitUrl=="myGift"||_splitUrl=="getRedEnvelope"||_splitUrl=="myCoupon"){
        //         //隐藏右上角菜单|
        //         wx.hideOptionMenu();
        //     }else if(_splitUrl=="lottery"){//如果是抽奖转盘页面，隐藏出“分享朋友圈、分享给朋友菜单”
        //         wx.hideOptionMenu();
        //         wx.showMenuItems({
        //             menuList: [
        //                 'menuItem:share:appMessage', // 发送给朋友
        //                 'menuItem:share:timeline' // 分享到朋友圈
        //               ]
        //         });
        //     }
        // }
    });
    /******************************************** E 微信操作 **********************************************/







    /******************************************** S 业务方法 **********************************************/
    //调用网络接口
    var Server = {
        LoginSms:function(param,fn,efn){
            ServerJsonP(STATIC_URL.Sms,param,fn,efn);
        },
        LoginVerify:function(param,fn,efn){
            ServerJsonP(STATIC_URL.Verify,param,fn,efn);
        },
        Share:function(param,fn,efn){
            ServerJsonP(STATIC_URL.Share,param,fn,efn);
        },
        Lottery:function(param,fn,efn){
            ServerJsonP(STATIC_URL.Lottery,param,fn,efn);
        }
    }


    //JsonP请求
    var ServerJsonP = function(url,param,fn,efn){
        //添加sessionId
        var _sessionId = Storage.get(STATIC.CSessionId);
        if(_sessionId!=''&&typeof _sessionId != 'undefined'){
//            console.log(param);
            param[STATIC_PARAM.SESSION_ID] = _sessionId;
        }
//        alert(JsonToStr(param));
        $.ajax({
            async:false,
            url: BASE_URL+url,
            type: "GET",
            dataType: 'json',
            jsonp: 'jsonpCallback',
            data: param,
//            timeout: 5000,

            success: function (data) {
                if(data){
                    if(data[SERVER_JSON.ReturnCode] == SERVER_ERROR_CODE.SUCCESS) {
//                        _json['list'] = data[SERVER_JSON.Data]
                        fn(data[SERVER_JSON.Data]);
                    }else{
                        UIDialog(data[SERVER_JSON.ReturnMessage]);
                    }
                }else{
                    UIDialog(SERVER_ERROR_MSG.NoData);
                }
//                fn(data);
            },
            complete: function(XMLHttpRequest, textStatus){
//                alert(JsonToStr(textStatus));
//                fn();
//                alert(2222);
                //$.unblockUI({ fadeOut: 10 });
            }

        });

    };



    //底部菜单-我的，切换是否显示子菜单(我的礼包，我的礼金)
    function toggleChildMenu(){
        var _flag = $('#'+STATIC.UpPop).attr("flag");
        if(IsEmpty(_flag)){
            $('#'+STATIC.UpPop).show();
            $('#'+STATIC.TipFloat2).show();
            $('#'+STATIC.UpPop).attr("flag","1");
            $(window).scrollTop(0);
        }else{
            $('#'+STATIC.UpPop).removeAttr("flag");
            $('#'+STATIC.UpPop).hide();
            $('#'+STATIC.TipFloat2).hide();
        }
    }



    //显示|隐藏浮层
    var toggleFloat ={
        hideFloat:function(){
            $('#'+STATIC.TipFloat).hide();
            $('#'+STATIC.TipFloat_box).hide();
        },

        showFloat:function(){
            $('#'+STATIC.TipFloat).show();
            $('#'+STATIC.TipFloat_box).show();
        }
     }


     //点击透明的浮层，隐藏“我的”子菜单
     function hideTransparentFloat(){
         $('#'+STATIC.UpPop).removeAttr("flag");
         $('#'+STATIC.UpPop).hide();
         $('#'+STATIC.TipFloat2).hide();
     }


     //点击“下载”下载游戏
     function downLoadGame(){
        var _gameHref =  $('#'+STATIC.DownLoadGame).attr("data-href");
        if(_gameHref=="/wx/jumpBindPhone"){//固定地址
            var _lastHref = "";
            var _currentPageHref = document.location.href;
            _lastHref = _gameHref+"?next_url="+encodeURIComponent(_currentPageHref);
            window.location.href=_lastHref;
        }else{//非固定的地址，判断是否是微信浏览器，若是微信浏览器，则弹出浮层，否则则直接跳转到下载地址
            var ua = window.navigator.userAgent.toLowerCase(); 
            if(ua.match(/MicroMessenger/i) == 'micromessenger'){//微信浏览器
                $('#'+STATIC.TipFloat).show();
                $('#'+STATIC.TipFloat_box).show();
            }else{//非微信浏览器
                window.location.href=_gameHref;
            } 
        }
     }


     //在非微信浏览器的情况下，在游戏详情，礼包详情等页面直接就下载游戏
     function initDownloadGame(){
        if(CURRENT_PAGELINK.indexOf("wx/evn") > 0||CURRENT_PAGELINK.indexOf("wx/giftDetail") > 0 ||CURRENT_PAGELINK.indexOf("wx/gameDetail") > 0)
        {   
            var _evenGameHref =  $('#'+STATIC.DownLoadGame).attr("data-href");
            if(!IsEmpty(_evenGameHref)){
                if(_evenGameHref=="/wx/jumpBindPhone"){//固定地址
               
                }else{//非固定的地址，判断是否是微信浏览器，若是微信浏览器，则弹出浮层，否则则直接跳转到下载地址
                    var ua = window.navigator.userAgent.toLowerCase(); 
                    if(ua.match(/MicroMessenger/i) == 'micromessenger'){//微信浏览器
                        
                    }else{//非微信浏览器
                        window.location.href=_evenGameHref;
                    } 
                }
            }
        }
     }


     var lottery={
            index:-1,   //当前转动到哪个位置，起点位置
            count:0,    //总共有多少个位置
            timer:0,    //setTimeout的ID，用clearTimeout清除
            speed:20,   //初始转动速度
            times:0,    //转动次数
            cycle:50,   //转动基本次数：即至少需要转动多少次再进入抽奖环节
            prize:-1,   //中奖位置
            init:function(id){
                if ($("#"+id).find(".lottery-unit").length>0) {
                    $lottery = $("#"+id);
                    $units = $lottery.find(".lottery-unit");
                    this.obj = $lottery;
                    this.count = $units.length;
                    $lottery.find(".lottery-unit-"+this.index).addClass("active");
                };
            },
            roll:function(){
                var index = this.index;
                var count = this.count;
                var lottery = this.obj;
                $(lottery).find(".lottery-unit-"+index).removeClass("active");
                index += 1;
                if (index>count-1) {
                    index = 0;
                };
                $(lottery).find(".lottery-unit-"+index).addClass("active");
                this.index=index;
                return false;
            },
            stop:function(index){
                //var _selected_result = "恭喜您获得"+LAST_SELECT_AWARD+"，我们将在7个工作日内将话费充入您与“逗逗游戏”公众号绑定的手机号码中。";
                // $('#'+STATIC.Lottery_tc_content2).html(_selected_result);
                // $('#'+STATIC.Lottery_tc_box2).show();
                UIDialog(LAST_SELECT_REMARK,function(){});
            }
        };



        function roll(){
            lottery.times += 1;
            lottery.roll();
            if (lottery.times > lottery.cycle+10 && lottery.prize==lottery.index) {
                clearTimeout(lottery.timer);
                lottery.prize=-1;
                lottery.times=0;
                click=false;
                //转盘停止后，获取抽中的奖品
                lottery.stop(LAST_SELECT_INDEX);
            }else{
                if (lottery.times<lottery.cycle) {
                    lottery.speed -= 10;
                }else if(lottery.times==lottery.cycle) {
                    //var index = 5;
                    lottery.prize = LAST_SELECT_INDEX;
                }else{
                    if (lottery.times > lottery.cycle+10 && ((lottery.prize==0 && lottery.index==7) || lottery.prize==lottery.index+1)) {
                        lottery.speed += 110;
                    }else{
                        lottery.speed += 20;
                    }
                }
                if (lottery.speed<40) {
                    lottery.speed=40;
                };
                lottery.timer = setTimeout(roll,lottery.speed);
            }
            return false;
        }



        //产生随机数
        function PercentageRandom() {
            var  randomNumber;
            randomNumber = Math.random();
            if (randomNumber >= 0 && randomNumber <= rate0)
            {
                return 0;
            }
            else if (randomNumber >= rate0 && randomNumber <= rate0 + rate1)
            {
                return 1;
            }
            else if (randomNumber >= rate0 + rate1
                    && randomNumber <= rate0 + rate1 + rate2)
            {
                return 2;
            }
            else if (randomNumber >= rate0 + rate1 + rate2
                    && randomNumber <= rate0 + rate1 + rate2 + rate3)
            {
                return 3;
            }
            else if (randomNumber >= rate0 + rate1 + rate2 + rate3
                    && randomNumber <= rate0 + rate1 + rate2 + rate3 + rate4)
            {
                return 4;
            }
            else if (randomNumber >= rate0 + rate1 + rate2 + rate3 + rate4
                    && randomNumber <= rate0 + rate1 + rate2 + rate3 + rate4
                    + rate5)
            {
                return 5;
            }
            else if (randomNumber >= rate0 + rate1 + rate2 + rate3 + rate4 + rate5
                    && randomNumber <= rate0 + rate1 + rate2 + rate3 + rate4 + rate5
                    + rate6)
            {
                return 6;
            }
            else if (randomNumber >= rate0 + rate1 + rate2 + rate3 + rate4 + rate5 + rate6
                    && randomNumber <= rate0 + rate1 + rate2 + rate3 + rate4 + rate5 + rate6
                    + rate7)
            {
                return 7;
            }
            return 0;
        }



        //抽奖的初始化方法
        var initLottery = {
            init:function(){
                var _param = {};
                Server.Lottery(_param,function(data){
                    if(data.player_State==1){//1:已抽奖没有转发分享
                        $('#'+STATIC.Lottery_mask).show();
                        $('#'+STATIC.Lottery_tc_box1).show();
                        $('#'+STATIC.Lottery_tc_content).html("转发到朋友圈就能抽奖咯，奖品都到碗里来！");
                    }else if(data.player_State==2){//2：已分享但没抽奖
                        LAST_SELECT_INDEX = data.lottery_idx;
                        LAST_SELECT_AWARD = data.lottery_award;
                        LAST_SELECT_REMARK = data.lottery_remark;
                        
                        $("div.get_m").myScroll({
                            speed:60,
                            rowHeight:20
                        });
                        lottery.init('lottery');
                        if (click) {
                            return false;
                        }else{
                            lottery.speed=100;
                            roll();
                            click=true;
                            return false;
                        }
                    }else if(data.player_State==3){
                         // $('#'+STATIC.Lottery_mask).show();
                         // $('#'+STATIC.Lottery_tc_box1).show();
                         // $('#'+STATIC.Lottery_tc_content).html("一个用户只能抽奖一次哦，您的奖品可在“逗逗游戏”公众号内“我的”－“礼金/礼品”中查看并使用");
                         UIDialog("一个用户只能抽奖一次哦，您的奖品可在“逗逗游戏”公众号内“我的”－“礼金/礼券”中查看并使用",function(){});
                    }else if(data.player_State==4){
                        UIDialog("请先关注“逗逗游戏”公众号，关注后转发本活动页面即可参与抽奖！",function(){});
                    }else if(data.player_State==5){
                        UIDialog("来晚了，奖品都发完了。逗逗后续还会推出更多有奖活动，记得多多关注“逗逗游戏”公众号哦~~",function(){});   
                    }else if(data.player_State==6){
                        UIDialog("本次活动已结束。逗逗后续还会推出更多有奖活动，记得多多关注“逗逗游戏”公众号哦~~",function(){});   
                    }else if(data.player_State==7){
                        UIDialog("您还没有绑定手机，不能参与抽奖哦~~",function(){});   
                    }else if(data.player_State==8){
                        UIDialog("活动还没有开始，不能参与抽奖哦~~",function(){});   
                    }
                },function(data){
                   UIDialog("抽奖失败!",function(){});
                });
            }
        }



      



         /******************************************** E 业务方法 **********************************************/