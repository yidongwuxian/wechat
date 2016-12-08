<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>预约尽调</title>
    <link rel="stylesheet" href="/static/ldd/css/libs/mui.min.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/plugins/mui.picker.min.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/common.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/appointime.css<?php echo '?' . $version; ?>" />
</head>
<body>
    <header class="mui-bar mui-bar-nav">
        <h1 class="mui-title">房产评估</h1>
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
        <a class="ldd-icon ldd-icon-bars mui-pull-right"></a>
    </header>
    <div class="mui-content">
            <form class="com_form_content ame_jin_form mui-input-group">
                <div class="jin_black"></div>
                <div class="mui-input-row">
                    <div class="mui-col-sm-6 mui-col-xs-12">
                        <label>业务编号</label>
                    </div>
                    <div class="mui-col-sm-6 mui-col-xs-12">
                        <input type="text" id="bsNumber" class="bs_number" value="<?php echo $data['projectNo']; ?>"  readonly="readonly" />
                    </div>
                </div>
                <div class="mui-input-row">
                    <div class="mui-col-sm-6 mui-col-xs-12">
                        <label>客户名称</label>
                    </div>
                    <div class="mui-col-sm-6 mui-col-xs-12">
                        <input type="text"  id="id_card" class="user_name" value="<?php echo $data['custName']; ?>" readonly="readonly" />
                    </div>
                </div>
                <div class="mui-content-padded">
                    <div class="mui-col-sm-12 mui-col-xs-12">
                        <label>房产信息</label>
                    </div>
                    <ul class="jinidao_cn">
                        <?php foreach ($data['address'] as $key => $value): ?>
        				<li class="mui-media">
        					<a href="javascript:;">
                                <span><?php echo $key + 1; ?>、</span>
        						<p><?php echo $value; ?></p>
        					</a>
        				</li>
                        <?php endforeach; ?>
        			</ul>
                </div>
                <div class="mui-input-row">
                    <div class="mui-col-sm-6 mui-col-xs-12">
                        <label>预约尽调时间</label>
                    </div>
                    <div class="mui-col-sm-6 mui-col-xs-12 mui-navigate-right">
                        <button id='jinTimes' name="jinTimes" data-options='{"type":"datetime"}' class="jin_dtp" value="<?php echo $data['appointTime']; ?>"><?php echo $data['appointTime']; ?></button>
                    </div>
                </div>
                <div class="mui-content-padded">
                    <label>备注</label>
                    <div class="jin_remark_box">
    					<textarea id="remarks"  placeholder="50字以内" rows="5"><?php echo $data['remark']; ?></textarea>
    				</div>
                </div>
                <div class="ame_btn com_form_btns">
                    <a class="mui-btn mui-btn-block mui-btn-save" id="apponitSub">提交</a>
                </div>
        </form>
    </div>
<script src="/static/base/js/jquery.2.1.1.min.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/libs/mui.min.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/plugins/mui.picker.all.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/common.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/lddvalid.js<?php echo '?' . $version; ?>"></script>
<script src="/static/ldd/js/appointtime.js<?php echo '?' . $version; ?>"></script>
</body>
</html>
