;(function(){
    var userProvince = '';
    //初始化页面时候根据url地址切换评估类别
    var evType = GetQueryString('type');
    if(evType == 1){
        $('.od_nav').find('.select1').addClass('mui-active');
        $('.od_nav').find('.select2').removeClass('mui-active');
    }else{
        evType = 2;
    }
    $('.od_nav').find('.select2').on('click', function(){
        $(this).addClass('mui-active');
        $('.od_nav').find('.select1').removeClass('mui-active');
        evType = 2;
    })

    function GetQueryString(name){
         var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
         var r = window.location.search.substr(1).match(reg);
         if(r!=null)return  unescape(r[2]); return null;
    }
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


    //执行方法
    initByLoginInfo();
    loadOrientation();


    var ld = new LD($('#ld-content'),{
        url: [
            '/ldd/information/getprovince',
            '/ldd/information/getcity',
            '/ldd/information/getarea'
        ],
        urlType:'post',
        dataKeys: [
            ['code', 'province'],
            ['code', 'city'],
            ['code', 'area']
        ],
        defaultSelected: {
            '1': userProvince
        }
    });
    
    // 提交表单流程
    $('#submit_btn').on('click',function(){
        var postData = {};
        postData.type = evType;
        postData.city = ld.paramsCache['2'].text;
        postData.district = ld.paramsCache['3'].text;
        postData.address = $('#ext_txt_village').val();
        postData.banName = $('#ext_txt_unit').val();
        postData.roomNo = $('#ext_txt_roomno').val();
        postData.area = $('#ext_txt_roomarea').val();
        postData.forward = $('#ext_sel_ori').val();
        postData.totalFloor = $('#ext_txt_totalfloor').val();
        postData.floor = $('#ext_txt_localfloor').val();
        postData.isFaceStreet = $('#ext_sel_frontage').val(); 
        
        $.ajax({
            type: 'POST',
            url: '/ldd/loan/mevaluation',
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
                    mui.alert(data.message);
                }
            }
        })

    })
})()