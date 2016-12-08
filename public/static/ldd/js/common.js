/**
 * Created by Administrator on 2016/6/15.
 */
//显示蒙版，避免重复点击
function showLoading() {
    $(document.body).append('<div class="backdrop">\
    <div class="floatingWrap"><div class="floatingBarsG">\
    <div class="blockG rotateG_01"></div>\
    <div class="blockG rotateG_02"></div>\
    <div class="blockG rotateG_03"></div>\
    <div class="blockG rotateG_04"></div>\
    <div class="blockG rotateG_05"></div>\
    <div class="blockG rotateG_06"></div>\
    <div class="blockG rotateG_07"></div>\
    <div class="blockG rotateG_08"></div>\
    </div></div>\
    </div>');
}
//隐藏蒙版
function hideLoading() {
    $('.backdrop').remove();
}
//倒计时
var wait = 60;
function time(o) {
    if (wait == 0) {
        o.attr("disabled", false);
        o.css('background-color', '#007aff');
        o.html("获取验证码");
        wait = 60;
    } else {
        o.attr("disabled", true);
        o.html("重新发送(" + wait + ")");
        o.css('background-color', '#007aff');
        wait--;
        setTimeout(function () {
                time(o)
            },
            1000)
    }
}
function clearNoNum(obj) {
    obj.value = obj.value.replace(/[^\d.]/g, ""); //清除“数字”和“.”以外的字符
}
function clearIdCard(obj) {
    obj.value = obj.value.replace(/[^(\d{17})(\d|X|x)$]/g, "");
}