//start
mui.init();

var data = {
	province: 'getprovince',
	city:     'getcity',
	district: '',
	ban:      'getbanunitlist',
	room:     'getroomlist',
	area:     'getroominfobyid'
};

var isProviceShow = $('#isProviceShow').val();

//初始化数据
getData($('#pg_province'),{},data.province);
//getData($('#pg_province1'),{},data.province);

//获取数据
function getData($this,param,name,callback){
	$.ajax({
		type:'post',
		url: '/ldd/information/' +name,
		data:param,
		dataType:'JSON',
		async:true,
		success:function(msg){
			if(!msg || msg.result==false){
	            if($this.data('key')=='ban'){
	                //如果没有小区的楼栋数据
	                mui.alert('暂无该小区的楼栋数据，请手动输入');
	            }else{
	                mui.alert('暂无该小区的楼栋数据');
	            }
				if($this.data('key')=='roomno'){
					mui.alert('暂无该房号的面积，请手动输入');
				}
	            return false;
			}else{
				if(msg.extension.fArea != null){
					$('#pg_roomarea').val(msg.extension.fArea);
				}
				else{
					createOptions(msg.extension,$this);
				}
			}
		}
	}).done(function(){
		if($.isFunction(callback)) {
			callback();
		}
	});
}

//创建select的options
function createOptions(list,$this){
	var _key  = $this.data('key'),
	    _html = '';
	if(_key=='province') {
 	    _html = '<option value="-1">请选择</option>';
		 list.forEach(function(item,i){
  		   _html+='<option value="'+item.code+'">'+item.province+'</option>';
     	});
    }
	else if(_key=='city') {
		list.forEach(function(item,i){
			_html+='<option value="'+item.code+'">'+item.city+'</option>';
		});
	}else if(_key=='ban') {	//当是选择楼栋的时候应该加入data-buildingId
		_html = '<option value="-1" data-buildingid="-1">请选择</option>';
		list.forEach(function (item, i) {
			_html += '<option value="' + item.sUnitCode + '" data-buildingid="' + item.sBanCode + '">' + item.sBanName + '</option>';
		});
		//如果是楼栋，加入其他选项
		_html += '<option value="999999" data-buildingid="999999">其他</option>';
   }
   else if(_key=='roomno'){
	    _html = '<option value="-1">请选择</option>';
		list.forEach(function(item,i){
		    _html+='<option value="'+item.sRoomID+'">'+item.sRoomNo+'</option>';
		});
		_html+='<option value="999999">其他</option>';
   }else if(_key=='roomarea'){

   }
   $this.html(_html);
}



//省选择
$('#pg_province').on('change',function(){
	if($(this).val()>-1){
		getData($('#pg_city'),{
			province: $('#pg_province').find("option:selected").val(),
			provinceName: $('#pg_province').find("option:selected").text()
		},data.city);
		hideMask($('#pg_city'));
        var _next = $(this).data('key');
		//liandong(_next);
	}else{
		$('#pg_city').html('').val('-1');
	}
});

function liandong(nextIndex){
	var Keys = [];
	var tempKeys = [];
	$('#home label').each(function(i,item){
		Keys.push($(this).next().data('key'));
		if(nextIndex == Keys[i]){
			var $pg = $('#pg_'+Keys[i]);
	 		    $pg.html('').val('');
				showMask($pg);
	 			if($pg.data('type')=='txt'){
	 				$pg.removeAttr('disabled');
	 			}
		}
	})
}

//市选择
$('#pg_city').on('click',function(){             //注意：测试数据为一条时，change事件不起作用
	if($(this).val()>-1){
		hideMask($('#pg_village'));
	}
});

//小区搜素
$('#pg_village').on('keyup',function(event){
    var searchText = $('#pg_village').val();
	if(searchText != ''){
		var datalist = {
			city: $('#pg_city').find("option:selected").text(),
			name: $('#pg_village').val()
		};
	$.ajax({
        type: 'POST',
	    url: '/ldd/information/getprojectlist',
        cache:false,
		data: datalist,
        dataType: 'json',
        success: function (json){
            if (json != null) {
            	var newJson = json.extension;
				if(searchText != ''){
					var tab = "";
						$.each(newJson,function(id, item){
							if(item.name.indexOf(searchText)!=-1){
								tab +="<li data-code="+item.code+">"+item.name+"</li>";
							}
						});
						$('#result ul').html(tab);
						$('#result').show();
						//点击搜索框li结果,传值给楼栋  start
						var result  = document.getElementById('result'),
							resultLi = result.getElementsByTagName("li");
							for(var k=0; k < resultLi.length; k++){
								(function(k){
									var hammerLi = new Hammer(resultLi[k]);
									hammerLi.on("tap", function(e){
										var $code = resultLi[k].getAttribute("data-code");
										var $value = resultLi[k].innerHTML;
										$('#pg_village').val($value);
										$('#pg_village_val').val($code);
										$('#result').hide();
										getData($('#pg_ban'),{
											city:  $('#pg_city').find("option:selected").text(),
											newCode: $('#pg_village_val').val(),
											projectName: $('#pg_village').val()
										},data.ban);
										hideMask($('#pg_ban'));
									});
								})(k);
							}
					    //点击搜索框li结果,传值给楼栋 end
				}
				else{
					$('#result ul').html('');
				}
            }
        },
        error: function(e){
        	console.log(e);
        }
    })
	}else{
		$('#result ul').html('');
		$('#result').hide();
	}
});

$('#pg_province1').on('change',function(){
	if($(this).val()>-1){
		getData($('#pg_city1'),{province1: $('#pg_province1').val()},data.city);
		hideMask($('#pg_city1'));
	}else{
		$('#pg_city1').html('').val('-1');
	}
})

$('#pg_city1').on('click',function(){            //注意：测试数据为一条时，change事件不起作用
	if($(this).val()>-1){
		getData($('#pg_district'),{city1: $('#pg_city1').val()},data.district);
	}
});

//楼栋选择
$('#pg_ban').on('change',function(){
	if($(this).val()>-1){
		if($(this).find('option:selected').val() == "999999"){
			$('.hide_div').show();
			//手动输入的房号隐藏
			$('#room_div').hide();
			//手动输入面积
			$('#pg_roomarea').val('');
			hideMask($('#pg_roomarea'));
		}else{
			getData($('#pg_roomno'),{
				city: $('#pg_city').find("option:selected").text(),
				newCode: $('#pg_village_val').val(),
				banCode: $('#pg_ban').find("option:selected").data('buildingid'),
				unitCode:$('#pg_ban').find("option:selected").val(),
				banName: $('#pg_ban').find("option:selected").text()
			},data.room);
			hideMask($('#pg_roomno'));
		}
	}else{
		$('#pg_ban').html('').val('-1');
	}
});

//房号选择
$('#pg_roomno').on('change',function(){
	if($(this).val()>-1){
		getData($('#pg_roomarea'),{
			city: $('#pg_city').find("option:selected").text(),
			newCode:$('#pg_village_val').val(),
			roomId:  $('#pg_roomno').find("option:selected").val(),
			roomNo:  $('#pg_roomno').find("option:selected").text()
		},data.area);
		hideMask($('#pg_roomarea'));
	}else{
		$('#pg_roomno').html('').val('-1');
	}
});

document.querySelector('.mui-switch').addEventListener('toggle', function(event) {
	if(event.detail.isActive){
		$(event.target).find('.mui-switch-handle').text('地址');
		//that.data('plugin_autoComplete').resetAjaxUrl('/ldd/index/queryvillage');
		$('#infoTip').html('<a>如果没有您想要的信息，请点击切换<span class="pg_blue">人工评估</span></a>');
		//window.location="/ldd/index/queryvillage";
	}else{
		$(event.target).find('.mui-switch-handle').text('小区');
		//that.data('plugin_autoComplete').resetAjaxUrl('/ldd/index/getvillagelist');
		$('#infoTip').html('<a>如果没有您想要的信息，请切换至地址查询</a>');
		//window.location="/ldd/index/getvillagelist";
	}
});

//表单提交
$('#submitbtn').on('click',function(){
	var param = {};

	param['city'] = $('#pg_city').find("option:selected").text();
	param['newCode'] = $('#pg_village_val').val(),
	param['banCode'] = $('#pg_ban').find("option:selected").data('buildingid');
	param['unitCode'] = $('#pg_ban').find("option:selected").val();
	param['roomId'] = $('#pg_roomno').find("option:selected").val();
	param['area'] = $('#pg_roomarea').val();


	// if($('#pg_province').find('option:selected').val() == '-1'){
	// 	mui.alert('省份是必填项');
	// 	return false;
	// }else if($('#pg_city').find('option:selected').val() == ''){
	// 	mui.alert('城市是必填项');
	// 	return false;
	// }else if($('#pg_village_val').val() == ''){
	// 	mui.alert('房产是必填项');
	// 	return false;
	// }else if($('#pg_ban').find('option:selected').val() == '-1'){
	// 	mui.alert('楼栋是必填项');
	// 	return false;
	// }else if($('#pg_roomno').find('option:selected').val() == '-1'){
	// 	mui.alert('房号是必填项');
	// 	return false;
	// }else if($('#pg_roomarea').val() === ''){
	// 	mui.alert('面积是必填项');
	// 	return false;
	// }
	// else{
	// 	//console.log(param);
	sumbitResult();
	// }

	if($('#pg_ban').find('option:selected').val() == "999999"  || $('#pg_ban').find("option:selected").val() == "999999" || $('#pg_roomno').find("option:selected").val() == "999999" ){
		param['banName'] = $('#pg_ban').find("option:selected").text();
		param['roomNo'] = $('#new_room_no').val();
		param['forward'] = $('#orientation').find("option:selected").val();
		param['totalFloor'] = $('#total_floor').val();
		param['floor'] = $('#in_floor').val();
		param['roomId'] = '999999';

		// if($('#pg_province').find('option:selected').val() == '-1'){
		// 	mui.alert('省份是必填项');
		// 	return false;
		// }else if($('#pg_city').find('option:selected').val() == ''){
		// 	mui.alert('城市是必填项');
		// 	return false;
		// }else if($('#pg_village_val').val() == ''){
		// 	mui.alert('房产是必填项');
		// 	return false;
		// }else if($('#pg_ban').find('option:selected').val() == '-1'){
		// 	mui.alert('楼栋是必填项');
		// 	return false;
		// }else if($('#new_room_no').val() == ''){
		// 	mui.alert('房间号是必填项');
		// 	return false;
		// }else if($('#orientation').find('option:selected').val() == ''){
		// 	mui.alert('朝向是必填项');
		// 	return false;
		// }
		// else if($('#total_floor').val() == ''){
		// 	mui.alert('总楼层是必填项');
		// 	return false;
		// }
		// else if($('#in_floor').val() == ''){
		// 	mui.alert('所在楼层是必填项');
		// 	return false;
		// }
		// else if($('#pg_roomarea').val() === ''){
		// 	mui.alert('面积是必填项');
		// 	return false;
		// }
		// else{
		// 	sumbitResult();
		// }
	}

	function sumbitResult(){
		showLoading();

		$.ajax({
            type: 'post',
            url: "/ldd/loan/evaluation",
            data: param,
            dataType: 'json',
            success: function (res) {
                if(res.result == 1){
                    window.location.href = res.extension;
                }else{
                    mui.alert(res.message);
                    return false;
                }
            }
        });
	}

});

$('#submitbtn1').on('click',function(){
	var param = {};
	param['province1'] = $('#pg_province1').find('option:selected').text();
	param['city1'] = $('#pg_city1').find("option:selected").text();
	param['district'] = $('#pg_district').find("option:selected").text();
	param['address'] = $('#address').val();
	param['banAddress1'] = $('#ban_address1').val();
	param['roomCode1'] = $('#pg_roomno1').val();
	param['orientation1'] = $('#orientation1').find("option:selected").text();
	param['total_floor1'] = $('#total_floor1').val();
	param['in_floor1'] = $('#in_floor1').val();
	param['pg_roomarea1'] = $('#pg_roomarea1').val();

	test($('#pg_roomarea1'));
	test($('#in_floor'));
	test($('#total_floor'));
	test($('#orientation1'));
	test($('#pg_roomno1'));
	test($('#ban_address1'));
	test($('#address'));
	test($('#pg_district'));
	test($('#pg_city1'));
	test($('#pg_province1'));

	if(flag == true ){
			showLoading();
			console.log(param);
			$("#form_info1").val(JSON.stringify(param));
			$("#myForm1").submit();
	}else{
		return false;
	}
});

//添加遮罩层
function showMask(obj){
	obj.parents('.mui-input-row').addClass('show');
}
//移除遮罩层
function hideMask(obj){
	obj.parents('.mui-input-row').removeClass('show');
}
