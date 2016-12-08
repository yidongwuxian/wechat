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
					<a href="/admin/template/index">模板消息管理</a>
				</li>
			</ul><!-- /.breadcrumb -->
		</div>
		<style type="text/css">
			.help-block {color: #b4b4b4; margin-top: 36px;}
		</style>
		<div class="page-content row">
			<div id="page-header">
				<h2>模板消息管理
					<small>
						<i class="ace-icon fa fa-angle-double-right"></i>
						模板消息列表
					</small>
					<div class="btn-group pull-right">
						<a href="#" class="btn btn-sm btn-primary tip-bottom" title="模板列表">模板列表</a>	
						<a href="#modal-add" data-toggle="modal" role="button" class="btn btn-sm btn-info tip-bottom addBtn" title="添加模板">添加模板</a>
						<a href="#" class="btn btn-sm btn-info tip-bottom" title="同步模板" id="synctpl">同步模板</a>	
					</div>
				</h2>
			</div>
			<div class="col-xs-12 widget-box widget-color-blue2">
				<div>
					<table id="dynamic-table" class="table table-striped table-bordered table-hover dataTable">
						<thead>
							<tr>
								<th>模板编号</th>
								<th>模板名称</th>
								<th>模板ID</th>
								<th class="sorting_disabled">操作</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($list as $vo):?>
							<tr>
								<td class="align-center"><?php echo $vo['tpl_num'];?></td>
								<td><?php echo $vo['title'];?></td>
								<td><?php echo $vo['tplid'];?></td>
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
							<h4 class="widget-title">添加模板消息</h4>
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
								<label for="type" class="col-sm-3 control-label no-padding-right">模板名称</label>
								<div class="col-sm-9">
									<input type="text" name="title" id="title"  maxlength="50" class="form-controls col-sm-10"/>
								</div>
							</div>
							<div class="form-group">
								<label for="type" class="col-sm-3 control-label no-padding-right">模板ID</label>
								<div class="col-sm-9">
									<input type="text" name="tplid" id="tplid" class="form-controls col-sm-10"/>
								</div>
							</div>
							<div class="form-group">
								<label for="type" class="col-sm-3 control-label no-padding-right">模板内容</label>
								<div class="col-sm-9">
									<textarea rows="8" cols="38" id="content" name="content"></textarea>
									<span class="help-block" style="margin-top: 0;">填写模板详细内容</span>
								</div>
							</div>
							<div class="form-group hidden">
								<label for="type" class="col-sm-3 control-label no-padding-right">Top Color</label>
								<div class="col-sm-9">
									<input type="text" name="topColor" id="topColor" length="7" class="form-controls col-sm-10"/>
									<span class="help-block">请正确填写色值，格式如：#FF0000</span>
								</div>
							</div>
							<div class="form-group">
								<label for="type" class="col-sm-3 control-label no-padding-right">文字颜色</label>
								<div class="col-sm-9">
									<input type="text" name="color" id="color" length="7" class="form-controls col-sm-10"/>
									<span class="help-block">请正确填写色值，格式如：#173177</span>
								</div>
							</div>
							<div class="form-group">
								<label for="type" class="col-sm-3 control-label no-padding-right">模板编号</label>
								<div class="col-sm-9">
									<input type="number" name="tplNum" id="tplNum"  maxlength="3" class="form-controls col-sm-10"/>
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
			bootbox.confirm('微信端模板将一并删除，删除后将不能恢复！您确定要删除吗？', function(result){
				if(result){
					$.ajax({
						url:'/admin/template/delete/id/' + id,
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
                url : "/admin/template/save",
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
			con.find('.modal-header .widget-title').html('编辑模板消息');
			$.ajax({
                type: "get",
                url : "/admin/template/info/id/"+id,
                dataType: "json",
                success : function(res){
                    if(res.result == '1'){
                    	con.find('#id').val(res.extension.id);
                    	con.find('#title').val(res.extension.title);
                    	con.find('#tplid').val(res.extension.tplid);
                    	con.find('#content').val(res.extension.content);
                    	con.find('#type').val(res.extension.type);
                    	con.find('#color').val(res.extension.color);
                    	con.find('#topColor').val(res.extension.top_color);
                    	con.find('#tplNum').val(res.extension.tpl_num);
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
			$('#modal-add .modal-header .widget-title').html('添加模板消息');
			$("#modal-add form")[0].reset();
		});

		// 同步微信模板
		$("#synctpl").on('click', function(){
			bootbox.confirm('本操作是以微信端数据为准', function(result){
				if(result){
					$.ajax({
						url:'/admin/template/synctemplate/',
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
	})


</script>
