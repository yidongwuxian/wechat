<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>

            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">主页</a>
                </li>

                <li>
                    <a href="#">公众号管理</a>
                </li>
                <li class="active">修改公众号</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    修改公众号
                </h1>
            </div><!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <form class="form-horizontal" id="form">
                        <input type="hidden" name="id" value="<?php echo $account['id']; ?>">
                        <input type="hidden" name="old_wechat" value="<?php echo $account['wechat']; ?>">
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="corp_secret"> 类型 </label>
                            <div class="radio col-sm-8">
                                <label>
                                    <input name="wechat_type" type="radio" class="ace" value="1" <?php if($account['wechat_type'] == 1) echo 'checked'; ?>/>
                                    <span class="lbl"> 服务号</span>
                                </label>
                                <label>
                                    <input name="wechat_type" type="radio" class="ace" value="2" <?php if($account['wechat_type'] == 2) echo 'checked'; ?>/>
                                    <span class="lbl"> 订阅号</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right"> 标志 </label>

                            <div class="col-sm-10">
                                <input type="text" name="wechat" placeholder="公众号标志" class="col-xs-10 col-sm-3" value="<?php echo $account['wechat']; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right"> 名称 </label>

                            <div class="col-sm-10">
                                <input type="text" name="title" placeholder="公众号名称" class="col-xs-10 col-sm-3" value="<?php echo $account['title']; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right"> 原始ID </label>

                            <div class="col-sm-10">
                                <input type="text" name="wechat_orid" placeholder="原始ID" class="col-xs-10 col-sm-3" value="<?php echo $account['wechat_orid']; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right"> AppId </label>

                            <div class="col-sm-10">
                                <input type="text" name="wechat_appid" placeholder="AppId" class="col-xs-10 col-sm-3" value="<?php echo $account['wechat_appid']; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right"> AppSecret </label>

                            <div class="col-sm-10">
                                <input type="text" name="wechat_appsecret" placeholder="AppSecret" class="col-xs-10 col-sm-3" value="<?php echo $account['wechat_appsecret']; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right"> Token </label>

                            <div class="col-sm-10">
                                <input type="text" name="wechat_token" placeholder="Token" class="col-xs-10 col-sm-3" value="<?php echo $account['wechat_token']; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right"> AesKey </label>

                            <div class="col-sm-10">
                                <input type="text" name="wechat_aeskey" placeholder="AesKey" class="col-xs-10 col-sm-3" value="<?php echo $account['wechat_aeskey']; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right"> 回调地址 </label>

                            <div class="col-sm-10">
                                <input type="text" name="wechat_callback" placeholder="回调地址" class="col-xs-10 col-sm-3" value="<?php echo $account['wechat_callback']; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right"> 状态 </label>

                            <div class="radio col-sm-8">
                                <label>
                                    <input name="status" type="radio" class="ace" value="1" <?php if($account['status'] == 1) echo 'checked'; ?>/>
                                    <span class="lbl"> 启用</span>
                                </label>
                                <label>
                                    <input name="status" type="radio" class="ace" value="2" <?php if($account['status'] == 2) echo 'checked'; ?>/>
                                    <span class="lbl"> 禁用</span>
                                </label>
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
                url : "/admin/account/edit",
                data : $('#form').serialize(),
                dataType: "json",
                success : function(res){ //res:{1:成功}
                    if(res.result == '1'){
                        bootbox.alert(res.message,function(){
                            window.location.href = '/admin/account/index';
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
