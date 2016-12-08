mui.init();//mui加载
(function(){
	
	var mainHref = '/ldd/index/';
	var keys = ['province','city','district','village','ban','roomno','roomarea'];//查询编号'area',
	var keyCns = ['省份','城市','区县','小区','楼栋','房号','面积'];//对应汉字'区域',
	var controller = ['queryCity','queryCity','queryCity','','getunitlist','getroomlist','getroominfo'];//对应请求路径'queryCity',
	var submitData = {};//需要提交的数据
	
/***************************事件*****************************/	
	//初始加载省份select
	/*getData({province: $('#pg_province').val()},'queryCity',$('#pg_city'),function(){
        getData({city: $('#pg_city').val()},'queryCity',$('#pg_area'));
    });*/
    getData({},'queryCity',$('#pg_province'));
	//select选择时候事件
   function getData(param,name,$this,callback){
       $.ajax({
           type:'post',
           url:mainHref + name,
           data:param,
           dataType:'JSON',
           async:true,
           success:function(msg){
               createOptions(msg.data,$this);
           }
       }).done(function(){
           if($.isFunction(callback)) {
               callback();
           }
       });
   }

    //创建select的options
    function createOptions(list,$this){
        if(list&&list.length){
            var _html = '<option value="-1">请选择</option>';
            list.forEach(function(item,i){
                _html+='<option value="'+item.id+'">'+item.name+'</option>';
            });
            $this.html(_html);
        }
    }
    $('#pg_province').on('change',function(){
        if($(this).val()>-1){
            getData({province: $('#pg_province').val()},'queryCity',$('#pg_city'));
            $('#pg_area').html('');
        }else{
            $('#pg_city').html('').val('-1');
            $('#pg_area').html('');
        }

    });
    $('#pg_city').on('change',function(){
        if($(this).val()>-1){
            getData({city: $('#pg_city').val()},'queryCity',$('#pg_area'));
        }else{
            $('#pg_area').html('')
        }

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
		mui.ajax("/ldd/loan/toapply", {
            type : "POST",
            data : data,
            dataType: "json",
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
