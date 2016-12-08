mui.init();//mui加载
(function(){
    //搜索，加载数据
    var localDataAll = [];
    loadDataAll();

    $('#searchAll').on('input propertychange',function(){
        var keyword = $(this).val();
        loadDataAll(keyword);
    });

    //加载数据
    function loadDataAll(keyword){
        if(!localDataAll[0]){//如果不存在数据
            $.ajax({url:'/ldd/loan/getManageloanlist'})
                .done(function(data){
                    if(data != ''){
                        localDataAll = data.split('|');
                        getSerchListAll.call(localDataAll);//第一次加载全部
                    }else{
                        $("#loadingAll").html("没有数据");
                    }
                })
                .error(function(){
                    mui.alert('数据加载错误');
                })
            return false;
        }
        getSerchListAll.call(localDataAll,keyword,searchFilterAll);//搜索
    }

    //定义搜索过滤器
    function searchFilterAll(index,item,key){
        console.log(item.substring(0,item.length-4));
        //console.log(item.length);
        return new RegExp(key).test(item.substring(0,item.length-4));
    }

    //获取搜索列表
    //callback是搜索过滤器，参数为当前集合索引，要比较的关键字，集合中当前项，返回true代表将此项加入到搜索结果中
    function getSerchListAll(keyword,callback){
        if(!keyword){
            createHtml(this);
            return false;
        }
        var result = [];
        this.forEach(function(item,i){
            if(callback(i,item,keyword))
                result.push(item);
        })
        createHtml(result);
    }
    //创建列表
    function createHtml(list){
        var _html = '';
        list.forEach(function(item,i){
            var _item = item.split(',');
            _html += createItemAll(i+1,_item[0],_item[1],_item[2],_item[3],_item[4],_item[5]);
        })
        $('#loanListAll').html(_html);
    }
    //模板，返回一条记录html
    function createItemAll(index,no,name,amount,status,code,hasContract){
        var _templete = '<li class="mui-table-view-cell mui-media">\
            <a class="mui-navigate-right">\
            <div class="mui-media-body">'+ no +'</div>\
                <p>'+ name +'&nbsp;&nbsp;'+ amount +'&nbsp;&nbsp;';

        var code_stype = code == 4 ? 'font_danger' : 'font_ok';

        _templete += '<span class="'+ code_stype +'">'+ status +'</span></p>\
            <div class="btns">';

        if(hasContract == 1){
            _templete += '<button class="mui-btn mui-btn-green contract" onclick="contract(\''+ no +'\')">合同</button>&nbsp;&nbsp;';
        }

        _templete += '<button class="mui-btn mui-btn-success preview" onclick="preview(\''+ no +'\')">查看</button>\
            </div>\
            </a>\
            </li>';
        return  _templete;
    }

})();

function contract(id){
    window.location.href = '/ldd/loan/contractdetail/projectNo/' + id;
}
function preview(id){
    window.location.href = '/ldd/loan/orderdetail/projectNo/' + id;
}