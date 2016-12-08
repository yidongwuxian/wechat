mui('.content_inner_btn').on('tap', '#changepwd',function () {
    var data = {};
    data.mobile = $("#mobile").val();
    data.code = $("#code").val();
    data.password = $('#password').val();
    data.password2 = $('#password2').val();
    var from = $("#changepwdform").val();
    data.from = from;

    var mobileReg = /^1[3|4|5|7|8][0-9]\d{8}$/;
    if(!mobileReg.test(data.mobile)){
        mui.alert('手机号格式不正确');
        return false;
    }
    if(data.code === '' || data.code.length!=6){
        mui.alert('请输入正确的验证码');
        return false;
    }
    if(data.password === ''){
        mui.alert('密码不为空');
        return false;
    }
    if(data.password2 === '' || data.password != data.password2){
        mui.alert('两次密码不一致');
        return false;
    }


    showLoading();
    mui.ajax("/ldd/user/changepassword", {
        type: "POST",
        data: data,
        dataType: "json",
        success: function (res) { //res:{1:成功}
            if (res.result == 1) {
                //登录成功后如果有来源地址跳转，没有则关闭窗口，同时判断是否为初始化密码
                var tourl = res.extension == "" ? "" : res.extension;
                if (tourl != '') {
                    window.location.href = tourl;
                } else {
                    //关闭微信窗口（只是微信窗口）.
                    WeixinJSBridge.call('closeWindow');
                }
                // mui.alert(res.message, function () {
                //
                // });
            } else {
                hideLoading();
                mui.alert(res.message);
            }
        },
        error: function () {
            hideLoading();
        }
    });

});