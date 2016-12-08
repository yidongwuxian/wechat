<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">首页</a>
                </li>
        
                <li>
                    <a href="#">菜单管理</a>
                </li>
                
                <li>
                    <a href="/admin/menu/index">菜单列表</a>
                </li>
            </ul><!-- /.breadcrumb -->
        </div>
        <style type="text/css">
            .help-block {color: #b4b4b4; margin-top: 36px;}
        </style>
        <div class="page-content row">
            <div id="page-header">
                <h2>菜单列表
                    <div class="btn-group pull-right">
                        <a href="#modal-add" data-toggle="modal" role="button" class="btn btn-sm btn-info tip-bottom addBtn" title="添加菜单">添加菜单</a>
                        <a href="javascript:;" id="send" class="btn btn-sm btn-info tip-bottom" title="生产微信自定义菜单">生产微信自定义菜单</a>
                    </div>
                </h2>
            </div>
            <div class="col-xs-12 widget-box widget-color-blue2">
                <div>
                    <table id="dynamic-table" class="table table-striped table-bordered table-hover dataTable">
                        <thead>
                            <tr>
                                <th width="15%">菜单名</th>
                                <th width="20%">类型</th>
                                <th width="30%">关联内容(key)</th>
                                <th width="20%" class="sorting_disabled">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list as $menu):?>
                            <tr>
                                <td><?php if ($menu['level'] == 2):?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;|----
                                    <?php endif;?>
                                    <?php echo $menu['name'];?>
                                </td>
                                <td><?php echo $typeConf[$menu['type']];?></td>
                                <td><?php echo $menu['code'];?></td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-xs btn-primary editBtn" href="#modal-add" data-toggle="modal" role="button">
                                            <i class="ace-icon fa fa-edit"></i>
                                            编辑
                                        </a>
                                        <a href="javascript:;" class="btn btn-xs btn-danger btn-delete" title="删除">
                                            <i class="ace-icon fa fa-trash-o"></i>
                                            删除
                                        </a>
                                        <input type="hidden" value="<?php echo $menu['id'];?>">
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- 新增层效果 start-->
        <div id="modal-add" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header no-padding">
                        <div class="widget-header">
                            <h4 class="widget-title">添加菜单</h4>
                            <div class="widget-toolbar">
                                <a aria-hidden="true" data-dismiss="modal" class="close" type="button" style="font-size: 16px;line-height: 36px;">
                                    <i class="ace-icon fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <form id="modal-add-form" method="post" action="" role="form" class="form-horizontal" novalidate="novalidate">
                        <div class="modal-body">    
                            <input type="hidden" name="id" id="id" value="">
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label no-padding-right">菜单名称</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" id="name" maxlength="50" class="form-controls col-sm-10"/>
                                    <span class="help-block">可创建最多 3 个一级菜单，每个一级菜单下可创建最多 5 个二级菜单。</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pid" class="col-sm-3 control-label no-padding-right">一级菜单</label>
                                <div class="col-sm-9">
                                    <select id="pid" name="pid" class="form-controls col-sm-10">
                                        <option value="0">无</option>
                                        <?php foreach ($levelOneList as $menu):?>
                                        <option value="<?php echo $menu['id'];?>"><?php echo $menu['name'];?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <span class="help-block">如果是一级菜单，选择"无"即可</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="type" class="col-sm-3 control-label no-padding-right">类型</label>
                                <div class="col-sm-9">
                                    <select id="type" name="type" class="form-controls col-sm-10">
                                        <?php foreach ($typeConf as $typeKey => $typeVal):?>
                                        <option value="<?php echo $typeKey;?>"><?php echo $typeVal;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="code" class="col-sm-3 control-label no-padding-right">关联内容(key值)</label>
                                <div class="col-sm-9">
                                    <input type="text" name="code" id="code" class="form-controls col-sm-10"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right">使用已有微站链接</label>
                                <div class="col-sm-9">
                                    <select></select>
                                    订阅号与服务号不一样，注意查看文档
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right">序号</label>
                                <div class="col-sm-9">
                                    <input type="text" name="sort" id="sort" maxlength="50" class="form-controls col-sm-10"/>
                                    <span class="help-block">数值越小，越靠前</span>
                                </div>
                            </div>                             
                        </div>
                        <div class="modal-footer no-margin-top">
                            <button data-dismiss="modal" class="btn btn-sm">
                                <i class="ace-icon fa fa-times"></i>
                                取消
                            </button>

                            <a class="btn btn-sm btn-primary" id="submit" type="button">
                                <i class="ace-icon fa fa-check"></i>
                                保存
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- 新增层效果 end -->

    </div>
</div>

<script type="text/javascript">
    jQuery(function($) {
        // 删除
        $("#dynamic-table .btn-delete").on('click', function(){
            var id  = $(this).siblings('input').val();
            bootbox.confirm('一旦删除不能恢复！您确定要删除吗？', function(result){
                if(result){
                    $.ajax({
                        url:'/admin/menu/delete/id/' + id,
                        type:'get',
                        dataType:'json',
                        success:function(res){
                            if (res.result == 1){
                                bootbox.alert(res.message, function(){
                                    window.location.reload();
                                });                 
                            } else {
                                bootbox.alert(res.message);
                            }
                            return false;
                        },
                        error:function(){
                            bootbox.alert('系统错误');
                            return false;
                        }
                    });
                }
            });
        });

        // 保存
        $("#modal-add #submit").on('click', function(){
            // console.log($('#modal-add-form').serialize());return false;
            $.ajax({
                type: "POST",
                url : "/admin/menu/save",
                data : $('#modal-add-form').serialize(),
                dataType: "json",
                success : function(res){
                    if(res.result == '1'){
                        bootbox.alert(res.message,function(){
                            window.location.reload();
                        });
                    }else{
                        bootbox.alert(res.message);
                        return false;
                    }
                }
            });
        })

        // 查看/编辑
        $("#dynamic-table .editBtn").on('click', function(){
            var id  = parseInt($(this).siblings('input').val());
            var con = $('#modal-add');
            if(isNaN(id)){
                $('#modal-add').modal('hide');
                bootbox.alert("请求参数错误");
                return false;
            }
            con.find('.modal-header .widget-title').html('编辑菜单');
            $.ajax({
                type: "get",
                url : "/admin/menu/info/id/"+id,
                dataType: "json",
                success : function(res){
                    if(res.result == '1'){
                        con.find('#id').val(res.extension.id);
                        con.find('#pid').val(res.extension.pid);
                        con.find('#name').val(res.extension.name);
                        con.find('#type').val(res.extension.type);
                        con.find('#code').val(res.extension.code);
                        con.find('#sort').val(res.extension.sort);
                    }else{
                        bootbox.alert(res.message);
                        $('#modal-add').modal('hide');
                        return false;
                    }
                },
                error:function(){
                    bootbox.alert('系统错误');
                    $('#modal-add').modal('hide');
                    return false;
                }
            });
        });
        
        // 添加模板消息
        $("#page-header .addBtn").on('click', function(){
            $('#modal-add .modal-header .widget-title').html('添加菜单');
            $("#modal-add form")[0].reset();
            $("#id").val('');
        });

        $("#send").on('click', function(){
            $.ajax({
                type: "get",
                url : "/admin/menu/createmenu/",
                dataType: "json",
                success : function(res){
                    bootbox.alert(res.message);
                    $('#modal-add').modal('hide');
                    return false;
                },
                error:function(){
                    bootbox.alert('系统错误');
                    $('#modal-add').modal('hide');
                    return false;
                }
            });
        });
    })


</script>
