<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
        <title>房产申请</title>
        <link rel="stylesheet" href="/static/ldd/css/libs/mui.min.css<?php echo '?' . $version; ?>" />
        <link rel="stylesheet" href="/static/ldd/css/pages/common.css<?php echo '?' . $version; ?>" />
        <link rel="stylesheet" href="/static/ldd/css/pages/apply.css<?php echo '?' . $version; ?>" />
    </head>
    <body>
        <div class="mui-content">
            <div class="ame_info_title c_9">请填写申请信息</div>
            <form class="com_form_content ame_info_form mui-input-group">
                <input type="hidden" id="token" value="<?php echo $token; ?>">
                <div class="mui-input-row">
                    <label>申请人</label>
                    <input type="text" id="cust_name" placeholder="请输入申请人姓名" class="valid" data-class="applyName" />
                </div>
                <div class="mui-input-row">
                    <label>身份证号</label>
                    <input type="text"  id="id_card" placeholder="请输入身份证号" maxlength="18" onkeyup="clearIdCard(this)" class="valid" data-class="idcard" />
                </div>
                <div class="mui-input-row">
                    <label>申请额度</label>
                    <input type="number" id="apply_limit" placeholder="请输入申请额度"  oninput="this.value=this.value.replace(/\D/g,'')"/>
                    <span class="com_form_ext">万</span>
                </div>
                <div class="mui-input-row">
                    <label>申请期限</label>
                    <select id="loan_term">
                        <option value="3">3</option>
                        <option value="6">6</option>
                        <option value="12">12</option>
                        <option value="24">24</option>
                        <option value="36">36</option>
                        <option value="60">60</option>
                    </select>
                    <span class="com_form_ext">月</span>
                </div>
                <div class="mui-input-row h20">
                    <label></label>
                    <input type="text" />
                </div>
            </form>
            <div class="ame_info_title c_9">核对房产信息</div>
            <ul class="mui-table-view info_cn">
                <?php
                    //定义贷款区间
                    $min = 0;
                    $max = 0;
                ?>
                <?php if( ! empty($evaluationList)): ?>
                <?php foreach ($evaluationList as $key => $item): ?>
                    <li class="mui-table-view-cell mui-media">
                        <a href="javascript:;">
                            <div class="mui-col-sm-3 pm1"><strong><?php echo $key + 1; ?>.</strong></div>
                            <div class="mui-media-body mui-col-sm-9">
                                <div class="ldd-times">
                                    <?php echo date('Y-m-d H:i:s',$item['evaluation_time']); ?>
                                </div>
                                <div class="ldd-status st-green"></div>
                                <div class="ldd-de"><?php echo $item['address']; ?></div>
                            </div>
                        </a>
                    </li>
                        <?php
                            //计算最大可贷额度，每个房产可贷额度的最大值求和
                            $max +=  $item['limit_price_max'];
                        ?>
                <?php endforeach; ?>
                <?php endif; ?>
			</ul>
            <input type="hidden" id="max" value="<?php echo floor($max / 10000); ?>">
            <div class="ame_btn com_form_btns">
                <a class="mui-btn mui-btn-block mui-btn-save" id="sub">确认提交</a>
            </div>
        </div>
        <script src="/static/base/js/jquery.2.1.1.min.js<?php echo '?' . $version; ?>"></script>
        <script src="/static/ldd/js/libs/mui.min.js<?php echo '?' . $version; ?>"></script>
        <script src="/static/ldd/js/common.js<?php echo '?' . $version; ?>"></script>
        <script src="/static/ldd/js/lddvalid.js<?php echo '?' . $version; ?>"></script>
        <script src="/static/ldd/js/apply.js<?php echo '?' . $version; ?>"></script>
    </body>
</html>
