$(function () {
    $.ajax({
        type:'post',
        url: '/ldd/loan/evaluationlist',
        dataType:'JSON',
        async:true,
        success:function(data){
            if(data){
                var newJson = data.extension;
                var tab = "<ul class='mui-table-view info_cn'>";
                $.each(newJson,function(id, item){
                    var status = item.status;
                    var xiugai = '';
                    var tiaoUrl = ' /ldd/loan/detail';
                    if(status == 0){
                        status =  'ldd-status st-blue';
                        xiugai = "";
                    }else if(status == 1){
                        status = 'ldd-status st-green';
                        xiugai = "<div class='mui-slider-right mui-disabled'><a class='mui-btn mui-btn-blue' href='"+tiaoUrl+"?id="+item.id+" '>修改</a></div>";
                    }else if(status == 2){
                        status = 'ldd-status';
                    }else if(status == 3){
                        status = 'ldd-status st-pink';
                        xiugai = "<div class='mui-slider-right mui-disabled'><a class='mui-btn mui-btn-blue' href='"+tiaoUrl+"?id="+item.id+" '>修改</a></div>";
                    }
                    var eval_time = formatTime.format(item.evaluation_time,'yyyy-MM-dd h:m:s');

                    tab +="<li class='mui-table-view-cell mui-media info_li'>"+
                        "<div class='mui-slider-handle'><a href='javascript:;' data-type='"+item.attribute+"'>"+
                        "<div class='mui-col-sm-3 pm2'><input value='"+item.id+"' type='checkbox' class='arrowRight' name='itemCheck' /></div>"+
                        "<div class='mui-media-body mui-col-sm-8'>"+
                        "<div class='ldd-times'>"+eval_time+"</div>"+
                        "<div class='"+status+"'></div>" +
                        "<div class='ldd-de'>"+ item.address +"</div>"+
                        "</div>"+
                        "</a></div>"+
                        xiugai+
                        "</li>";
                });
                tab +="</ul>";
                $('#infoList').html(tab);

                $('.info_li').click(function() {
                    //评估完成
                    if($(this).find('div').hasClass('ldd-status st-green')){
                        if($("input.arrowCur").length >= 3) {
                            $(this).find('input').removeClass('arrowCur');
                        }else{
                           $(this).find('input').toggleClass('arrowCur');
                        }
                    } 

                    //评估中
                    if($(this).find('div').hasClass('ldd-status st-blue')){
                        mui.toast("评估中，不能选择！");
                    }
                    //评估完成但数据不完整
                    if($(this).find('div').hasClass('ldd-status st-pink')){
                        mui.toast("资料不全，请补全信息！");
                    }
                });
            }
        },
        error: function(e){
            console.log(e);
        }
    })
    $('#evallistBtn').click(function(){
        postApply();
    });
    function postApply(){
        var selArr=[];
        $("input.arrowCur").each(function(i,item){
            selArr.push($(this).val());
        })
        $.ajax({
            type:'post',
            url: '/ldd/loan/checkloan',
            data: {ids:selArr},
            dataType:"json",
            async:true,
            success:function(data){
                if(data.result == 1){
                    if( data.extension != null){
                        window.location.href = data.extension;
                    }
                }else{
                    mui.alert(data.message);
                }
            },
            error: function(e){
                console.log(e);
            }
        });
    }

    //滚动到底部 begin
     $('.evallist_box').css('position','fixed');
    var winH  = $(window).height();
    $(window).scroll(function(){
        var pageH = $(document).height();
        var scrollT = $(window).scrollTop();
        var diffY = (pageH - winH - scrollT) / winH;
        if( scrollT > 100){
            $('#eval-hd').css('position','fixed');
        }else{
            $('#eval-hd').css('position','static');
        }
        if( diffY < 0.12 ){
             $('.evallist_box').css('position','static');
        }
        else{
             $('.evallist_box').css('position','fixed');
        }

    });
    //滚动到底部 end
});
