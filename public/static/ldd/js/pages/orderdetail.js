   function GetQueryString(name) {
       var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
       var r = window.location.search.substr(1).match(reg);
       if (r != null) return unescape(r[2]);
       return null;
   }
   var bianhao = GetQueryString("bianhao");
   console.log(bianhao)
   $.ajax({
       type: 'post',
       data: {
           projectNo: bianhao
       },
       url: '/ldd/loan/orderdetail',
       dataType: 'JSON',
       async: true,
       success: function (vals) {
           console.log(vals)
           var item = vals.extension.projectStatus;
           var indexs = item.indexOf("|");
           var statustext = item.slice(0, indexs)
           $(".or_derail_item").eq(vals.extension.stage - 1).addClass("checkeddtype").siblings().removeClass("checkeddtype");
           var li = '<li class="or_derail_item"><span class="or_der_list"></span><span class="yewuname">业务编号</span><span class="yewuxinxi">' + vals.extension.projectNo + '</span></li><li class="or_derail_item"><span class="or_der_list"></span><span class="yewuname">申请人</span><span class="yewuxinxi">' + vals.extension.custName + '</span></li><li class="or_derail_item"><span class="or_der_list"></span> <span class="yewuname">申请金额</span><span class="yewuxinxi yewuxin">' + vals.extension.enableLoanMoney + '</span></li><li class="or_derail_item"><span class="or_der_list"></span><span class="yewuname">提交订单时间</span><span class="yewuxinxi yewuxin">' + vals.extension.createTime + '</span></li><li class="or_derail_item"><span class="or_der_list"></span><span class="yewuname">业务状态</span><span class="yewuxinxi yewuyell">' + statustext + '</span></li>';
           $(".or_derail_list").html(li);
           var btns = ' <button class="or_derail_button" id="jindiao"><a href="/ldd/loan/appointtime/projectNo/' + vals.extension.projectNo + '">预约尽调</a></button><button class="or_derail_button" id="xiugai"><a href="/ldd/loan/appointtime/projectNo/' + vals.extension.projectNo + '">修改尽调</a></button>';
           $("#or_derail_btn").html(btns);
           var jindiao = $("#jindiao"),
               xiugai = $("#xiugai");
           if (vals.extension.canAppoint == 1 && vals.extension.canModifyAppoint == 1) {
               jindiao.show();
               xiugai.show();
           } else if (vals.extension.canAppoint == 1) {
               jindiao.show();

           } else if (vals.extension.canModifyAppoint == 1) {
               xiugai.show();

           }
       }

   })