
    //每个页面单独静态变量
	var STATIC_SUB = {
        //登陆/注册页
        JLoginPhone:"JLoginPhone",
        JLoginSMS:"JLoginSMS",
        JLoginSend:"JLoginSend",
        JLoginBtn:"JLoginBtn"
    };
	

    //错误信息
    var ERROR_MSG = {
        PHONE_ERROR:"请输入正确的手机号码",
        SMS_EMPTY:"验证码不能为空",
        SMS_ERROR:"验证码不正确"
    };





    $(function(){

        window.canGetSMS = Countdown.isCountdown();
        if(!window.canGetSMS){
            Countdown.doCountDown(Countdown.getTime());
        }


        //绑定手机第一步，发送验证码至手机
        $('#'+STATIC.JSend).on(getTapName(),function(){
            checkSend();
        });


        //绑定手机第二步，提交验证码并登录
        $('#'+STATIC_SUB.JLoginBtn).on(getTapName(),function(){
            checkLogin();
        });



    });        




	


	


    //验证发送验证码
    var checkSend = function(){
        if(!window.canGetSMS){
            return;
        }
        var _phone = checkPhone();
        if(!_phone){
            return;
        }

      
        var _param = {};
        _param[STATIC_PARAM.SMS_COUNTRYCODE] = STATIC.PhoneCode;
        _param[STATIC_PARAM.SMS_PHONE] = _phone;
        
        _param[STATIC_PARAM.NEWPWD] =$('#JLoginPwd').val();

        Server.LoginSms(_param,function(data){
            var _time = new Date().getTime();
            Storage.set(STATIC.CSmsTime,_time);
            Countdown.doCountDown(Countdown.maxTime);
        });

    }


     //判断手机
    var checkPhone = function(){
        var _phoneDom = $('#'+STATIC_SUB.JLoginPhone);
        var _phone = _phoneDom.val();
        //判断手机号码是否为空
        if(_phone == ''){
            UIDialog(ERROR_MSG.PHONE_ERROR,function(){_phoneDom.focus();});
            return false;
        }

        //判断手机号码格式
        if(!new RegExp(regexEnum.mobile).test(_phone)){
            UIDialog(ERROR_MSG.PHONE_ERROR,function(){_phoneDom.focus();});
            return false;
        }

        return _phone;
    }



   



     //验证注册/登录
    var checkLogin = function(){
        var _phone = checkPhone();
        if(!_phone){
            return;
        }

        var _sms = checkSMS();
        if(!_sms){
            return;
        }
       
        var _param = {};
        _param[STATIC_PARAM.VERIFY_COUNTRYCODE] = STATIC.PhoneCode;
        _param[STATIC_PARAM.VERIFY_PHONE] = _phone;
        _param[STATIC_PARAM.VERIFY_SMSCODE] = _sms;
        _param[STATIC_PARAM.NEWPWD] =$('#JLoginPwd').val();
        

        Server.LoginVerify(_param,function(data){
            // Storage.set(STATIC.CAlipay,JsonToStr(data[SERVER_JSON_SUB.JAlipay]));
            // Storage.set(STATIC.CLogininfo,JsonToStr(data[SERVER_JSON_SUB.JLogininfo]));
            // var _userInfo = data[SERVER_JSON_SUB.JUser];
            // Storage.set(STATIC.CUser,JsonToStr(_userInfo));
            // if(_userInfo!=null){
            //     //登录和获取用户信息唯一区别,是登录时有sessionID
            //     Storage.set(STATIC.CSessionId,_userInfo[SERVER_JSON_SUB.JSessionId]);
            //     Storage.set(STATIC.CUnreadCount,_userInfo[SERVER_JSON_SUB.JUnreadCount]);
            // }
            // //登陆成功后返回之前页面
            // ActionCtrl.loginAction();
            //登录成功后返回之前页面
            var _previousPageUrl = data.next_url;
            window.location.href=_previousPageUrl;
        },function(data){
            if(data[SERVER_JSON.ReturnCode] == SERVER_ERROR_CODE.VERIFYCODE_ERROR){
                $('#'+STATIC_SUB.JLoginSMS).val('');
                $('#'+STATIC_SUB.JLoginSMS).focus();
            }
        });
    }



     //判断手机验证码
    var checkSMS = function(){
        var _smsDom = $('#'+STATIC_SUB.JLoginSMS);
        var _sms = _smsDom.val();
        //判断手机号码是否为空
        if(_sms == ''){
             UIDialog(ERROR_MSG.SMS_EMPTY,function(){_smsDom.focus();});
            return false;
        }


        return _sms;
    }


    




    

