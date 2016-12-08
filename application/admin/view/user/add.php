<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>

            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="/admin/index">主页</a>
                </li>

                <li>
                    <a href="/admin/user">管理员管理</a>
                </li>
                <li class="active">添加管理员</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    添加管理员
                </h1>
            </div><!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">

                    <form class="form-horizontal" id="validation-form">
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="user_name">用户名:</label>

                            <div class="col-xs-12 col-sm-4">
                                <div class="clearfix">
                                    <input type="text" id="user_name" name="user_name" class="col-xs-12 col-sm-5" />
                                </div>
                            </div>
                        </div>

                        <div class="space-2"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="password">密码:</label>
                            <div class="col-xs-12 col-sm-4">
                                <div class="clearfix">
                                    <input type="password" id="password" name="password" class="col-xs-12 col-sm-5" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="password2">确认密码:</label>
                            <div class="col-xs-12 col-sm-4">
                                <div class="clearfix">
                                    <input type="password" id="password2" name="password2" class="col-xs-12 col-sm-5" />
                                </div>
                            </div>
                        </div>

                        <div class="space-2"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="we_ids">所管理的公众号:</label>

                            <div class="col-xs-12 col-sm-4">
                                <div class="clearfix">
                                    <select multiple="multiple" class="select2 tag-input-style" data-placeholder="选择公众号">
                                        <?php foreach($wechatData as $v): ?>
                                            <option value="<?php echo $v['id']?>"><?php echo $v['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" id="we_ids" name="we_ids" >
                        </div>
                        <div class="space-2"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="email">邮箱:</label>
                            <div class="col-xs-12 col-sm-4">
                                <div class="clearfix">
                                    <input type="text" id="email" name="email" class="col-xs-12 col-sm-5" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="nick_name">昵称:</label>
                            <div class="col-xs-12 col-sm-4">
                                <div class="clearfix">
                                    <input type="text" id="nick_name" name="nick_name" class="col-xs-12 col-sm-5" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="corp_secret"> 状态 </label>

                            <div class="radio col-sm-8">
                                <label>
                                    <input name="status" type="radio" class="ace" value="1" checked/>
                                    <span class="lbl"> 启用</span>
                                </label>
                                <label>
                                    <input name="status" type="radio" class="ace" value="2"/>
                                    <span class="lbl"> 禁用</span>
                                </label>
                            </div>
                        </div>

                    </form>
                    <div class="clearfix">
                        <div class="col-md-offset-2 col-md-9">
                            <button class="btn btn-info" type="button" id="btn">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                提交
                            </button>
                            <a class="btn" href="/admin/user">
                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                返回
                            </a>
                        </div>
                    </div>

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
{include file="layout/datatable" /}
<script>
    $(function($){
        $('.select2').css('width','228').select2({
            allowClear:true
        }).on('change', function(e){
            //select2选中的值
            $("#we_ids").val(e.val);
        });

        $('#btn').click(function(){
            $.ajax({
                type : "POST",
                url : "/admin/user/add",
                data : $('#validation-form').serialize(),
                dataType: "json",
                success : function(res){ //res:{1:成功}
                    if(res.result == '1'){
                        bootbox.alert(res.message,function(){
                            window.location.href = '/admin/user/index';
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
