<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>业务人员列表</title>
    <link rel="stylesheet" href="/static/ldd/css/libs/mui.min.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/common.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/add.css<?php echo '?' . $version; ?>" />
</head>

<body class="addlist_body">
    <div class="mui-content">
        <div class="addlist_top">
            <div class="add_serchinput">
                <span class="mui-icon mui-icon-search "></span>
                <input type="text" placeholder="请输入搜索关键字" id="agentlist_btn">
            </div>
        </div>
        <div class="add_list">
            <div class="add_head">
                <p>
                    <span>编号</span>
                    <span>姓名</span>
                    <span>电话</span>
                    <span>编辑</span>
                </p>
            </div>
            <ul class="yewu_list">

            </ul>
        </div>
    </div>
    <script src="/static/base/js/jquery.2.1.1.min.js<?php echo '?' . $version; ?>"></script>
    <script src="/static/ldd/js/libs/mui.min.js<?php echo '?' . $version; ?>"></script>
    <script src="/static/ldd/js/common.js<?php echo '?' . $version; ?>"></script>
    <script src="/static/ldd/js/pages/add.js<?php echo '?' . $version; ?>"></script>
</body>

</html>