<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
        <title>房产信息</title>
        <link rel="stylesheet" href="/static/ldd/css/libs/mui.min.css<?php echo '?' . $version; ?>" />
        <link rel="stylesheet" href="/static/ldd/css/pages/common.css<?php echo '?' . $version; ?>" />
        <link rel="stylesheet" href="/static/ldd/css/plugins/mui.picker.min.css<?php echo '?' . $version; ?>" />
        <link rel="stylesheet" href="/static/ldd/css/pages/apply.css<?php echo '?' . $version; ?>" />
    </head>
    <body>
        <div class="mui-content">
            <form class="com_form_content ame_info_form mui-input-group">
                <span class="addrClass"><span id="d_addr"></span>室</span>

                <div class="info_title">
        	    	<div class="line"></div>
                    <span class="tips lddFont">房产信息详情</span>
                </div>
                <div class="mui-input-row">
                    <label>朝向</label>
                    <select id="orientation" class="orientation">
                       <option value="0">请选择楼层朝向</option>
                   </select>
                    <span class="com_form_ldddown"></span>
                </div>
                <div class="mui-input-row">
                    <label>建筑面积</label>
                    <input type="text" id="area" class="valid mui-input-clear" placeholder="请输入建筑面积" data-class="buildArea" onkeyup="clearNoNum(this)" value="" disabled="disabled" />
                    <span class="com_form_ext">(平方米)</span>
                </div>
                    <div class="mui-input-row">
                        <label>总楼层</label>
                        <input type="number" id="floorToal" class="valid mui-input-clear" placeholder="请输入楼栋总层数" data-class="floorToal" oninput="this.value=this.value.replace(/\D/g,'')" value=""  disabled="disabled"/>
                        <span class="com_form_ext">层</span>
                    </div>
                    <div class="mui-input-row">
                        <label>所在楼层</label>
                        <input type="number" id="floor" class="valid mui-input-clear"  placeholder="请输入所在楼层" data-class="infloor" oninput="this.value=this.value.replace(/\D/g,'')" value=""  disabled="disabled"/>
                        <span class="com_form_ext">层</span>
                    </div>
                    <div class="mui-input-row">
                        <label>有无电梯</label>
                        <select id="lift" class="valid" data-class="lift">
                            <option value="0">请选择楼层是否有电梯</option>
                            <option value="1" >有</option>
                            <option value="2" >无</option>
                        </select>
                        <span class="com_form_ldddown"></span>
                    </div>
                    <div class="mui-input-row">
                        <label>竣工年代</label>
                        <input type="text" id="completionTime" class="valid" data-class="years" maxlength="4" value="" />
                        <button id='year' name="year" data-options='{"type":"year"}' class="fArea" ></button>
                        <span class="com_form_ext">年</span>
                    </div>
                    <div class="mui-input-row">
                        <label>抵押类型</label>
                        <select id="sel_mortgage" class="valid" data-class="mortgage">
                            <option value="0">请选择抵押类型</option>
                            <option value="1">一押</option>
                            <option value="2">二押</option>
                        </select>
                        <span class="com_form_ldddown"></span>
                    </div>
                    <div class="mui-input-row div_erya" id="div_yiya">
                        <label>一押剩余本金</label>
                        <input type="number" id="one_residual" oninput="this.value=this.value.replace(/\D/g,'')"/>
                        <span class="com_form_ext">万</span>
                    </div>
                    <div class="mui-input-row div_erya">
                        <label>房产交易是否满五年</label>
                        <select id="is_five" class="sel_yiya">
                            <option value="0">请选择</option>
                            <option value="1">是</option>
                            <option value="2">否</option>
                        </select>
                        <span class="com_form_ldddown"></span>
                    </div>
                    <div class="mui-input-row div_erya">
                        <label>周边是否有麦当劳或肯德基</label>
                        <select id="haveKFC" class="sel_yiya">
                            <option value="0">请选择</option>
                            <option value="1">是</option>
                            <option value="2">否</option>
                        </select>
                        <span class="com_form_ldddown"></span>
                    </div>
                    <div class="mui-input-row">
                        <label>房产类型</label>
                        <select id="sel_house_type" class="valid sel_yiya" data-class="sel_house_type">
                            <option value="0">请选择房产类型</option>
                        </select>
                        <span class="com_form_ldddown"></span>
                    </div>
                    <div class="mui-content-padded com_tips_yellow">
                        <h6>提示：以上选项均为必填项目，务必核对清楚保证无误</h6>
                    </div>
                    <input type="hidden" id="city" value="">
                    <input type="hidden" id="id" value="">
                    <input type="hidden" id="forward" value="">
                    <div class="ame_btn com_form_btns">
                        <a class="mui-btn mui-btn-block mui-btn-save" id="detailSave">保存</a>
                    </div>
            </form>
        </div>
        <script src="/static/base/js/jquery.2.1.1.min.js<?php echo '?' . $version; ?>"></script>
        <script src="/static/ldd/js/libs/mui.min.js<?php echo '?' . $version; ?>"></script>
        <script src="/static/ldd/js/plugins/mui.picker.all.js<?php echo '?' . $version; ?>"></script>
        <script src="/static/ldd/js/common.js<?php echo '?' . $version; ?>"></script>
        <script src="/static/ldd/js/lddvalid.js<?php echo '?' . $version; ?>"></script>
        <script src="/static/ldd/js/detail.js<?php echo '?' . $version; ?>"></script>
    </body>
</html>
