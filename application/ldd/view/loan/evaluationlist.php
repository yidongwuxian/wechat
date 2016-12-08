<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>房产列表</title>
    <link rel="stylesheet" href="/static/ldd/css/libs/mui.min.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/common.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/apply.css<?php echo '?' . $version; ?>" />
</head>
<body>
<div class="mui-content" style="position:relative">
    <div class="com_list_tt" id="eval-hd"><a href="/ldd/loan/evaluation"><s class="eval_addIco"></s>添加房产</a></div>
    <div id="infoList" class="infolist"></div>
    <div class="ame_btn com_form_btns evallist_box">
        <a class="mui-btn mui-btn-block mui-btn-save" id="evallistBtn">提交</a>
    </div>
    <input type="hidden" id="sId" />
</div>
<script src="/static/base/js/jquery.2.1.1.min.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/libs/mui.min.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/utils/formatdate.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/common.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/lddvalid.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/evaluationlist.js<?php echo '?' . $version; ?>"></script>
</body>
</html>
