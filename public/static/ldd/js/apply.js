

$('#sub').on('click', function() {
    var param = {};
        param['token'] = $('#token').val();
        param['custName'] = $('#cust_name').val();
        param['idCard'] = $('#id_card').val();
        param['loanLimit'] =$('#loan_term').val();
        param['amount'] = $('#apply_limit').val();

    var valid = $('.ame_info_form').valid();
    if(valid.flag){
        $.ajax({
                type:'post',
                url: '/ldd/loan/apply',
                data: param,
                dataType:'JSON',
                async:true,
                success:function(data){
                    if (data.result == 1) {
                        if( data.extension != null){
                            mui.alert(data.message,function(){
                                window.location.href = data.extension;
                            });
                        }
                    }
                    if (data.result == 0) {
                        mui.alert(data.message);
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
