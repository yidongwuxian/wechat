<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>房产评估</title>
    <link rel="stylesheet" href="/static/ldd/css/libs/mui.min.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/common.css<?php echo '?' . $version; ?>" />
    <link rel="stylesheet" href="/static/ldd/css/pages/evaluation.css<?php echo '?' . $version; ?>" />
</head>
<body>
    <div class="mui-content">
        <div class="od_nav clearfix">
            <div class="mui-segmented-control">
                <a href="./evaluation" class="select1">
                    <i></i>
                        <span>
                            住宅
                        </span>
                </a>
                <a href="javascript:;" class="select2 mui-active">
                    <i></i>
                        <span>
                            商铺
                        </span>
                </a>
            </div>
        </div>
        <div id="ld-content" class="ld_content com_form_content mui-input-group">
            <div id="default_wrap" class="default_wrap">
                <div class="mui-input-row">
                    <label>省份</label>
                     <select class="valid" ld-type="select" ld-index="1">
                        <option value="-1">请选择所在省份</option>
                    </select>
                    <span class="com_form_ldddown"></span>
                </div>
                <div class="mui-input-row">
                    <label>城市</label>
                     <select class="valid" ld-type="select" ld-index="2" ld-params="province:1,provinceName:1.t">
                        <option value="-1">请选择所在城市</option>
                    </select>
                    <span class="com_form_ldddown"></span>
                </div>
                <div class="mui-input-row">
                    <label>区县</label>
                     <select class="valid" ld-type="select" ld-index="3" ld-params="cityCode:1" ld-end>
                        <option value="-1">请选择所在区县</option>
                    </select>
                    <span class="com_form_ldddown"></span>
                </div>
            </div>
            <div id="ext_wrap">
                <div class="mui-input-row">
                    <label>房产</label>
                    <input id="ext_txt_village" type="text" class="valid mui-input-clear" />
                </div>
                <div class="mui-input-row">
                    <label>楼栋单元</label>
                    <input id="ext_txt_unit" type="text" class="valid mui-input-clear" />
                </div>
                <div class="mui-input-row">
                    <label>房间号</label>
                    <input id="ext_txt_roomno" type="text" class="valid mui-input-clear" />
                    <span class="com_form_ext">室</span>
                </div>
                <div class="mui-input-row">
                    <label>朝向</label>
                    <select id="ext_sel_ori" class="valid">
                        <option value="-1">请选择楼层朝向</option>
                    </select>
                    <span class="com_form_ldddown"></span>
                </div>
                <div class="mui-input-row">
                    <label>总楼层</label>
                    <input id="ext_txt_totalfloor" type="text" class="valid mui-input-clear" />
                    <span class="com_form_ext">层</span>
                </div>
                <div class="mui-input-row">
                    <label>所在楼层</label>
                    <input id="ext_txt_localfloor" type="text" class="valid mui-input-clear" />
                    <span class="com_form_ext">层</span>
                </div>
                <div class="mui-input-row">
                    <label>面积</label>
                    <input id="ext_txt_roomarea" type="text" class="valid mui-input-clear txt_roomarea" />
                    <span class="com_form_ext">(平方米)</span>
                </div>
                 <div class="mui-input-row">
                    <label>是否临街</label>
                    <select id="ext_sel_frontage" class="valid">
                        <option value="1">是</option>
                        <option value="2">否</option>
                    </select>
                    <span class="com_form_ldddown"></span>
                </div>
            </div>
        </div>
        <div class="mui-content-padded com_tips_yellow">
            <h6>提示：以上选项均为必填项目，务必核对清楚保证无误</h6>
        </div>
        <div class="com_form_btns ld_submit">
            <a id="submit_btn" class="mui-btn mui-btn-block mui-btn-save">确认提交</a>
        </div>
    </div>
    <div id="resultMask"></div>
    <script src="/static/base/js/jquery.2.1.1.min.js<?php echo '?' . $version; ?>"></script>
    <script src="/static/ldd/js/libs/mui.min.js<?php echo '?' . $version; ?>"></script>
    <script src="/static/ldd/js/pages/evaluation_ld.js<?php echo '?' . $version; ?>"></script>
    <script src="/static/ldd/js/pages/mevaluation_excute.js<?php echo '?' . $version; ?>"></script>
</body>
</html>
