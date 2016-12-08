<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>订单详情</title>
    <link rel="stylesheet" href="/static/ldd/css/libs/mui.min.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/libs/swiper.min.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/common.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/contractdetail.css<?php echo '?' . $version; ?>" />
</head>

<body class="contractdetail_body">
<div class="mui-content">
    <div class="contractdetail_headtop">
        <div class="contractdetail_tiple">
            <h5><?php echo number_format($detail[0]['principal'],2); ?>元</h5>
            <h6>待还本金</h6>
        </div>
        <div class="contractdetail_tiple">
            <h5><?php echo number_format($detail[0]['interest'],2); ?>元</h5>
            <h6>待还利息</h6>
        </div>

    </div>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php foreach ($detail as $key => $value): ?>
            <div class="swiper-slide">
                <div class="contractdetail_pages">合同期数（<span><?php echo $key + 1; ?> / <?php echo count($detail); ?></span>）</div>
                <h2><span>还款计划：</span><span>到期时间：<?php echo date('Y-m-d',strtotime($value['endDate'])); ?></span></h2>
                <ul class="contractdetail_list">
                    <li><span>合同编号</span><span><?php echo $value['contractNum']; ?></span></li>
                    <li><span>应还款日期</span><span><?php echo date('Y-m-d',strtotime($value['shouldRepaymentDate'])); ?></span></li>
                    <li><span>实还款日期</span><span><?php echo date('Y-m-d',strtotime($value['actualRepaymentDate'])); ?></span></li>
                    <li><span>本期待还本金</span><span><?php echo number_format($value['currentShouldRepaymentPrincipal'],2); ?>元</span></li>
                    <li><span>本期待还利息</span><span><?php echo number_format($value['currentShouldRepaymentInterest'],2); ?>元</span></li>
                    <li><span>逾期利息</span><span><?php echo number_format($value['overdueInterest'],2); ?>元</span></li>
                    <li><span>实还本金</span><span><?php echo number_format($value['repayedPrincipal'],2); ?>元</span></li>
                </ul>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
</div>
<script src="/static/base/js/jquery.2.1.1.min.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/libs/mui.min.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/libs/swiper.min.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/pages/contractdetail.js"></script>

</body>

</html>