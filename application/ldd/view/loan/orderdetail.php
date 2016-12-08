<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>订单详情</title>
    <link rel="stylesheet" href="/static/ldd/css/libs/mui.min.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/common.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/order_detail.css<?php echo '?' . $version; ?>" />
</head>

<body class="or_derail_body">
    <div class="mui-content">
        <div class="or_derail_jindutiao">
            <div class="or_derail_jindu">
                <div class="or_derail_item ">
                    <span class="type_img"><img src="/static/ldd/img/pages/feiji.png" alt=""></span>
                    <span class="type_text">业务申请</span>
                </div>
                <div class="or_derail_item ">
                    <span class="type_img"><img src="/static/ldd/img/pages/feiji.png" alt=""></span>
                    <span class="type_text">尽职调查</span>
                </div>
                <div class="or_derail_item">
                    <span class="type_img"><img src="/static/ldd/img/pages/feiji.png" alt=""></span>
                    <span class="type_text">等待审核</span>
                </div>
                <div class="or_derail_item">
                    <span class="type_img"><img src="/static/ldd/img/pages/feiji.png" alt=""></span>
                    <span class="type_text">业务结束</span>
                </div>
            </div>
        </div>
        <ul class="or_derail_list">
            <!--  <li class="or_derail_item">
                <span class="or_der_list"></span>
                <span class="yewuname">业务编号</span>
                <span class="yewuxinxi">Y109011608170004</span>
            </li>-->
        </ul>

        <div id="or_derail_btn">

        </div>
    </div>
    <script src="/static/base/js/jquery.2.1.1.min.js<?php echo '?' . $version; ?>"></script>
    <script src="/static/ldd/js/libs/mui.min.js<?php echo '?' . $version; ?>"></script>
    <script src="/static/ldd/js/pages/orderdetail.js"></script>
</body>

</html>