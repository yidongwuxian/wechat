<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>房产评估</title>
    <link rel="stylesheet" href="/static/ldd/css/libs/mui.min.css" />
    <link rel="stylesheet" href="/static/ldd/css/common.css" />
    <link rel="stylesheet" href="/static/ldd/css/assessment.css" />
    <link rel="stylesheet" href="/static/ldd/css/nprogress.css" />
    <link rel="stylesheet" href="/static/ldd/css/order.css" />
    <link rel="stylesheet" href="/static/ldd/css/evaluation.css" />
</head>
<body>
	<div class="mui-content">
        <div class="od_nav clearfix">
            <div class="mui-segmented-control">
                <a href="#home" class="select1 mui-control-item mui-active">
                    <i></i>
    	       			<span>
    	       				住宅
    	       			</span>
                </a>
                <a href="#shop" class="select2 mui-control-item">
                    <i></i>
    	       			<span>
    	       				商铺
    	       			</span>
                </a>
            </div>
        </div>
        <div class="whiteblock"></div>


        <div id="home" class="mui-control-content mui-active">
            <!-- <p class="com_info_title">房屋估价</p> -->
        	<div class="com_form_content ame_info_form mui-input-group com_form_auto">
                <div class="com_form_inrow">
                    <div class="mui-input-row areainfo">
                        <label>省份</label>
                        <select id="pg_province" data-key="province" data-type="select" class="select">
                            <option value="-1">请选择</option>
                            <option value=""></option>
                        </select>
                        <span class="com_form_arrdown"></span>
                        <div class="ame_row_mask"></div>
                    </div>
                    <div class="mui-input-row show">
                        <label>城市</label>
                        <select id="pg_city" data-key="city" data-type="select" class="select"></select>
                        <span class="com_form_arrdown"></span>
                        <div class="ame_row_mask"></div>
                    </div>
                </div>
    			<div class="mui-input-row show nopd">
                    <label>房产</label><!--<input id="choose_xq" name="radio1" type="radio" checked="">-->
    				<input type="text" id="pg_village" data-key="village" data-type="auto" onkeyup="value=value.replace(/[^u4E00-u9FA5][^[A-Za-z]+$]/g,'')"  />
                    <input type="hidden" id="pg_village_val" />
                    <div class="mui-switch mui-switch-blue">
                        <div class="mui-switch-handle">小区</div>
                    </div>
    				<div class="ame_row_mask"></div>
                    <div id="result">
                        <ul></ul>
                    </div>
    			</div>
                <!-- <div class="mui-input-row mui-radio show">
                    <label>地址</label><input id="choose_dz" name="radio1" type="radio">
                    <input type="text" id="pg_village2" data-key="village" data-type="auto" />
                    <span class="com_form_ext" id="dzsearch"><span class="mui-icon mui-icon-search"></span></span>
                    <div class="ame_row_mask"></div>
                </div> -->
    			<div class="mui-input-row show" id="sel_ban_div">
    				<label>楼栋</label>
    				<select id="pg_ban" name="pg_ban" data-key="ban" data-type="select" class="select"></select>
    				<span class="com_form_arrdown"></span>
    				<div class="ame_row_mask"></div>
    			</div>
                <div class="mui-input-row hide_div" id="ban_div" style="display: none;">
                    <label>楼栋单元</label>
                    <input type="text" id="ban_address" name="ban_address"/>
                </div>
    			<!-- <div class="com_form_inrow"> -->
                <div class="mui-input-row show" id="room_div">
                    <label>房号</label>
                    <select id="pg_roomno" data-key="roomno" data-type="select" class="select"></select>
                    <span class="com_form_arrdown"></span>
                    <div class="ame_row_mask"></div>
                </div>
                <div class="hide_div new_room_div" style="display: none;">
                    <div class="mui-input-row">
                        <label>房间号</label>
                        <input type="text" id="new_room_no"/>
                        <span class="com_form_ext">室</span>
                    </div>
                </div>
                <div class="mui-input-row hide_div" style="display: none;">
                    <label>朝向</label>
                    <select id="orientation" name="chaoxiang">
                        <option value="0"></option>
                        <?php foreach ($forward as $k => $v):?>
                            <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="com_form_arrdown"></span>
                </div>
                <div class="mui-input-row hide_div" style="display: none;">
                    <label>总楼层</label>
                    <input type="text" id="total_floor" name="total_floor" oninput="this.value=this.value.replace(/\D/g,'')"/>
                </div>
                <div class="mui-input-row hide_div" style="display: none;">
                    <label>所在楼层</label>
                    <input type="text" id="in_floor" name="in_floor" oninput="this.value=this.value.replace(/[^\-\d]/g,'')"/>
                </div>
                <div class="mui-input-row show" id="area_div">
                    <label>面积</label>
                    <input type="text" id="pg_roomarea" name="pg_roomarea" class="mui-input-clear mui-input-numbox" data-key="roomarea" data-type="txt" style="padding-left:80px;">
                    <span class="com_form_ext">平米<i>*</i></span>
                    <div class="ame_row_mask"></div>
                </div>
    			<!-- </div> -->
        	</div>

        	<div class="com_form_btns">
        		<a id="submitbtn" class="mui-btn mui-btn-block mui-btn-success">房产评估结果</a>
        		<!-- <a id="apply" class="mui-btn mui-btn-block mui-btn-success">马上评估</a> -->
        	</div>
        </div>
        <div id="shop" class="mui-control-content">
            <!-- <p class="com_info_title">房屋估价</p> -->
            <div class="com_form_content ame_info_form mui-input-group com_form_auto">
                <div class="com_form_inrow">
                    <div class="mui-input-row areainfo">
                        <label>省份</label>
                        <select id="pg_province" data-key="province" data-type="select" class="select">
                                <option value="-1">请选择</option>
                                <option value=""></option>
                        </select>
                        <span class="com_form_arrdown"></span>
                        <div class="ame_row_mask"></div>
                    </div>
                    <div class="mui-input-row show">
                        <label>城市</label>
                        <select id="pg_city" data-key="city" data-type="select" class="select"></select>
                        <span class="com_form_arrdown"></span>
                    </div>
                </div>
                <div class="mui-input-row" id="sel_area_div">
                    <label>区县</label>
                    <select id="pg_area" data-key="area" data-type="select" class="select"></select>
                    <span class="com_form_arrdown"></span>
                    <div class="ame_row_mask"></div>
                </div>
                <div class="mui-input-row">
                    <label>房产</label>
                    <input type="text" id="address" placeholder="请输入房产名称"/>
                </div>
                <div class="mui-input-row">
                    <label>楼栋单元</label>
                    <input type="text" id="ban"/>
                </div>
                <!-- <div class="com_form_inrow"> -->
                <div class="mui-input-row">
                    <label>房间号</label>
                    <input type="number" id="room_no" maxlength="5"/>
                    <span class="com_form_ext">室</span>
                </div>
                <div class="mui-input-row">
                    <label>朝向</label>
                    <select id="orientation" name="chaoxiang">
                        <option value="0"></option>
                        <option value=""></option>
                    </select>
                    <span class="com_form_arrdown"></span>
                </div>
                <div class="mui-input-row">
                    <label>总楼层</label>
                    <input type="number" id="total_floor" oninput="this.value=this.value.replace(/\D/g,'')"/>
                </div>
                <div class="mui-input-row">
                    <label>所在楼层</label>
                    <input type="number" id="in_floor" oninput="this.value=this.value.replace(/[^\-\d]/g,'')"/>
                </div>
                <div class="mui-input-row show" id="area_div">
                    <label>面积</label>
                    <input type="number" id="pg_roomarea" name="pg_roomarea" class="mui-input-clear mui-input-numbox" data-key="roomarea" data-type="txt" style="padding-left:80px;">
                    <span class="com_form_ext">平米<i>*</i></span>
                </div>
                <!--<div class="mui-input-row">
                    <label class="row-lab">周边是否有肯德基或麦当劳</label>
                    <div class="mui-radio radio_zb">
                        <input name="radio1" type="radio">
                        <label>有</label>
                    </div>
                    <div class="mui-radio radio_zb">
                        <input name="radio1" type="radio" checked>
                        <label>无</label>
                    </div>
                </div>-->
                <!-- </div> -->
                <input type="hidden" id="type" value="">
            </div>

            <div class="com_form_btns">
                <a id="submitbtn" class="mui-btn mui-btn-block mui-btn-success">提交房产评估</a>
            </div>
        </div>
	</div>
    <div id="resultMask"></div>
    <script src="/static/base/js/jquery.2.1.1.min.js"></script>
    <script src="/static/ldd/js/libs/mui.min.js"></script>
    <script src="/static/ldd/js/common.js"></script>
    <script src="http://cdn.bootcss.com/hammer.js/2.0.4/hammer.min.js"></script>
    <script src="/static/ldd/js/liandong-xy.js"></script>
</body>
</html>
