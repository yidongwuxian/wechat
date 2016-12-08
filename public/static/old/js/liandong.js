mui.init();//mui加载
(function(){

	var mainHref = '/ldd/index/';
	var keys = [/*'province',*/'city','village','ban','roomno','roomarea'];//查询编号'area',
	var keyCns = [/*'省份',*/'城市','小区','楼栋','房号','面积'];//对应汉字'区域',
	var controller = [/*'queryCity',*/'queryCity','','getunitlist','getroomlist','getroominfo'];//对应请求路径'queryCity',
	var submitData = {};//需要提交的数据
	
/***************************事件*****************************/	
	//初始加载省份select
	getData(0);
	//select选择时候事件
	$('.com_form_content').on('change','.select',function(){
		var $this = $(this);
        if($this.val()=='-1'){
            var _key = $this.data('key'),  //关键字，keys中的
                _nextIndex = keys.indexOf(_key)+1;//下一个select索引
            if(_nextIndex>=keys.length)	//当下一个索引值是最后一个就不再进行查询
                return false;
            clearForm(_nextIndex);
            if(_key == 'ban'){
                //如果楼栋重置
                $('.hide_div').hide();
                $('#room_div').show();
                $('#pg_roomarea').val('');
            }
            if(_key == 'roomno'){
                $('.new_room_div').hide();
                $('#new_room_no').val('');
                $('#pg_roomarea').val('');
            }
            return false;
        }
        loadSelect.call(this);
	});
	//input data-type="txt"的文本框写入事件，主要记录表单数据
	$('.com_form_content').on('input propertychange','input[data-type="txt"]',function(){
		var $this = $(this);
			_key = $this.data('key');
			_val = $this.val(),
			_name = _val;
		setSubmitData(_key,_val,_name);	
	});

	mui('.ame_btn').on('tap','#sub',function(){
        showLoading();
        var data = {};
        data.cust_name = $("#cust_name").val();
        data.id_card   = $('#id_card').val();
        //身份证校验
        //var check_id_result = IdentityCodeValid(data.id_card);
        //if( ! check_id_result){
        //    mui.alert('身份证号格式不正确！');
        //    hideLoading();
        //    return false;
        //}
        data.mortgage_type = $('#sel_mortgage').val();
        data.house_type  = $('#sel_house_type').val();
        data.oneResidual = $('#one_residual').val();
        data.isFive = $('#is_five').val();
        data.apply_limit = $('#apply_limit').val();
        data.loan_term   = $('#loan_term').val();
        data.house_info  = $('#house_info').val();
        data.addr  		 = $('#addr').val();
        data.orientation = $('#orientation').val();  
        data.area 	= $('#area').val();
        data.floor  = $('#floor').val();
        data.floorToal = $('#floorToal').val();
        data.lift 	= $('#lift').val();
        data.year 	= $('#year').val();
        data.price1 = $('#price1').val();
        data.price2 = $('#price2').val();
        data.price3 = $('#price3').val();
        data.price4 = $('#price4').val();
        data.price5 = $('#price5').val();

        // console.log(data);
        $.ajax({
            type : "POST",
            url : "/ldd/loan/toapply",
            data : data,
            dataType: "json",
            //async: false,
            //beforeSend: function(){
            //    showLoading();
            //},
            success : function(res){ 
            	hideLoading();
                if(res.status == '00'){
                    mui.alert(res.message, '', '', function(){
                        // mui.init({ swipeBack:true });
                        WeixinJSBridge.call('closeWindow');
                    });
                }else{
                    mui.alert(res.message);
                }
            },
            error : function(){
                hideLoading();
            }
        });


    });

	//提交按钮
	$('#submitbtn').on('click',function(){
		//console.log(submitData);
		var flag = true;
        //pgData数据必填校验，萌萌写的，不做过多修改
		$.each(keys,function(i,item){
			if(!submitData[item]||!submitData[item]['id']||!submitData[item]['name']){
                //var buildindid = 0;
                //if(typeof(submitData['ban']) != 'undefined'){
                //    //楼栋类型
                //    buildindid = submitData['ban']['id'];
                //}
                //if(buildindid == '999999' && keys[i] == 'roomno'){
                //
                //}else{
                //    var tip = keyCns[i];
                //    mui.alert(tip+'是必填项');
                //    flag = false;
                //    return false;
                //}
                //if(buildindid != '999999'){
                //    //如果楼栋没有选择其他，需要验证房号必填
                //    var tip = keyCns[i];
                //    mui.alert(tip+'是必填项');
                //    flag = false;
                //    return false;
                //}else{
                //    if(keys[i] != 'roomno'){
                //        var tip = keyCns[i];
                //        mui.alert(tip+'是必填项');
                //        flag = false;
                //        return false;
                //    }
                //}
                if(keys[i] != 'roomno' && keys[i] != 'roomarea'){
                    var tip = keyCns[i];
                    mui.alert(tip+'是必填项');
                    flag = false;
                    return false;
                }
			}
		});
		if (flag) {
			//alert(flag);
			$("#house_info").val(JSON.stringify(submitData));
            console.log(JSON.stringify(submitData));
            //附加信息
            var data = {};
            data.ban = $('#ban_address').val(); //楼栋地址
            data.chaoxiang = $('#orientation').val(); //朝向
            data.total_floor = $('#total_floor').val(); //总楼层
            data.in_floor = $('#in_floor').val(); //所在楼层
            data.new_roomno = $('#new_room_no').val(); //房间号
            data.room_area = $('#pg_roomarea').val(); //面积
            if(submitData['ban']['id'] == '999999'){
                //如果手动输入
                if(data.ban == ''){
                    mui.alert('楼栋单元必填！');
                    return false;
                }else if(data.new_roomno == '' || data.new_roomno == '0'){
                    mui.alert('房间号必填！');
                    return false;
                }else if(data.chaoxiang == '0'){
                    mui.alert('请选择朝向！');
                    return false;
                }else if(data.total_floor == '' || data.total_floor == '0'){
                    mui.alert('总楼层必填！');
                    return false;
                }else if(data.in_floor == '' || data.in_floor == '0'){
                    mui.alert('所在楼层必填！');
                    return false;
                }else if(data.room_area == '' || data.room_area == '0'){
                    mui.alert('面积必填！');
                    return false;
                }
            }else{
                //如果选择了楼栋
                if( ! submitData['roomno']){
                    mui.alert('房号必填！');
                    return false;
                }else{
                    if(submitData['roomno']['id'] == '999999'){
                        //如果手动输入房间号
                        if(data.new_roomno == '' || data.new_roomno == '0'){
                            mui.alert('房间号必填！');
                            return false;
                        }else if(data.chaoxiang == '0'){
                            mui.alert('请选择朝向！');
                            return false;
                        }else if(data.total_floor == '' || data.total_floor == '0'){
                            mui.alert('总楼层必填！');
                            return false;
                        }else if(data.in_floor == '' || data.in_floor == '0'){
                            mui.alert('所在楼层必填！');
                            return false;
                        }else if(data.room_area == '' || data.room_area == '0'){
                            mui.alert('面积必填！');
                            return false;
                        }
                    }else{
                        if(data.room_area == '' || data.room_area == '0'){
                            mui.alert('面积必填！');
                            return false;
                        }
                    }
                }
            }
            $("#ext_info").val(JSON.stringify(data));
            console.log(JSON.stringify(data));
            showLoading();
        	$("#myForm").submit();
		}
		
	})
	
	
/***************************方法*****************************/
	//加载表单元素
	function loadSelect(){
		var $e = $(this),
			_val = $e.val(),		//值
			_name = $e.find('option:selected').html(),      //txt值
			_bid = $e.find('option:selected').data('buildingid'),//如果有此值回加进去，没有也无所谓
			_key = $e.data('key'),  //关键字，keys中的
			_nextIndex = keys.indexOf(_key)+1;//下一个select索引
		setSubmitData(_key,_val,_name,_bid);	
		if(_nextIndex>=keys.length)	//当下一个索引值是最后一个就不再进行查询
			return false;
		var $next = $('#pg_'+keys[_nextIndex]);//
		getData(_nextIndex,_val,$next);
	}
	function getData(nextIndex,val,$next){
		var key = nextIndex>=1?keys[nextIndex-1]:keys[0];
		var param = {};
		if(nextIndex<3){
			param[key] = val;
            if(nextIndex==0){
                param['province'] = $('#pg_province').find('option:selected').val();
            }else{
                param['city'] = $('#pg_city').find("option:selected").text();
            }
		}else{
			param['city'] = $('#pg_city').find("option:selected").text();
			param['area'] = $('#pg_area').find("option:selected").text();
			param['village'] = '全部';
			param['villageCode'] = $('#pg_village').data('code');
			param['banCode'] = $('#pg_ban').find("option:selected").data('buildingid');
			param['unitCode'] = $('#pg_ban').val();
			param['roomCode'] = $('#pg_roomno').val(); 
		}

		/*paramsArr[nextIndex].forEach(function(item){
			var $ee = $('#pg_'+item);console.log($ee)
			if($ee[0].tagName == 'SELECT')
				param[item] = $ee.text();
			else
				param[item] = $ee.val();
		})*/
		//console.log(param);
        if(param['banCode'] == '999999'){
            //如果楼栋号选择其他
            $('.hide_div').show();
            $('#room_div').hide();
            //面积可填写
            //手动输入面积
            hideMask.call($('#pg_roomarea'));
        }else{
            //如果没有选择其他，则手动输入区域隐藏
            $('.hide_div').hide();
            $('#room_div').show();
            $('#pg_roomarea').val('');
            if(param['roomCode'] == '999999'){
                //如果房间号选择其他，则需要手动输入房间号
                $('.hide_div').show();
                //手动输入的楼栋地址隐藏
                $('#ban_div').hide();
                //手动输入面积
                hideMask.call($('#pg_roomarea'));
            }else{
                //手动输入房间号清空
                $('.new_room_div').hide();
                $('#new_room_no').val('');
                $('#pg_roomarea').val('');
                if(!controller[nextIndex]){
                    ajaxCallBack({success:true,data:{}},nextIndex,$next);
                }
                $.ajax({
                    type:'post',
                    url:mainHref + controller[nextIndex],
                    data:param,
                    dataType:'JSON',
                    async:true,
                    success:function(msg){
                        ajaxCallBack(msg,nextIndex,$next);
                    }
                });
            }
        }

	}
	function ajaxCallBack(msg,nextIndex,$next){
		$next = $next || $('#pg_'+keys[0]);
        //console.log(keys);
		if(!msg || msg.success==false || nextIndex == keys.length){
        //if(!msg.success&&nextIndex == keys.length-1){
			if(keys[nextIndex]=='roomarea'){
                hideMask.call($('#pg_'+keys[nextIndex]));
                mui.alert('暂无该房号的面积，请手动输入');
                return false;
            }
			var _tip = keyCns[nextIndex];

            if(keys[nextIndex]=='ban'){
                //如果没有小区的楼栋数据
                mui.alert('暂无该'+keyCns[nextIndex-1]+'的'+keyCns[nextIndex]+'数据，请手动输入');
                manualBanInfo();
            }else{
                mui.alert('暂无该'+keyCns[nextIndex-1]+'的'+keyCns[nextIndex]+'数据');
            }
            return false;

		}
        if(keys[nextIndex]=='ban'){
            $('#sel_ban_div').show();
        }

		clearForm(nextIndex);
		hideMask.call($next);
        if($next.attr('id') == 'pg_city'){
            hideMask.call($('#pg_village'));//当默认城市出来时，将小区遮罩层去掉
            //$next = $('#pg_village');
            setTimeout(function(){
                ajaxCallBack({success:true,data:{}},1,$('#pg_village'));//当默认城市出来时，模拟请求一下，让next值校正
                setSubmitData('city',$('#pg_city').val(),$('#pg_city').find("option:selected").text());
            },0)
        }
		createDom.call($next,$next.data('key'),$next.data('type'),msg.data);
	}
	
	//当选择后无数据，清除此表单以后所有表单的数据数据
	function clearForm(nextIndex){
		var tempKeys = [];
		$.extend(tempKeys,keys);
		tempKeys.splice(nextIndex).forEach(function(item,i){
			var $pg = $('#pg_'+item);
			$pg.html('').val('');
			showMask.call($pg);
			if($pg.data('type')=='txt'){
				$pg.removeAttr('disabled');
			}
		});
	}
	
	//设置提交数据值(bid是作为可选的，因为楼栋select项需要加入一个buildingId)
	function setSubmitData(k,id,name,bid){
		submitData[k] = {};
		submitData[k]['id'] = id;
		submitData[k]['name'] = name;
		if(bid)
			submitData[k]['buildingId'] = bid;
	}
	//工厂根据type创建不同的节点
	function createDom(key,type,obj){
		switch(type){
			case 'select':createOptions.call(this,key,obj);break;
			case 'auto':createAutoComplete.call(this,key,obj);break;
			case 'txt':createInputValue.call(this,key,obj);break;
		}
	}
	//创建select的options
	function createOptions(key,list){
		var _html = '';
        if(key=='city') {
            list.forEach(function(item,i){
                _html+='<option value="'+item.id+'">'+item.name+'</option>';
            });
            //_html+='<option value="'+333+'">'+'测试城市'+'</option>';
        }
		else if(key=='ban') {	//当是选择楼栋的时候应该加入data-buildingId
            _html = '<option value="-1" data-buildingid="-1">请选择</option>';
            list.forEach(function (item, i) {
                _html += '<option value="' + item.unitId + '" data-buildingid="' + item.id + '">' + item.name + '</option>';
            });
            //如果是楼栋，加入其他选项
            _html += '<option value="999999" data-buildingid="999999">其他</option>';
        }else{
            _html = '<option value="-1">请选择</option>';
			list.forEach(function(item,i){
				_html+='<option value="'+item.id+'">'+item.name+'</option>';
			});
		}
        if(key=='roomno'){
            _html+='<option value="999999">其他</option>';
        }

		this.html(_html);
	}
	//创建自动搜索
	function createAutoComplete(key,list){
		var that = this;
		var ac = getAutoComplete(that);//获取搜索框的实例，如果存在只需要替换数据就好
		if(!!ac){
            ac = null;
			//ac.setData(list);//重新设置数据
			//return false;
		}
        //$('#pg_area').val();
        if(that.data('plugin_autoComplete')){
            that.data('plugin_autoComplete').resetAjaxParms({city:$('#pg_city').find("option:selected").text()});
            return false;
        }
		that.autoComplete({
			searchType:'ajax',//local
			top:49,
			//data:list,
            url:'/ldd/index/getvillagelist',//7.22修改
            ajaxParms:{city:$('#pg_city').find("option:selected").text()},//7.22修改
			myClass:'ame_autocomplete',
            //btmBtnText:'切换到地址搜索',

            searchBtn:$('#dzsearch'),
            needClear:false,
            searchBtnUrl:'/ldd/index/queryvillage',
			ontxtinput:function(keyword,that){
				if(!keyword){
					setSubmitData(key,'','');
					clearForm(keys.indexOf(key)+1)
				}
			},
            onsuccess: function($layer,opts){
                var $acinfo = $('.ac_infotip');
                if(!$acinfo.length)
                    $acinfo = $('<div class="ac_infotip"></div>').appendTo($layer);
                if(~opts.url.indexOf('query')){

                    $acinfo.html('<a href="/ldd/loan/mevaluate/type/1">如果没有您想要的信息，请点击切换<span class="pg_blue">人工评估</span></a>');
                }
                else {
                    $acinfo.html('<a>如果没有您想要的信息，请切换至地址查询</a>');
                }
            },
			onselect:function(id,txt){
				var _nextIndex = keys.indexOf(key)+1;
				if(_nextIndex>=keys.length){
					
					return false;
				}
				if(id == (!!submitData[key]?submitData[key]['id']:'-2')){	//判断上一次点击的是不是一样，一样的话不用请求新数据
					return false;
				}
				$('#pg_village').val(txt).data('code',id);
				setSubmitData(key,id,txt);	
				var $next = $('#pg_'+keys[_nextIndex]);
				getData(_nextIndex,id,$next);
			},
			onnotequal:function(){
				setSubmitData(key,'','');
				clearForm(keys.indexOf(key)+1)
			},
            onnodata:function($layer,opts){
                var key = $('#pg_village').data('key');
                setSubmitData(key,'','');
                clearForm(keys.indexOf(key)+1);
                //代表地址搜索
                if(~opts.url.indexOf('query'))
                    $layer.html('<div class="ac_infotip"><a href="/ldd/loan/mevaluate/type/1">如果没有您想要的信息，请点击切换<span class="pg_blue">人工评估</span></a></div>');
                else{
                    $layer.html('<div class="ac_infotip"><a>如果没有您想要的信息，请切换至地址查询</a></div>');
                }
            }/*,
            onBtmBtnClick:function(){
                var addrHtml = '<div class="mui-input-row" id="">\
                    <label>楼栋</label>\
                    <input type="text" id="" data-key="" data-type="auto" />\
                </div>';
                that.after(addrHtml);
            }*/
		});
        /*$('#pg_village2').autoComplete({
            searchType:'ajax',//local
            top:49,
            url:'/ldd/index/queryvillage',//7.22修改
            ajaxParms:{city:$('#pg_city').find("option:selected").text()},//7.22修改
            myClass:'ame_autocomplete',
            needInputChange:false,
            searchBtn:$('#dzsearch'),
            needClear:false,
            ontxtinput:function(keyword){

            },
            onselect:function(id,txt){
                var key = $('#pg_village').data('key');
                var _nextIndex = keys.indexOf(key)+1;
                if(_nextIndex>=keys.length){
                    return false;
                }
                if(id == (!!submitData[key]?submitData[key]['id']:'-2')){	//判断上一次点击的是不是一样，一样的话不用请求新数据
                    return false;
                }
                $('#pg_village').val(txt).data('code',id);
                setSubmitData(key,id,txt);
                var $next = $('#pg_'+keys[_nextIndex]);
                getData(_nextIndex,id,$next);
            },
            onnodata:function(){
                var key = $('#pg_village').data('key');
                setSubmitData(key,'','');
                clearForm(keys.indexOf(key)+1);

                //var btnArray = ['是', '否'];
                //mui.confirm('未查找到小区，是否切换到手动输入？', '', btnArray, function(e) {
                //    if (e.index == 0) {
                //        alert(1)
                //    } else {
                //
                //    }
                //});
            }
        });*/

        document.querySelector('.mui-switch').addEventListener('toggle', function(event) {
            if(event.detail.isActive){
                $(event.target).find('.mui-switch-handle').text('地址');
                that.data('plugin_autoComplete').resetAjaxUrl('/ldd/index/queryvillage');
            }else{
                $(event.target).find('.mui-switch-handle').text('小区');
                that.data('plugin_autoComplete').resetAjaxUrl('/ldd/index/getvillagelist');
            }
        });
	}
	//创建普通输入框加载值
	function createInputValue(key,data){
		if(key == 'roomarea'){
			if(data[0].fArea>0){
				//this.attr('disabled',true);
                this.val(data[0].fArea);
			}else if(data[0].fArea == '' || data[0].fArea == '0'){
                mui.alert('暂无该房号的面积，请手动输入');
            }
			this.focus();
			setSubmitData(key,data[0].fArea,data[0].fArea);
		}else{
			this.val(data[0].name).attr('disabled',true);
			setSubmitData(key,data[0].name,data[0].name);
		}
	}
	//添加遮罩层
	function showMask(){
		this.parents('.mui-input-row').addClass('show');//.find('.ame_row_mask');
	}
	//移除遮罩层
	function hideMask(){
		this.parents('.mui-input-row').removeClass('show');//.find('.ame_row_mask');
	}
	//获取自动搜索的插件实例
	function getAutoComplete(e){
		return e.data('plugin_autoComplete');
	}

    //手动输入楼栋信息
    function manualBanInfo(){
        //楼栋号选择其它
        $('#sel_ban_div').hide();
        var _html = '<option value="999999" data-buildingid="999999" selected>其他</option>';
        $('#pg_ban').html(_html);
        setSubmitData('ban','999999','其他','999999');
        //如果房间号选择其他，则需要手动输入房间号
        $('.hide_div').show();
        //手动输入的房号隐藏
        $('#room_div').hide();
        //手动输入面积
        hideMask.call($('#pg_roomarea'));
    }

    //新加入选择小区和地址切换
    /*$('#choose_xq').on('change',function(){
        var $this = $(this);
        if($this.prop("checked")){
            $this.parents('.mui-input-row').removeClass('show');
            $('#choose_dz').parents('.mui-input-row').addClass('show');
            $('#pg_village').focus();
            //$('#pg_village2').val('');
        }
    })
    $('#choose_dz').on('change',function(){
        var $this = $(this);
        if($this.prop("checked")){
            $this.parents('.mui-input-row').removeClass('show');
            $('#choose_xq').parents('.mui-input-row').addClass('show');
            $('#pg_village2').focus();
        }
    })*/

    mui("#slider").slider({
        interval: 2000
    });

})();
