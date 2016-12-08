$.ajax({
    type: 'post',
    url: '/ldd/agent/agentlist',
    dataType: 'JSON',
    async: true,
    success: function (data) {
        initRender(data)
        agentlistserch(data)
    }
})
var reg = new RegExp("[\\u4E00-\\u9FFF]+", "g");

function agentlistserch(data) {
    $("#agentlist_btn").on("keyup", function () {
        var Sample = $(this).val();
        if (reg.test(Sample) == true) {
            var views = "";
            $.each(data.extension, function (i, v) {
                $.each(v, function (i1, v1) {
                    if (v1.businessPeopleName.indexOf(Sample) != -1) {
                        views += ergodic(v1);
                    }
                })
            })
            $(".yewu_list").html(views)
        } else if (Sample != "") {
            var views = ""
            $.each(data.extension, function (i, v) {
                $.each(v, function (i1, v1) {
                    var bs = v1.tel.toString();
                    if (v1.businessPeopleNum.indexOf(Sample) != -1 && bs.indexOf(Sample) == -1) {
                        views += ergodic(v1);
                    } else if (bs.indexOf(Sample) != -1 && v1.businessPeopleNum.indexOf(Sample) == -1) {
                        views += ergodic(v1);
                    }

                })
            })
            $(".yewu_list").html(views)
        } else if (Sample === "") {
            var views = ""
            $.each(data.extension, function (i, v) {
                $.each(v, function (i1, v1) {
                    views += ergodic(v1);
                })
            })
            $(".yewu_list").html(views)
        }
    })
}

function initRender(data) {

    var views = ""
    $.each(data.extension, function (i, v) {
        $.each(v, function (i1, v1) {
            views += ergodic(v1);
        })
    })
    $(".yewu_list").html(views)
}

function ergodic(v1) {

    return " <li><p><span>" + v1.businessPeopleNum + "</span><span>" + v1.businessPeopleName + "</span><span>" + v1.tel + "</span><a href='/ldd/agent/edit/id/" + v1.businessPeopleNum + "' class='xiugai'><img src='/static/ldd/img/pages/biji.png' alt=''></a></p></li>"

}