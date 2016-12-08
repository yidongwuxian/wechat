<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>

            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">测试</a>
                </li>

                <li>
                    <a href="#">测试</a>
                </li>
                <li class="active">测试</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    测试
                </h1>
            </div><!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <form class="form-horizontal" id="form">

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right"> appid </label>

                            <div class="col-sm-10">
                                <input type="text" id="wechat" name="appid" class="col-xs-10 col-sm-3" value="11"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right"> type </label>

                            <div class="col-sm-10">
                                <input type="text" id="title" name="type" class="col-xs-10 col-sm-3" value="7"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right"> encrypt </label>

                            <div class="col-sm-10">
                                <input type="text" id="encrypt" name="encrypt" class="col-xs-10 col-sm-3" value="fd321bd2aa464e4c60910852aaf44be3"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right"> data </label>
                            <div class="col-sm-10">
                                <textarea id="data" name="data" rows="10" cols="60">{"uid":"oeTlvwNine6YyjZbfqt3YKNZwAq4","url":"http://www.baidu.com","first":"\u4e1a\u52a1\u7f16\u53f7\uff1aY1060011603210002","keyword1":"\u5f20\u4e09","keyword2":"\u7ed3\u679c\u5982\u4e0b","remark":"\u623f\u4ea7\u4fe1\u606f\uff1a\u5317\u4eac\u5e02\u4e30\u53f0\u533aXX\u5c0f\u533aXX\u53f7\u697cXX\u53f7\\n\u6700\u5927\u53ef\u8d37\u91d1\u989d\uff1a116\u4e07\\n\u4ee5\u5b9e\u9645\u52d8\u5bdf\u7ed3\u679c\u4e3a\u51c6"}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right"></label>
                            <div class="col-sm-10">
                                <a class="btn btn-info" id="sub">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    提交
                                </a>
                                <a class="btn" href="/admin/account">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    返回
                                </a>
                            </div>
                        </div>
                    </form>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
<script>
    $(function(){
        $('#sub').click(function(){
            $.ajax({
                type : "POST",
                url : "/ldd/api/sendtplmsgnew",
                data : $('#form').serialize(),
                dataType: "json",
                success : function(res){ //res:{1:成功}
                    if(res.result == '0'){
                        bootbox.alert(res.message,function(){
                            //window.location.href = '/admin/account/index';
                        });
                    }else{
                        bootbox.alert(res.message,function(){

                        });
                    }
                }
            });

        });
    });
</script>
