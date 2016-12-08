$(".edit_button").on("touchend", function () {
    var index = $("#data_id").val();
    var agentNames = $("#namse").val();
    var shoujihao = $("#shoujihao").val();
    console.log(index);
    console.log(agentNames);
    console.log(shoujihao);
    $.ajax({
        type: 'post',
        url: '/ldd/agent/edit',
        data: {
            id: index,
            agentName: agentNames,
            mobile: shoujihao
        },
        dataType: 'JSON',
        async: true,
        success: function (vals) {
            if (vals.message == "编辑成功！") {
                mui.alert('重置成功');
                if (vals.extension != "") {
                    location.href = vals.extension;
                } else {
                    WeixinJSBridge.call('closeWindow');
                }
            } else {
                mui.alert('重置失败');
            }

        }
    })
})