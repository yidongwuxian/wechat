<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">首页</a>
                </li>
        
                <li>
                    <a href="#">二维码管理</a>
                </li>
                
                <li>
                    <a href="/admin/qrcode/index">二维码列表</a>
                </li>
            </ul><!-- /.breadcrumb -->
        </div>
        <style type="text/css">
            .help-block {color: #b4b4b4; margin-top: 40px;}
            #dynamic-table img {width: 100px;height: 100px;}
        </style>
        <div class="page-content row">
            <div id="page-header">
                <h2>二维码管理
                    <small>
                        <i class="ace-icon fa fa-angle-double-right"></i>
                        二维码列表
                    </small>
                    <div class="btn-group pull-right">
                        <a href="#modal-add" data-toggle="modal" role="button" class="btn btn-sm btn-info tip-bottom addBtn" title="添加二维码">添加二维码</a>
                    </div>
                </h2>
            </div>
            <div class="col-xs-12 widget-box widget-color-blue2">
                <div>
                    <table id="dynamic-table" class="table table-striped table-bordered table-hover dataTable">
                        <thead>
                            <tr>
                                <th width="20%">名称</th>
                                <th width="15%">类型</th>
                                <th width="10%">场景编号</th>
                                <th width="10%">二维码</th>
                                <th width="10%">过期时间</th>
                                <th width="10%" class="sorting_disabled">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list as $vo):?>
                            <tr>
                                <td><?php echo $vo['name'];?></td>
                                <td><?php echo $vo['type'] == 1 ? '永久' : '临时';?></td>
                                <td><?php echo $vo['sid'];?></td>
                                <td>
                                    <a href="<?php echo $vo['url'];?>" target="_blank">
                                        <img src="<?php echo $vo['url'];?>" />
                                    </a>
                                </td>
                                <td><?php echo $vo['type'] == 1 ? '' : date('Y-m-d H:i:s', $vo['end_time']);?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="javascript:;" class="btn btn-xs btn-danger btn-delete" title="删除">
                                            <i class="ace-icon fa fa-trash-o"></i>
                                            删除
                                        </a>
                                        <input type="hidden" value="<?php echo $vo['id'];?>">
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
            <div class="modal-dialog" style="width:800px;">
                <div class="modal-content">
                    <div class="modal-header no-padding">
                        <div class="widget-header">
                            <h4 class="widget-title">添加二维码</h4>
                            <div class="widget-toolbar">
                                <a aria-hidden="true" data-dismiss="modal" class="close" type="button" style="font-size: 16px;line-height: 36px;">
                                    <i class="ace-icon fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <form id="modal-add-form" method="post" action="" role="form" class="form-horizontal" novalidate="novalidate">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label no-padding-right">二维码名称</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" id="name" class="form-controls col-sm-10"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="type" class="col-sm-2 control-label no-padding-right">二维码类型</label>
                                 <div class="radio col-sm-10">
                                    <label>
                                        <input type="radio" value="1" class="ace" name="type">
                                        <span class="lbl"> 永久</span>
                                    </label>
                                    <label>
                                        <input type="radio" value="2" class="ace" name="type" checked>
                                        <span class="lbl"> 临时</span>
                                    </label>
                                    <span class="help-block" style="margin-top: 10px;">
                                    * 永久二维码，无过期时间，但最多10万个。适用于帐号绑定、用户来源统计等场景<br/>
                                    * 临时二维码，有过期时间，最长可以设置604800秒后过期，但生成数量较多。
                                    </span>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label for="sid" class="col-sm-2 control-label no-padding-right">场景编号</label>
                                <div class="col-sm-10">
                                    <input type="number" name="sid" id="sid" class="form-controls col-sm-10"/>
                                    <span class="help-block">
                                        * 临时二维码时为32位非0整型，永久二维码时最大值为100000（目前参数只支持1--100000）
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="time" class="col-sm-2 control-label no-padding-right">有效时间</label>
                                <div class="col-sm-10">
                                    <input type="number" name="time" id="time" class="form-controls col-sm-10"/>
                                    <span class="help-block">
                                        * 该二维码有效时间，以秒为单位。最长可以设置604800秒后过期。此设置仅对临时二维码有效。
                                    </span>
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
{include file="layout/datatable" /}
<script type="text/javascript">
    jQuery(function($) {
        // //datatable init
        var oTable1 = $('#dynamic-table')
        .wrap("<div class='dataTables_borderWrap' />")
        .dataTable({
            "aoColumns": [
              null, null, null, null, null, null
            ],  
            "bAutoWidth": false,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bStateSave": true, //状态保存
            "bDestroy":true,
            "bJQueryUI": false,
            "sPaginationType": "full_numbers",
            "bInfo": true,//页脚信息
            "bProcessing": false,
            "oLanguage": {
                "sLengthMenu": "每页显示 _MENU_条",
                "sZeroRecords": "没有找到符合条件的数据",
                "sProcessing": "&lt;img src='/public/static/base/img/loading.gif' /&gt;",
                "sInfo": "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
                "sInfoEmpty": "木有记录",
                "sInfoFiltered": "(从 _MAX_ 条记录中过滤)",
                "sSearch": "搜索：",
                "oPaginate": {
                    "sFirst": "首页",
                    "sPrevious": "前一页",
                    "sNext": "后一页",
                    "sLast": "尾页"
                }
            }
        });

        // 删除
        $("#dynamic-table .btn-delete").on('click', function(){
            var id  = $(this).siblings('input').val();
            bootbox.confirm('一旦删除不能恢复！您确定要删除吗？', function(result){
                if(result){
                    $.ajax({
                        url:'/admin/qrcode/delete/id/' + id,
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
            $.ajax({
                type: "POST",
                url : "/admin/qrcode/save",
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

       

    })


</script>
