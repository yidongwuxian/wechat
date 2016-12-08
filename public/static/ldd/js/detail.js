(function($) {
	$.init();
	//日期选择器
    var fYears  = $('#year');
	fYears.each(function(i, dtp) {
		dtp.addEventListener('tap', function() {
			var optionsJson = this.getAttribute('data-options') || '{}';
			var options = JSON.parse(optionsJson);
			var id = this.getAttribute('id');
			var picker = new $.DtPicker(options);
			picker.show(function(rs) {
                document.getElementById('completionTime').value = '';
                dtp.innerText = rs.value;
				dtp.value = rs.value;
				picker.dispose();
			});
		}, false);
	});
})(mui);
//12.6加入回显数据
//获取url参数
function getQueryString(name) { 
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
	var r = window.location.search.substr(1).match(reg); 
	if (r != null) return unescape(r[2]); return null; 
}
function myAjax(url, data, callback, noDataCallBack){
	$.ajax({
		type:'post',
		url: url,
		data: data,
		dataType:'JSON',
		async:true,
		success:function(data){
			if(data.result == 1 && data.extension){
				callback(data.extension);
			}else if(typeof noDataCallBack == 'function'){
				noDataCallBack(data);
			}
		},
		error: function(e){
			mui.alert('服务器读取数据失败');
		}
	});
}
//加载初始化方向
function initForwards(forward){
	myAjax('/ldd/information/getforward', {}, function(data){
		var html = '';
		for(var k in data){
			html += '<option value="'+ k +'">'+ data[k] +'</option>';
		}
		var $ori = $('#orientation').html(html);
		if(forward) $ori.val(forward);
	});
}
//加载初始化房屋类型
var thisData = null;//注册全局数据
var houseTypeData = null;//注册全局房屋类型，防止再次请求（此时城市可填null）
function getHouseType(city, type, val){
	var createhtml = function(data){
		var list;
		if(type == 1){
			list = data.houseTypeOne;
		}else if(type == 2){
			list = data.houseTypeTwo;
		}else{
			list = '';
		}
		var html = '';
		for(var k in list){
			html += '<option value="'+ k +'">'+ list[k] +'</option>';
		}
		$('#sel_house_type').html(html);
		if(val) $('#sel_house_type').val(val);
	}

	if(houseTypeData){
		createhtml(houseTypeData);
	}else{
		myAjax('/ldd/loan/gethousetype', {city: city}, function(data){
			houseTypeData = data;
			createhtml(houseTypeData);
		});
	}
}
//加载初始化基础数据
function initData(data){
	$('#d_addr').html(data.address);
	//$('#orientation').val(data.forward);
	initForwards(data.forward);
	$('#area').val(data.area);
	$('#floorToal').val(data.totalFloor);
	$('#floor').val(data.floor);
	$('#lift').val((function(){
		return typeof data.elevator == 'number'? data.elevator: ['有', '无'].indexOf(data.elevator) + 1;
	})());
	$('#completionTime').val(data.year);
}
//加载初始化扩展信息
function initExtendData(data, mortgageType){
	if(!mortgageType)
		return false;
	$("#sel_mortgage").val(mortgageType);
	getHouseType(thisData.city, mortgageType, data.houseType);
	if(mortgageType == 2){
		$('.div_erya').show();
		$('.div_erya').find('input').addClass('valid').data('class','one_residual');
		$('.div_erya').find('select').addClass('sel_yiya valid').data('class','is_five');
		if(data && data.oneResidual){
			$('#one_residual').val(data.oneResidual);
			$('#is_five').val(data.isFive);
			$('#haveKFC').val(data.haveKFC);
		}
	}else{
		$('.div_erya').hide();
	}
}


/*****************************页面初始化*******************************/
var thisId = getQueryString('id');

//初始化加载
myAjax('/ldd/loan/getdetail', {id: thisId}, function(data){
	//data.mortgageType =2 //测试
	thisData = data;
	initData(data);
	initExtendData(data, data.mortgageType);
}, function(data){
	window.location.href = data.extension;//不成功跳转
});

//12.6 end

//获取房产类型数据
$("#sel_mortgage").on('change', function(){
	initExtendData({}, $(this).val());
});

$('#detailSave').on('click',function(){console.log(thisData)
	var param = {};
	param['id']           = thisId;
	param['area']         = $('#area').val();
    param['forward']      = $('#orientation').find("option:selected").val() || $('#orientation1').find("option:selected").val();
    param['totalFloor']   = $('#floorToal').val();
    param['floor']        = $('#floor').val();
    param['elevator']     = $('#lift').find("option:selected").val();
    param['year']         = $('#completionTime').val() || $('#year').val();
	param['mortgageType'] = $('#sel_mortgage').find("option:selected").val();
    param['houseType']    = $('#sel_house_type').find("option:selected").val();
	if($('#sel_mortgage').find("option:selected").val() == 2){
		param['oneResidual'] = $('#one_residual').val();
	    param['isFive']      = $('#is_five').find("option:selected").val();
		param['haveKFC']     = $('#haveKFC').find("option:selected").val();
	}
	    var valid = $('.ame_info_form').valid();
		if(valid.flag){
			$.ajax({
					type:'post',
					url: '/ldd/loan/detail',
					data: param,
					dataType:'JSON',
					async:true,
					success:function(data){
						if( data.extension != null){
							//$('.ame_info_form')[0].reset();
							location.href = data.extension;
						}
					},
					error: function(e){
						console.log(e);
					}
				})
		}else{
			mui.alert(valid.msg);
		}
		return;

});
