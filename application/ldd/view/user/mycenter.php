<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>我的账户</title>
    <link rel="stylesheet" href="/static/ldd/css/libs/mui.min.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/common.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/index.css<?php echo '?' . $version; ?>" />
</head>

<body>
    <div class="mui-content">
        <div class="index_header">
            <img src="/static/ldd/img/pages/mebg.jpg" alt="">
            <span class="name">林鹏</span>
            <img src="/static/ldd/img/pages/title.png" alt="" class="head">
            <p class="title title1">东方邦信融通股份有限公司北京小贷</p>
        </div>
        <div id="index_list">
            <ul class="mui-table-view mui-grid-view mui-grid-9">
                <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">
                    <a href="/ldd/loan/evaluationlist">
                        <span class="listitem"><img src="/static/ldd/img/pages/fangchanlis.png" alt=""></span>
                        <div class="mui-media-body">房产列表</div>
                    </a>
                </li>
                <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">
                    <a href="/ldd/loan/index">
                        <span class="listitem"><img src="/static/ldd/img/pages/yewu.png" alt=""></span>
                        <div class="mui-media-body">我的订单</div>
                    </a>
                </li>
                <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">
                    <a href="/ldd/about/introduce">
                        <span class="listitem"><img src="/static/ldd/img/pages/jieshao.png" alt=""></span>
                        <div class="mui-media-body">产品介绍</div>
                    </a>
                </li>
                <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">
                    <a href="/ldd/about/help">
                        <span class="listitem"><img src="/static/ldd/img/pages/bangzhu.png" alt=""></span>
                        <div class="mui-media-body">帮助中心</div>
                    </a>
                </li>
                <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">
                    <a href="/ldd/about/instruction">
                        <span class="listitem"><img src="/static/ldd/img/pages/shiyong.png" alt=""></span>
                        <div class="mui-media-body">使用说明</div>
                    </a>
                </li>
                <?php
                    //只有在白名单中的机构可以查看积分明细
                    $province = \think\Session::get('loginInfo')['provinceCode'];
                    $whiteList = \think\Config::get('white_list');
                    if(in_array($province,$whiteList)):
                ?>
                <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">
                    <a href="/ldd/user/mypoint">
                        <span class="listitem"><img src="/static/ldd/img/pages/jifen.png" alt=""></span>
                        <div class="mui-media-body">积分明细</div>
                    </a>
                </li>
                <?php endif; ?>
                <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">
                    <a href="/ldd/user/changepassword">
                        <span class="listitem"><img src="/static/ldd/img/pages/mima.png" alt=""></span>
                        <div class="mui-media-body">修改密码</div>
                    </a>
                </li>
                <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">
                    <a href="/ldd/agent/agentlist">
                        <span class="listitem"><img src="/static/ldd/img/pages/weihui.png" alt=""></span>
                        <div class="mui-media-body">成员维护</div>
                    </a>
                </li>
                <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">
                    <a href="/ldd/user/logout">
                        <span class="listitem"><img src="/static/ldd/img/pages/tuichu.png" alt=""></span>
                        <div class="mui-media-body">退出</div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <script src="/static/base/js/jquery.2.1.1.min.js<?php echo '?' . $version; ?>"></script>
    <script src="/static/ldd/js/libs/mui.min.js<?php echo '?' . $version; ?>"></script>
    <script src="/static/ldd/js/common.js<?php echo '?' . $version; ?>"></script>
</body>

</html>