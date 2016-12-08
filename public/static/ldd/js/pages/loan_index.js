var windowhe = $(Window).height();
$("body").css({
    "height": windowhe
});

var target = ".aa";
var numfrequency = true;
var urls = "/ldd/loan/getapplylist";
var inputs = "#geren";
$("#aa").on("touchend", function () {
    inputs = "#geren";
    target = ".aa"
    urls = "/ldd/loan/getapplylist";
    initial();
})
$("#bb").on("touchend", function () {
    inputs = "#jigou";
    target = ".bb"
    urls = "/ldd/loan/getorgapplylist";
    initial();
})

$.ajax({
    type: 'post',
    url: '/ldd/user/info',
    dataType: 'JSON',
    async: true,
    success: function (vals) {
        if (vals.extension.type) {
            $("#bb").hide();
        }
    }
})

mui.init({
    pullRefresh: {
        container: '#content',
        down: {
            callback: pulldownRefresh
        },
        up: {
            contentrefresh: '正在加载...',
            callback: pullupRefresh
        }
    }
})

function pulldownRefresh() {

    var table = document.body.querySelector(target);
    var cells = document.body.querySelectorAll('.mui-table-view-cell');

    mui('#content').pullRefresh().endPulldownToRefresh(); //refresh completed


}
var count = 0;
var frequency = 0;

function pullupRefresh() {
    if (frequency != 0) {
        dropdown_Load()
    } else {
        frequency++
        initial()
    }
}
//下拉加载数据
function dropdown_Load() {
    mui('#content').pullRefresh().endPullupToRefresh((0));
    var table = document.body.querySelector(target);
    var cells = document.body.querySelectorAll('.mui-table-view-cell');

}
//初次渲染DOM
function initial() {
    mui('#content').pullRefresh().endPullupToRefresh((0));
    var table = document.body.querySelector(target);
    var cells = document.body.querySelectorAll('.mui-table-view-cell');
    $.ajax({
        type: 'post',
        url: urls,
        dataType: 'JSON',
        async: true,
        success: function (data) {
            if (data.message != "查询成功！") {
                if (data.extension != "") {
                    location.href = data.extension;
                } else {
                    WeixinJSBridge.call('closeWindow');
                }
                return false;
            }
            $.each(data.extension, function (i, v) {
                var li = document.createElement('li');
                var types = typeif(v.status)
                var hetong = "";
                if (v.hasContract == 1) {
                    hetong = "<a href='javascript:;' data_biahao=" + v.projectNo + " class='hetong'>合同</a>"
                }
                var list_p = "<p><span class='delte'></span>" + v.projectNo + " <span class='time'>2016-02-08 08:12:20</span></p><p class=' names'><span class='name'>" + v.custName + "</span><span class='mani'>50万</span></p><p class='btns'>" + types + "<a href='javascript:;' data_biahao=" + v.projectNo + " class='chakan'>查看</a>" + hetong + "</p>";
                li.innerHTML = list_p;
                table.appendChild(li)
            })
            tiaozh()
            $(inputs).on("keyup", function () {
                var text = $(inputs).val();
                search(text, data)
            })
        },
        error: function (e) {}
    });
}

function search(val, data) {
    var table = document.body.querySelector(target);
    var reg = new RegExp("[\\u4E00-\\u9FFF]+", "g");
    var reg1 = /^[0-9a-zA-Z]+$/
    if (val != "" && reg.test(val)) {
        $(".loan_listitem").html("");
        $.each(data.extension, function (i, v) {
            if (v.custName.indexOf(val) != -1 || v.status.indexOf(val) != -1) {
                var types = typeif(v.status)
                var li = document.createElement('li');
                var hetong = "";
                if (v.hasContract == 1) {
                    hetong = "<a href='javascript:;' data_biahao=" + v.projectNo + " class='hetong'>合同</a>"
                }
                var list_p = "<p><span class='delte'></span>" + v.projectNo + " <span class='time'>2016-02-08 08:12:20</span></p><p class=' names'><span class='name'>" + v.custName + "</span><span class='mani'>50万</span></p><p class='btns'>" + types + "<a href='javascript:;' data_biahao=" + v.projectNo + " class='chakan'>查看</a>" + hetong + "</p>";
                li.innerHTML = list_p;
                table.appendChild(li)
            }
        })
        tiaozh()
    } else if (reg1.test(val)) {
        $(".loan_listitem").html("");
        val = val.toUpperCase();
        $.each(data.extension, function (i, v) {
            if (v.projectNo.indexOf(val) != -1) {
                var types = typeif(v.status)
                var li = document.createElement('li');
                var hetong = "";
                if (v.hasContract == 1) {
                    hetong = "<a href='javascript:;' data_biahao=" + v.projectNo + " class='hetong'>合同</a>"
                }
                var list_p = "<p><span class='delte'></span>" + v.projectNo + " <span class='time'>2016-02-08 08:12:20</span></p><p class=' names'><span class='name'>" + v.custName + "</span><span class='mani'>50万</span></p><p class='btns'>" + types + "<a href='javascript:;' data_biahao=" + v.projectNo + " class='chakan'>查看</a>" + hetong + "</p>";
                li.innerHTML = list_p;
                table.appendChild(li)
            }
        })
        tiaozh()
    } else if (val == "") {
        $(".loan_listitem").html("");
        $.each(data.extension, function (i, v) {
            var types = typeif(v.status)
            var li = document.createElement('li');
            var hetong = "";
            if (v.hasContract == 1) {
                hetong = "<a href='javascript:;'  data_biahao=" + v.projectNo + "  class='hetong'>合同</a>"
            }
            var list_p = "<p><span class='delte'></span>" + v.projectNo + " <span class='time'>2016-02-08 08:12:20</span></p><p class=' names'><span class='name'>" + v.custName + "</span><span class='mani'>50万</span></p><p class='btns'>" + types + "<a href='javascript:;' data_biahao=" + v.projectNo + " class='chakan'>查看</a>" + hetong + "</p>";
            li.innerHTML = list_p;
            table.appendChild(li)
        })
        tiaozh()
    }

}

function typeif(items) {
    var item = items;
    if (item.indexOf("|") == -1) {
        var statustext = item;
        var statuscolor = "green";
    } else {
        var indexs = item.indexOf("|");
        var statustext = item.slice(0, indexs)
        var statuscolor = item.slice(indexs + 1, item.length)
    }
    return types = "<span class=" + statuscolor + ">" + statustext + "</span>"
}
if (mui.os.plus) {
    mui.plusReady(function () {
        setTimeout(function () {
            mui('#content').pullRefresh().pullupLoading();
        }, 1000);
    });
} else {
    mui.ready(function () {
        mui('#content').pullRefresh().pullupLoading();
    });
}

function tiaozh() {
    $(".chakan").on("touchend", function () {
        var hrefs = $(this).attr("data_biahao")
        location.href = "/ldd/loan/orderdetail?bianhao=" + hrefs + ""
    })
    $(".hetong").on("touchend", function () {
        var hrefs = $(this).attr("data_biahao");
        location.href = "/ldd/loan/contractdetail/projectNo/" + hrefs + ""
    })
}