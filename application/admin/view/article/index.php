<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">首页</a>
				</li>
		
				<li>
					<a href="#">微站建设</a>
				</li>
				
				<li>
					<a href="/admin/article/index">文章列表</a>
				</li>
			</ul><!-- /.breadcrumb -->
		</div>
		<style type="text/css">
			.wysiwyg-toolbar .btn-group > .btn > .ace-icon:first-child{width: 16px;}
            .wysiwyg-style2{width: 530px;}
		</style>
		<div class="page-content row">
			<div id="page-header">
				<h2>微站建设
					<small>
						<i class="ace-icon fa fa-angle-double-right"></i>
						文章列表
					</small>
					<div class="btn-group pull-right">
						<a href="#" class="btn btn-sm btn-primary tip-bottom" title="文章列表">文章列表</a>	
						<a href="/admin/article/type" class="btn btn-sm btn-info tip-bottom" title="文章分类管理">文章分类管理</a>
						<a href="#modal-add" data-toggle="modal" role="button" class="btn btn-sm btn-info tip-bottom addBtn" title="添加文章">添加文章</a>
					</div>
				</h2>
			</div>
			<div class="col-xs-12 widget-box widget-color-blue2">
				<div>
					<table id="dynamic-table" class="table table-striped table-bordered table-hover dataTable">
						<thead>
							<tr>
								<th width="20%">所属分类</th>
								<th width="30%">标题</th>
								<!-- <th width="15%">关键词</th> -->
								<th width="15%">状态</th>
								<th class="sorting_disabled">操作</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($list as $vo):?>
							<tr>
								<td><?php echo isset($type[$vo['type_id']]) ? $type[$vo['type_id']] : '分类不存在';?></td>
								<td><?php echo $vo['title'];?></td>
								<!-- <td><?php echo $vo['keyword'];?></td> -->
								<td><?php  echo isset($status[$vo['status']]) ? $status[$vo['status']] : '错误'; ?></td>
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
			<div class="modal-dialog" style="width:800px;">
				<div class="modal-content">
					<div class="modal-header no-padding">
						<div class="widget-header">
							<h4 class="widget-title">添加文章</h4>
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
							<textarea style="display: none;" id="content" name="content"></textarea>
							<div class="form-group">
								<label for="type" class="col-sm-2 control-label no-padding-right">文章类型</label>
								<div class="col-sm-10">
									<select name="type" id="type" class="form-controls col-sm-10">
										<?php echo $typeList; ?>
									</select>
								</div>
							</div>							
							<div class="form-group">
								<label for="title" class="col-sm-2 control-label no-padding-right">文章标题</label>
								<div class="col-sm-10">
									<input type="text" name="title" id="title"  maxlength="200" class="form-controls col-sm-10"/>
								</div>
							</div>
							<div class="form-group">
								<label for="brief" class="col-sm-2 control-label no-padding-right">文章摘要</label>
								<div class="col-sm-10">
									<textarea rows="3" cols="60" id="brief" name="brief"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="type" class="col-sm-2 control-label no-padding-right">文章内容</label>
								<div class="col-sm-10">
									<div class="wysiwyg-editor" id="editor1" style="height:180px;width:520px;"></div>
								</div>
							</div>
							<div class="form-group">
                                <label class="col-sm-2 control-label no-padding-right">状态</label>
                                <div class="col-sm-10 radio">
                                    <label>
                                        <input type="radio" name="status" value="1" class="ace" checked/>
                                        <span class="lbl">启用</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="status" value="0" class="ace"/>
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
<script src="/static/base/js/bootstrap-wysiwyg.min.js"></script>
<script src="/static/base/js/jquery.hotkeys.min.js"></script>
{include file="layout/datatable" /}
<script type="text/javascript">
	jQuery(function($) {

		// //datatable init
		var oTable1 = $('#dynamic-table')
		.wrap("<div class='dataTables_borderWrap' />")
		.dataTable({
			"aoColumns": [
			  null, null, null, null
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
						url:'/admin/article/delete/id/' + id,
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
			$('#modal-add #content').val($('#modal-add #editor1').html());
			$.ajax({
                type: "POST",
                url : "/admin/article/save",
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
			con.find('.modal-header .widget-title').html('编辑文章');
			$.ajax({
                type: "get",
                url : "/admin/article/info/id/"+id,
                dataType: "json",
                success : function(res){
                    if(res.result == '1'){
                    	con.find('#id').val(res.extension.id);
                    	con.find('#title').val(res.extension.title);
                    	con.find('#type').val(res.extension.type_id);
                    	con.find('#brief').val(res.extension.brief);
                    	con.find('#content').val(res.extension.content);
                    	con.find('#status').val(res.extension.status);
                    	con.find('#editor1').html(res.extension.content);
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
		
		// 添加
		$("#page-header .addBtn").on('click', function(){
			$('#modal-add .modal-header .widget-title').html('添加文章');
			$("#modal-add form")[0].reset();
		});

		// ==========EDITOR START===============
        //but we want to change a few buttons colors for the third style
        function showErrorAlert (reason, detail) {
            var msg='';
            if (reason==='unsupported-file-type') { msg = "Unsupported format " +detail; }
            else {
                //console.log("error uploading file", reason, detail);
            }
            $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>'+ 
            '<strong>File upload error</strong> '+msg+' </div>').prependTo('#alerts');
        }
        $('#editor1').ace_wysiwyg({
            toolbar:
            [
                'font',
                null,
                'fontSize',
                null,
                {name:'bold', className:'btn-info'},
                {name:'italic', className:'btn-info'},
                {name:'strikethrough', className:'btn-info'},
                {name:'underline', className:'btn-info'},
                null,
                {name:'insertunorderedlist', className:'btn-success'},
                {name:'insertorderedlist', className:'btn-success'},
                {name:'outdent', className:'btn-purple'},
                {name:'indent', className:'btn-purple'},
                null,
                {name:'justifyleft', className:'btn-primary'},
                {name:'justifycenter', className:'btn-primary'},
                {name:'justifyright', className:'btn-primary'},
                {name:'justifyfull', className:'btn-inverse'},
                null,
                {name:'createLink', className:'btn-pink'},
                {name:'unlink', className:'btn-pink'},
                null,
                {name:'insertImage', className:'btn-success'},
                null,
                'foreColor',
                null,
                {name:'undo', className:'btn-grey'},
                {name:'redo', className:'btn-grey'}
            ],
            'wysiwyg': {
                fileUploadError: showErrorAlert
            }
        }).prev().addClass('wysiwyg-style2');
        //RESIZE IMAGE
        //Add Image Resize Functionality to Chrome and Safari
        //webkit browsers don't have image resize functionality when content is editable
        //so let's add something using jQuery UI resizable
        //another option would be opening a dialog for user to enter dimensions.
        if ( typeof jQuery.ui !== 'undefined' && ace.vars['webkit'] ) {
            
            var lastResizableImg = null;
            function destroyResizable() {
                if(lastResizableImg == null) return;
                lastResizableImg.resizable( "destroy" );
                lastResizableImg.removeData('resizable');
                lastResizableImg = null;
            }

            var enableImageResize = function() {
                $('.wysiwyg-editor')
                .on('mousedown', function(e) {
                    var target = $(e.target);
                    if( e.target instanceof HTMLImageElement ) {
                        if( !target.data('resizable') ) {
                            target.resizable({
                                aspectRatio: e.target.width / e.target.height,
                            });
                            target.data('resizable', true);
                            
                            if( lastResizableImg != null ) {
                                //disable previous resizable image
                                lastResizableImg.resizable( "destroy" );
                                lastResizableImg.removeData('resizable');
                            }
                            lastResizableImg = target;
                        }
                    }
                })
                .on('click', function(e) {
                    if( lastResizableImg != null && !(e.target instanceof HTMLImageElement) ) {
                        destroyResizable();
                    }
                })
                .on('keydown', function() {
                    destroyResizable();
                });
            }

            enableImageResize();
        }
        // ==========EDITOR END===============

	})


</script>
