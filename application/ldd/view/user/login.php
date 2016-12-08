<!DOCTYPE html>
<html class="login_html">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>登录</title>
    <link href="/static/ldd/css/libs/mui.min.css<?php echo '?' . $version; ?>" rel="stylesheet" />
    <link href="/static/ldd/css/pages/common.css<?php echo '?' . $version; ?>" rel="stylesheet" />
    <link href="/static/ldd/css/login.css<?php echo '?' . $version; ?>" rel="stylesheet" />
</head>

<body class="login_body">
    <div class="mui-content login_warp">
        <div class="login_logo">
            <img src="/static/ldd/img/pages/logo.png" />
        </div>
        <div class="login_con mui-input-group login_input">
            <div class="mui-input-row">
                <label class="login_label"><img src="/static/ldd/img/old/ico-phone.png" /><span>账号</span></label>
                <input type="tel" class="an-left50" placeholder="用户名/手机号" id="mobile" oninput="this.value=this.value.replace(/\D/g,'')">
            </div>
            <div class="mui-input-row">
                <label class="login_label"><img src="/static/ldd/img/old/ico-lock.png" /><span>密码</span></label>
                <input type="password" class="an-left50 mui-input-password" placeholder="请填写6-20位密码" id="password">
            </div>
        </div>
        <div class="mui-content-padded">
            <div class="login_btns">
                <button class="mui-btn mui-btn-block mui-btn-primary" id="sub">登录</button>
            </div>

        </div>
        <input id="from" value="<?php echo $from; ?>" type="hidden">
    </div>
    <script src="/static/base/js/jquery.2.1.1.min.js<?php echo '?' . $version; ?>"></script>
    <script src="/static/ldd/js/libs/mui.min.js<?php echo '?' . $version; ?>"></script>
    <script src="/static/ldd/js/common.js<?php echo '?' . $version; ?>"></script>
    <script src="/static/ldd/js/login.js<?php echo '?' . $version; ?>"></script>
</body>

</html>
