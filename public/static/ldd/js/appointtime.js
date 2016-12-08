(function($) {
	$.init();
	//日期选择器
    var dtps  = $('.jin_dtp');
	dtps.each(function(i, dtp) {
		dtp.addEventListener('tap', function() {
			var optionsJson = this.getAttribute('data-options') || '{}';
			var options = JSON.parse(optionsJson);
			var id = this.getAttribute('id');
			var picker = new $.DtPicker(options);
			picker.show(function(rs) {
                dtp.innerText = rs.value;
				dtp.value = rs.value;
				picker.dispose();
			});
		}, false);
	});
})(mui);


$(function(){
    $('#apponitSub').click(function(){
        var param = {};
            param['projectNo']  = $('#bsNumber').val();
            param['appointTime']  = $('#jinTimes').val();
            param['remark'] = $('#remarks').val();
        $.ajax({
                type:'post',
                url: '/ldd/loan/appointtime',
                data: param,
                dataType:'JSON',
                async:true,
                success:function(data){
                    if (data.result == 1) {
                        var tourl = data.extension == "" ? "" : data.extension;
                        if (tourl != '') {
                            window.location.href = tourl;
                        }else{
                            WeixinJSBridge.call('closeWindow');
                        }
                    }else{
                        mui.alert(data.message);
                    }
                },
                error: function(e){
                    console.log(e);
                }
        })
});


})
