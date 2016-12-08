<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>业务人员列表</title>
    <link rel="stylesheet" href="/static/ldd/css/libs/mui.min.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/common.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/edit.css<?php echo '?' . $version; ?>" />

</head>

<body class="edit_body">
    <div class="mui-content">
        <ul>
            <li><span><i></i>姓名</span>
                <input type="text" disabled="disabled" placeholder="请输入姓名" value="<?php echo $agentInfo['businessPeopleName']; ?>" id="namse">
                <input type="hidden" value="<?php echo $agentInfo['id']; ?>" id="data_id">
            </li>
            <li><span><i></i>手机号</span>
                <input type="text" disabled="disabled" placeholder="请输入手机号" value="<?php echo $agentInfo['tel']; ?>" id="shoujihao">
            </li>

            <li><span><i></i>业务员编号</span>
                <p>
                    <?php echo $agentInfo['businessPeopleNum']; ?>
                </p>
            </li>
            <li><span><i></i>更新日期</span>
                <p>
                    <?php echo date('Y-m-d H:i:s',$agentInfo['sysUpdateTime'] / 1000); ?>
                </p>
            </li>
        </ul>
        <button class="edit_button">密码重置</button>
    </div>
    <script src="/static/base/js/jquery.2.1.1.min.js<?php echo '?' . $version; ?>"></script>
    <script src="/static/ldd/js/libs/mui.min.js<?php echo '?' . $version; ?>"></script>
    <script src="/static/ldd/js/common.js<?php echo '?' . $version; ?>"></script>
    <script src="/static/ldd/js/pages/edit.js<?php echo '?' . $version; ?>"></script>

</body>

</html>