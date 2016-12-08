mui.init();//mui加载
(function(){
	var keys = ['province','city','area','village','ban','roomno','roomarea'];//查询编号
	var keyCns = ['省份','城市','区域','小区','楼栋','房号','面积'];//对应汉字
	var submitData = {};//需要提交的数据
	
/***************************事件*****************************/	
	//初始加载省份select
	getData();
	//select选择时候事件
	$('.com_form_content').on('change','select',function(){
		var $this = $(this);
		if($this.val()=='-1'){
			var _key = $this.data('key'),  //关键字，keys中的
				_nextIndex = keys.indexOf(_key)+1;//下一个select索引
			if(_nextIndex>=keys.length)	//当下一个索引值是最后一个就不再进行查询
				return false;
			clearForm(_nextIndex);
			return false;
		}
		loadSelect.call(this);
	});
	//input data-type="txt"的文本框写入事件，主要记录表单数据
    mui('.com_btm_btns').on('tap','#sub',function(){
        //判断必填项
        $.each(keys,function(i,item){
            if(!submitData[item]||!submitData[item]['id']||!submitData[item]['name']){
                var tip = keyCns[i];
                mui.alert(tip+'是必填项');
                return false;
            }
        });

        var data = {};
        data.cust_name = $("#cust_name").val();
        data.id_card = $('#id_card').val();
        data.mortgage_type = $('#sel_mortgage').val();
        data.house_type = $('#sel_house_type').val();
        data.apply_limit = $('#apply_limit').val();
        data.loan_term = $('#loan_term').val();
        data.house_info = submitData;
        console.log(data);

        $.ajax({
            type : "POST",
            url : "/ldd/loan/apply",
            data : data,
            dataType: "json",
            success : function(res){ //res:{1:成功}
                if(res.res == 1){
                    mui.alert(res.msg,'','',function(){
                        //alert('此处关闭微信页面');
                        WeixinJSBridge.call('closeWindow');
                    })
                }else{
                    mui.alert(res.msg);
                }
            }
        });
    });

	$('.com_form_content').on('input propertychange','input[data-type="txt"]',function(){
		var $this = $(this);
			_key = $this.data('key');
			_val = $this.val(),
			_name = _val;
		setSubmitData(_key,_val,_name);	
	});
	
	//提交按钮
	$('#submitbtn').on('click',function(){
		console.log(submitData);
		$.each(keys,function(i,item){
			if(!submitData[item]||!submitData[item]['id']||!submitData[item]['name']){
				var tip = keyCns[i];
				mui.alert(tip+'是必填项');
				return false;
			}
		})
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
		param[key] = val;
		$.ajax({
			type:'get',
			//url:'http://122.113.37.11:8080/bxloan/wxAssess/queryCity', //106
            url:'http://122.113.37.12:8080/bxloan/wxAssess/queryCity', //243
			data:param,
			dataType:'JSON',
			async:true,
			success:function(msg){
				ajaxCallBack(msg,nextIndex,$next);
			}
		});
	}
	function ajaxCallBack(msg,nextIndex,$next){
		$next = $next || $('#pg_'+keys[0]);
		if(!msg.success&&nextIndex == keys.length-1){
			if(keys[nextIndex]=='roomarea'){
                hideMask.call($('#pg_'+keys[nextIndex]));
                mui.alert('暂无该房号的面积，请手动输入');
                return false;
            }
			var _tip = keyCns[nextIndex];
			mui.alert('暂无该'+keyCns[nextIndex-1]+'的'+keyCns[nextIndex]+'数据');
			return false;
		}
		clearForm(nextIndex);
		hideMask.call($next);
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
		var _html = '<option value="-1">请选择</option>';
		if(key=='ban'){	//当是选择楼栋的时候应该加入data-buildingId
			list.forEach(function(item,i){
				_html+='<option value="'+item.id+'" data-buildingid="'+item.buildingId+'">'+item.name+'</option>';
			});
		}else{
			list.forEach(function(item,i){
				_html+='<option value="'+item.id+'">'+item.name+'</option>';
			});
		}
		this.html(_html);
	}
	//创建自动搜索
	function createAutoComplete(key,list){
		var that = this;
		var ac = getAutoComplete(that);//获取搜索框的实例，如果存在只需要替换数据就好
		if(!!ac){
			ac.setData(list);//重新设置数据
			return false;
		}
		that.autoComplete({
			searchType:'local',
			top:49,
			data:list,
			myClass:'ame_autocomplete',
			ontxtinput:function(keyword){
				if(!keyword){
					setSubmitData(key,'','');
					clearForm(keys.indexOf(key)+1)
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
				setSubmitData(key,id,txt);	
				var $next = $('#pg_'+keys[_nextIndex]);
				getData(_nextIndex,id,$next);
			},
			onnotequal:function(){
				setSubmitData(key,'','');
				clearForm(keys.indexOf(key)+1)
			}
		});
	}
	//创建普通输入框加载值
	function createInputValue(key,data){
		this.val(data[0].name).attr('disabled',true);
		setSubmitData(key,data[0].name,data[0].name);	
	}
	//添加遮罩层
	function showMask(){
		this.parents('.mui-input-row').find('.ame_row_mask').addClass('show');
	}
	//移除遮罩层
	function hideMask(){
		this.parents('.mui-input-row').find('.ame_row_mask').removeClass('show');
	}
	//获取自动搜索的插件实例
	function getAutoComplete(e){
		return e.data('plugin_autoComplete');
	}
})();
