$(function(){
    var curCount;

    $('#getcode').on('click',function(){
        validMessage();
    });

    function validMessage(){
        var $mobile = $("#mobile").val();
        var mobileReg = /^1[3|4|5|7|8][0-9]\d{8}$/;

        if( $("#mobile").val() == '') {
            mui.alert('手机号码不能为空');
            return false;
        }
        else if(!mobileReg.test($mobile)) {
            mui.alert('请输入有效的11位手机号码');
            return false;
        }
        else{
            sendMessage();
        }
    }

    function sendMessage(){
        curCount=60;
        $('#getcode').prop("disabled",true);
        $('#getcode').html(curCount+"s后重新发送");
        InterValObj = window.setInterval(SetRemainTime, 1000);
        var $mobile = $("#mobile").val();
        $.ajax({
            type:'post',
            url: '/ldd/user/getcode',
            data: {mobile: $mobile},
            dataType:'JSON',
            async:true,
            success:function(data){
                if(data.result == 1){
                    mui.alert(data.message);
                }else{
                    mui.alert(data.message);
                }
            },
            error: function(e){
                console.log(e);
            }
        })
    }

    function SetRemainTime(){
        if (curCount == 0) {
            window.clearInterval(InterValObj);
            $("#getcode").removeAttr("disabled");
            $("#getcode").html("获取验证码");
        }
        else {
            curCount--;
            $("#getcode").html(curCount + "s后重新发送");
        }
    }

    $('#changepwd').click(function(){
        var data = {};
        data['mobile'] = $("#mobile").val();
        data['code']   = $("#code").val();
        data['password'] = $('#password').val();
        data['password2'] = $('#password2').val();

        var valid = $('#changepwdform').valid();
        if(valid.flag){
            $.ajax({
                type:'post',
                url: '/ldd/user/changepassword',
                data: data,
                dataType:'JSON',
                async:true,
                success:function(data){
                    if(data.result == 1){
                        if( data.extension != null){
                            window.location.href = data.extension;
                        }
                    }else{
                        mui.alert(data.message);
                    }
                },
                error: function(e){
                    console.log(e);
                }
            })
        }else{
            mui.alert(valid.msg);
        }
        return;
    });
});
