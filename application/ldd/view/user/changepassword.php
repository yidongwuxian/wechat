<!DOCTYPE html>
<html class='xiugai_html'>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>修改密码</title>
    <link rel="stylesheet" href="/static/ldd/css/libs/mui.min.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/common.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/forgetpassword.css<?php echo '?' . $version; ?>" />
</head>
<body>
<div class="mui-content content_inner xiugai_main">
    <img src="/static/ldd/img/pages/logo.png" alt="" class="xiugai_logo">
    <form id="changepwdform">
        <div class="xiugai_from">
            <div class="mui-input-row xiugai_listo">
                <span class="xiugai_label">手机号</span>
                <input type="number" data-class="mobile" id="mobile" class="xiugaimobile valid">
            </div>
            <div class="inrow_group clearfix xiugai_listo">
                <span class="xiugai_label">验证码</span>
                <div class="inrow mui-col-xs-8 mui-col-md-9 xiugaiyzm">
                    <input type="text" class="valid" data-class="validcode" id="code" >
                </div>
                <div class="inrow  mui-col-xs-4 mui-col-md-3" id="yzm_btn">
                    <a class="mui-btn mui-btn-blue" href="javascript:;" id="getcode">获取验证码</a>
                </div>
            </div>
            <div class="mui-input-row mui-password xiugai_listo">
                <span class="xiugai_label">新密码</span>
                <input type="password" class="mui-input-password valid" data-class="newpassword" id="password" class="xiugaimobile">
            </div>
            <div class="mui-input-row mui-password xiugai_listo">
                <span class="xiugai_label">确认密码</span>
                <input type="password" class="mui-input-password valid" data-class="surepassword" id="password2" class="xiugaimobile">
            </div>
            <div class="content_inner_btn">
                <a class="mui-btn mui-btn-blue" id="changepwd" style="line-height: 24px;">提交</a>
            </div>
        </div>
    </form>
</div>
<script src="/static/base/js/jquery.2.1.1.min.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/libs/mui.min.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/lddvalid.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/pages/changepassword.js<?php echo '?' . $version; ?>"></script>
</body>
</html>
