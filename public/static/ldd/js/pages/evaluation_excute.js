;(function(){
    var userProvince = '';

    //获取用户
    function initByLoginInfo(){
        $.ajax({
            type: 'POST',
            url: '/ldd/user/info',
            dataType: 'JSON',
            async: false,
            success: function(data){
                if(data.extension.channelType == 1){
                    userProvince = data.extension.provinceCode;
                    $('#default_wrap').find('select[ld-index="1"]').attr('disabled',true);
                }
            }
        })
    }
    //加载房屋朝向select
    function loadOrientation(){
        $.ajax({
            type: 'POST',
            url: '/ldd/information/getforward',
            dataType: 'JSON',
            success: function(data){
                if(data && data.extension){
                    var html = '';
                    for(var k in data.extension){
                        html += '<option value="'+ k +'">'+ data.extension[k] +'</option>';
                    }
                    $('#ext_sel_ori').html(html); 
                }
                
            }
        })
    }
    // 当数据正确加载完的时候要执行隐藏手动输入
    function loadDataCallBack(index){
        //当为最后一个输入面积时，不进行此方法，因为面积在联动ajax是成功获取到数据的相当于，只不过面积没有。
        if(index == 6)
            return false;
        $('#nodata_wrap').children('div').hide();
        $('#default_wrap').find('.mui-input-row').show();
    }
    // 当没有数据的时候要执行显示手动输入
    function nodataCallBack(index, msgInfo, showCurrent){
        if(!showCurrent)
            mui.alert(msgInfo || '未查询到数据，请手动输入');
        $('#default_wrap').find('[ld-index]').each(function(i,e){
            var _index = $(e).attr('ld-index');
            if(_index && index <= parseInt(_index)){
                if(index == parseInt(_index) && showCurrent)
                    $(e).parent('div').show();
                else
                    $(e).parent('div').hide();
            }else{
                $(e).parent('div').show();
            }
        });
        $('#nodata_wrap').children('div').each(function(i,e){
            var _index = $(e).attr('d-index');
            if(_index && index > parseFloat(_index)){
                $(e).hide();
            }else{
                $(e).show();
            }
        });
        $('#ext_txt_totalfloor').val('');
        $('#ext_txt_localfloor').val('');
        $('#ext_txt_roomno').val('');
        $('#ext_txt_roomarea').val('');
        $('#ext_txt_unit').val('');
        $('#ext_txt_roomarea').val('');
    }

    //执行方法
    initByLoginInfo();
    loadOrientation();
    //设置自动搜索的联动关联
    LD.setLoadDom({
        'autocomplete': {
            invoke: function(elem, $e, data, keys, paramsObj){
                if($e.data('plugin_autoComplete')){
                    $e.data('plugin_autoComplete').resetAjaxParms(paramsObj);
                    return false;
                }
                $e.autoComplete({
                    top:49,
                    url:'/ldd/information/getprojectlist',//7.22修改
                    ajaxParms:paramsObj,//7.22修改
                    myClass:'ame_autocomplete',
                    needClear:false,
                    ontxtinput:function(keyword, that){
                        
                    },
                    onsuccess: function($layer, opts){
                        var $acinfo = $('.auto_infotip');
                        if(!$acinfo.length){
                            $acinfo = $('<div class="auto_infotip"></div>').appendTo($layer);
                            $acinfo.html('如未查询到，请切换地址查询<a href="/ldd/loan/mevaluation?type=1">或者切换至<span class="pg_blue">人工评估</span></a>');
                        }
                    },
                    onselect:function(id, txt){
                        var obj = {};
                        obj.code = id;
                        obj.name = txt;
                        //触发获取到插件选中元素事件，传入当前选中的项的一些缓存信息
                        elem.trigger('getValue', [$e, id, obj, keys]);
                    },
                    onnotequal:function(){
                        
                    },
                    onnodata:function($layer,opts){
                        $layer.html('<div class="auto_infotip">如未查询到，请使用地址查询，<a href="/ldd/loan/mevaluation?type=1">或切换至<span class="pg_blue">人工评估</span></a></div>');
                    }
                });
                //切换自动搜索选项
                document.querySelector('.mui-switch').addEventListener('toggle', function(event) {
                    if(event.detail.isActive){
                        $(event.target).find('.mui-switch-handle').text('地址');
                        $e.data('plugin_autoComplete').resetAjaxUrl('/ldd/information/getprojectlistbykeyword');
                    }else{
                        $(event.target).find('.mui-switch-handle').text('小区');
                        $e.data('plugin_autoComplete').resetAjaxUrl('/ldd/information/getprojectlist');
                    }
                });
            },
            remove: function($e){
                $e.val('');
            }
        }
    });
    var ld = new LD($('#ld-content'),{
        url: [
            '/ldd/information/getprovince',
            '/ldd/information/getcity',
            '',
            '/ldd/information/getbanunitlist',
            '/ldd/information/getroomlist',
            '/ldd/information/getroominfobyid'
        ],
        urlType:'post',
        dataKeys: [
            ['code', 'province'],
            ['code', 'city'],
            ['code', 'name'],
            ['sBanCode', 'sBanName', 'sUnitCode'],
            ['sRoomID', 'sRoomNo'],
            ['fArea', 'fArea']
        ],
        defaultSelected: {
            '1': userProvince
        },
        extendSelectItem: {
            '4': [{sBanCode: -1, sBanName: '请选择所在楼栋'}, {sBanCode: 999999, sBanName: '其他'}],
            '5': [{sRoomID: -1, sRoomNo: '请选择所属房间号'}, {sRoomID: 999999, sRoomNo: '其他'}]
        },
        loadDataCallBack: loadDataCallBack,
        noDataCallBack: nodataCallBack
    });
    
    // 提交表单流程
    $('#submit_btn').on('click',function(){
        var postData = {};
        var errTips = {
            city: '请选择城市',
            newCode: '楼盘（小区）信息有误！',
            banCode: '楼栋单元信息有误！',
            //unitCode: '选择城市',
            roomId: '房间号信息有误！',
            area: '面积必填或输入有误！',
            forward: '朝向选择有误！',
            totalFloor: '总楼层输入有误！',
            floor: '所在楼层输入有误！',
            roomNo: '请将房间号补充完整！',
            banName: '请将楼栋单元补充完整！'
        } 
        postData.city = ld.paramsCache['2'].text;
        postData.newCode = ld.paramsCache['3']? ld.paramsCache['3'].code: '-1';
        postData.banCode = ld.paramsCache['4']? ld.paramsCache['4'].code: '-1';
        postData.unitCode = ld.paramsCache['4']? ld.paramsCache['4'].sUnitCode: '-1';
        postData.roomId = ld.paramsCache['5']? ld.paramsCache['5'].code: '-1';
        
        //设置一下unitcode
        postData.unitCode = postData.banCode == '999999'? '999999': postData.unitCode;
     
        if(postData.banCode=='999999' || postData.unitCode=='999999'){
            postData.banName = $('#ext_txt_unit').val();
        }
        if(postData.banCode=='999999' || postData.unitCode=='999999' || postData.roomId=='999999'){
            postData.roomNo = $('#ext_txt_roomno').val();
            postData.forward = $('#ext_sel_ori').val();
            postData.totalFloor = $('#ext_txt_totalfloor').val();
            postData.floor = $('#ext_txt_localfloor').val();
            postData.area = $('#ext_txt_roomarea').val();
        }else{
            postData.area = ld.paramsCache['6']? ld.paramsCache['6'].text: '';
        }
        if(!postData.area){
            postData.area = $('#ext_txt_roomarea').val();
        }
        //空校验
        var errorStatus = false;
        $.each(postData, function(i, item){
            if(postData[i] == -1 || !$.trim(item)){
                mui.alert(errTips[i]);
                errorStatus = true;
                return false;
            }
        })
        if(errorStatus){
            return false;
        }
        $.ajax({
            type: 'POST',
            url: '/ldd/loan/evaluation',
            dataType: 'JSON',
            data: postData,
            async: true,
            success: function(data){
                if(data.result == 1){
                    if(data.extension)
                        window.location.href = data.extension;
                    else
                        WeixinJSBridge.call('closeWindow');
                }else{
                    if(~data.message.indexOf('请进行人工评估')){
                        var btnArray = ['取消', '确定'];
                        mui.confirm(data.message, '', btnArray, function(e) {
                            if (e.index == 1) {
                                window.location.href = '/ldd/loan/mevaluation?type=1';
                            }
                        })
                    }else{
                        mui.alert(data.message);
                    }
                }
            }
        })

    })
})()