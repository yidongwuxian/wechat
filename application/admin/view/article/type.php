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
					<a href="/admin/article/index">文章分类</a>
				</li>
			</ul><!-- /.breadcrumb -->
		</div>
		<style type="text/css">
			.help-block {color: #b4b4b4;}
		</style>
		<div class="page-content row">
			<div id="page-header">
				<h2>微站建设
					<small>
						<i class="ace-icon fa fa-angle-double-right"></i>
						文章分类
					</small>
					<div class="btn-group pull-right">
						<a href="/admin/article/index" class="btn btn-sm btn-info tip-bottom" title="文章列表">文章列表</a>	
						<a href="#" class="btn btn-sm btn-primary tip-bottom" title="文章分类管理">文章分类管理</a>
						<a href="#modal-add" data-toggle="modal" role="button" class="btn btn-sm btn-info tip-bottom addBtn" title="添加分类">添加分类</a>
					</div>
				</h2>
			</div>
			<div class="col-xs-12 widget-box widget-color-blue2">
				<div>
					<table id="dynamic-table" class="table table-striped table-bordered table-hover dataTable">
						<thead>
							<tr>
								<th width="30%">名称</th>
								<th width="20%">排序</th>
								<th width="10%" class="sorting_disabled">操作</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($list as $vo): ?>
							<tr>
								<td><?php echo $vo['name'];?></td>
								<td><?php echo $vo['sort'];?></td>
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
							<?php endforeach; ?>
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
							<h4 class="widget-title">添加分类</h4>
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
								<label for="pid" class="col-sm-3 control-label no-padding-right">父分类</label>
								<div class="col-sm-9">
									<select id="pid" name="pid" class="form-controls col-sm-10">
										<option value="0">作为一级分类</option>
										<?php foreach ($list as $key => $val):?>
										<option value="<?php echo $val['id'];?>"><?php echo $val['name'];?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="type" class="col-sm-3 control-label no-padding-right">名称</label>
								<div class="col-sm-9">
									<input type="text" name="name" id="name"  maxlength="80" class="form-controls col-sm-10"/>
								</div>
							</div>
							<div class="form-group">
								<label for="sort" class="col-sm-3 control-label no-padding-right">排序</label>
								<div class="col-sm-9">
									<input type="text" name="sort" id="sort" class="form-controls col-sm-10"/>
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

		//datatable init
		// var oTable1 = $('#dynamic-table')
		// .wrap("<div class='dataTables_borderWrap' />")
		// .dataTable({
		// 	"aoColumns": [
		// 	  null, null, null, null, null
		// 	],	
		// 	"bAutoWidth": false,
		// 	"bLengthChange": true,
		// 	"bFilter": true,
		// 	"bSort": true,
		// 	"bStateSave": true, //状态保存
		// 	"bDestroy":true,
		// 	"bJQueryUI": false,
		// 	"sPaginationType": "full_numbers",
		// 	"bInfo": true,//页脚信息
		// 	"bProcessing": false,
		// 	"oLanguage": {
		// 		"sLengthMenu": "每页显示 _MENU_条",
		// 		"sZeroRecords": "没有找到符合条件的数据",
		// 		"sProcessing": "&lt;img src='/public/static/base/img/loading.gif' /&gt;",
		// 		"sInfo": "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
		// 		"sInfoEmpty": "木有记录",
		// 		"sInfoFiltered": "(从 _MAX_ 条记录中过滤)",
		// 		"sSearch": "搜索：",
		// 		"oPaginate": {
		// 			"sFirst": "首页",
		// 			"sPrevious": "前一页",
		// 			"sNext": "后一页",
		// 			"sLast": "尾页"
		// 		}
		// 	}
	 //    });

		// 删除
		$("#dynamic-table .btn-delete").on('click', function(){
			var id  = $(this).siblings('input').val();
			bootbox.confirm('一旦删除不能恢复！您确定要删除吗？', function(result){
				if(result){
					$.ajax({
						url:'/admin/article/deltype/id/' + id,
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
                url : "/admin/article/savetype",
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
			con.find('.modal-header .widget-title').html('编辑分类');
			$.ajax({
                type: "get",
                url : "/admin/article/infotype/id/"+id,
                dataType: "json",
                success : function(res){
                    if(res.result == '1'){
                    	con.find('#id').val(res.extension.id);
                    	con.find('#name').val(res.extension.name);
                    	con.find('#sort').val(res.extension.sort);
                    	con.find('#pid').val(res.extension.pid);
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
			$('#modal-add .modal-header .widget-title').html('添加分类');
			$("#modal-add form")[0].reset();
		});
	})


</script>
