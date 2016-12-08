<!DOCTYPE html>
<html id="loan_index_html">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>我的订单</title>
    <link rel="stylesheet" href="/static/ldd/css/libs/mui.min.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/common.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/loan_index.css<?php echo '?' . $version; ?>" />
    <style>

    </style>
</head>

<body id="content" class="loan_index_content">
    <div class="mui-content" id="loan_indexmains">
        <div class="com_tab">
            <div id="loan_segmentedControl" class="mui-segmented-control">
                <a id="aa" class="mui-control-item mui-active ids" href="#tab_left">个人订单</a>
                <a id="bb" class="mui-control-item ids" href="#tab_right">机构订单</a>
            </div>
        </div>
        <div id="tab_left" class="mui-control-content mui-active">
            <div class="loan_sershinput">
                <div class="loan_serchmain">
                    <div class="loan_searchbtn"><span class="mui-icon mui-icon-search"></span></div>
                    <input class="loan_searchinput" id="geren" placeholder="请输入搜索关键字">
                    </iinput>
                </div>
            </div>
            <ul class="mui-table-view mui-table-view-chevron user_links aa loan_listitem">
                <!--<li>
                    <p><span class="delte"></span>Y109011608170004 <span class="time">2016-02-08 08:12:20</span></p>
                    <p class="names"><span class="name">张三</span><span class="mani">50万</span></p>
                    <p class="btns">
                        <span class="yello">评估中</span>
                        <a href="#" class="chakan">查看</a>
                        <a href="javascript" class="hetong">合同</a>
                    </p>
                </li>-->

            </ul>
        </div>
        <div id="tab_right" class="mui-control-content">
            <div class="loan_sershinput">
                <div class="loan_serchmain">
                    <div class="loan_searchbtn"><span class="mui-icon mui-icon-search"></span></div>
                    <input class="loan_searchinput" id="jigou" placeholder="请输入搜索关键字">
                    </iinput>
                </div>
            </div>
            <ul class="mui-table-view mui-table-view-chevron user_links bb loan_listitem">
                <!-- <li>
                    <p><span class="delte"></span>Y109011608170004 <span class="time">2016-02-08 08:12:20</span></p>
                    <p class="names"><span class="name">李四</span><span class="mani">50万</span></p>
                    <p class="btns">
                        <span class="yello">评估中</span>
                        <a href="#" class="chakan">查看</a>
                        <a href="#" class="hetong">合同</a>
                    </p>
                </li>
                -->
            </ul>
        </div>
    </div>
</body>
<script src="/static/base/js/jquery.2.1.1.min.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/libs/mui.min.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/pages/loan_index.js<?php echo '?' . $version; ?>"></script>

</body>

</html>