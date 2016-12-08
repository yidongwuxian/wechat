<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">首页</a>
                </li>
        
                <li>
                    <a href="#">消息管理</a>
                </li>
                
                <li>
                    <a href="/admin/reply/index">自定义回复管理</a>
                </li>
            </ul><!-- /.breadcrumb -->
        </div>
        <style type="text/css">
            .help-block {color: #b4b4b4;}
            #keyContainer,.unstyled {margin-left:0;}
            #keyContainer li {list-style: none; margin-top: 5px;}
            #keyContainer li input{width: 60%; margin-right: 10px;}
            .input-daterange.input-group{width: 84%;}
            .unstyled li {list-style: none;}
        </style>
        <div class="page-content row">
            <div id="page-header">
                <h2>文本回复管理
                    <small>
                        <i class="ace-icon fa fa-angle-double-right"></i>
                        文本回复列表
                    </small>
                    <div class="btn-group pull-right">
                        <a href="#" class="btn btn-sm btn-primary tip-bottom" title="文本回复列表">文本回复列表</a> 
                        <a href="/admin/reply/newslist" class="btn btn-sm btn-info tip-bottom" title="图文回复列表">图文回复列表</a>
                        <a href="#modal-add" data-toggle="modal" role="button" class="btn btn-sm btn-info tip-bottom addBtn" title="添加文本回复">添加文本回复</a>
                    </div>
                </h2>
            </div>
            <div class="col-xs-12 widget-box widget-color-blue2">
                <div>
                    <table id="dynamic-table" class="table table-striped table-bordered table-hover dataTable">
                        <thead>
                            <tr>
                                <th width="15%">规则名称</th>
                                <th width="20%">关键词</th>
                                <th width="10%" class="align-center">状态</th>
                                <th width="20%">作用时间</th>
                                <th width="20%" class="sorting_disabled">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list as $vo):?>
                            <tr>
                                <td><?php echo $vo['name'];?></td>
                                <td><ul class="unstyled">
                                        <?php $keys = json_decode($vo['keyword'], true); foreach ($keys as $val): ?>
                                        <li>
                                            <span>关键词： <?php echo $val['key'];?></span>
                                            <span class="pull-right">类型：<?php if($val['type']==1):?>完全匹配<?php else:?>包含匹配<?php endif;?></span>
                                        </li>
                                        <?php endforeach;?>
                                    </ul>
                                </td>
                                <td class="align-center"><?php echo $vo['state'] == 1 ? '启用' : '未启用';?></td>
                                <td><?php echo date('Y-m-d H:i:s', $vo['start_time']) . ' 至 ' . date('Y-m-d H:i:s', $vo['end_time']);?></td>
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
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header no-padding">
                        <div class="widget-header">
                            <h4 class="widget-title">添加文本回复</h4>
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
                                <label for="name" class="col-sm-3 control-label no-padding-right">规则名</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" id="name"  maxlength="50" class="form-controls col-sm-10"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="keyword" class="col-sm-3 control-label no-padding-right">关键词</label>
                                <div class="col-sm-9">
                                    <ul class="unstyled" id="keyContainer">
                                        <li>
                                            <input type="text" name="keyword[0][key]" id="keyword" maxlength="50"/>
                                            <select name="keyword[0][type]">
                                                <option value="1">完全匹配</option>
                                                <option value="2">包含匹配</option>
                                            </select>                                       
                                        </li>                                   
                                    </ul>
                                    <div><button class="btn btn-sm btn-info" id="addKey">添加</button></div> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="content" class="col-sm-3 control-label no-padding-right">回复内容</label>
                                <div class="col-sm-9">
                                    <textarea rows="6" cols="38" id="content" name="content"></textarea>
                                    <span class="help-block">填写回复内容</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="score" class="col-sm-3 control-label no-padding-right">作用时间</label>
                                <div class="col-sm-9">
                                    <div class="input-daterange input-group">
                                        <input type="text" size="16" readonly class="form-control datetime" id="startTime" name="startTime" value=""/>
                                        <span class="input-group-addon">至</span>
                                        <input type="text" size="16" readonly class="form-control datetime" id="endTime" name="endTime" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right">状态</label>
                                <div class="col-sm-9 radio">
                                    <label>
                                        <input type="radio" name="state" value="1" class="ace" checked/>
                                        <span class="lbl">启用</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="state" value="0" class="ace"/>
                                        <span class="lbl">禁止</span>
                                    </label>
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
<link rel="stylesheet" href="/static/base/css/bootstrap-datetimepicker.min.css" />
<script src="/static/base/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
    jQuery(function($) {
        $(".datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii', autoclose: true});
        // //datatable init
        var oTable1 = $('#dynamic-table')
        .wrap("<div class='dataTables_borderWrap' />")
        .dataTable({
            "aoColumns": [
              null, null, null,null, null
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
                        url:'/admin/reply/textdel/id/' + id,
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
                url : "/admin/reply/textsave",
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
            con.find('.modal-header .widget-title').html('编辑文本回复');
            $.ajax({
                type: "get",
                url : "/admin/reply/textinfo/id/"+id,
                dataType: "json",
                success : function(res){
                    if(res.result == '1'){
                        con.find('#id').val(res.extension.id);
                        con.find('#state').val(res.extension.state);
                        con.find('#name').val(res.extension.name);
                        con.find('#content').val(res.extension.content);
                        con.find('#startTime').val(res.extension.startTime);
                        con.find('#endTime').val(res.extension.endTime);
                        var keys = '';
                        $.each(res.extension.keyword,function(index, item){
                            keys += '<li><input type="text" name="keyword['+index+'][key]" id="keyword" value="'+item.key+'" maxlength="50"/>' +
                                    '<select name="keyword['+index+'][type]">' +
                                    '<option value="1" '+(item.type== 1? 'selected="selected"' : '')+'>完全匹配</option>' +
                                    '<option value="2" '+(item.type== 2? 'selected="selected"' : '')+'>包含匹配</option></select></li>';
                        })
                        con.find('#keyContainer').html(keys);
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
            $('#modal-add .modal-header .widget-title').html('添加文本回复');
            $("#modal-add form")[0].reset();

            var keys = '<li><input type="text" name="keyword[0][key]" id="keyword" maxlength="50"/>'+
                            '<select name="keyword[0][type]">'+
                                '<option value="1">完全匹配</option>'+
                                '<option value="2">包含匹配</option>'+
                            '</select>'+                              
                        '</li>';
            $('#modal-add #keyContainer').html(keys);
        });

        $("#addKey").click(function(){
            var length = $("#keyContainer li").length;
            var keyStr = '<li><input type="text" name="keyword['+length+'][key]" id="keyword" maxlength="50"/>'
                        + '<select name="keyword['+length+'][type]">'
                        + '<option value="1">完全匹配</option>'
                        + '<option value="2">包含匹配</option></select></li>';
                
            $("#keyContainer").append(keyStr);
            return false;
        });

    })


</script>
