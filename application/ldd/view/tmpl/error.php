<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>提示信息</title>
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="/static/old/css/mui.min.css<?php echo '?' . $version; ?>">
    <link rel="stylesheet" href="/static/ldd/css/tip.css<?php echo '?' . $version; ?>">
</head>
<body>
<div class="mui-content">
    <div class="icon-box">
        <i class="icon-warn icon_msg"></i>
        <div class="icon-box__ctn">
            <h3 class="icon-box__title"><?php
                    if($msg != '')
                        echo $msg;
                    else
                        echo '未知错误！';
                ?></h3>
            <p class="icon-box__desc"><?php
                    if( ! empty($data['description']))
                        echo $data['description'];
                ?></p>
        </div>
        <div class="com_form_btns">
            <a class="mui-btn mui-btn-block mui-btn-success" id="sub"><?php
                    if( ! empty($data['button_name']))
                        echo $data['button_name'];
                    else
                        echo '确定';
                ?></a>
        </div>
    </div>
</div>
</body>
<script src="/static/old/js/mui.min.js<?php echo '?' . $version; ?>"></script>
<script>
    (function(){
        mui('.com_form_btns').on('tap','#sub',function(){
            var tourl = '<?php echo $url ? $url : ""; ?>';
            if(tourl != ''){
                window.location.href = tourl;
            }else {
                //关闭微信窗口（只是微信窗口）
                WeixinJSBridge.call('closeWindow');
            }
        });
    })()
</script>
</html>
