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
                    <a href="#">粉丝管理</a>
                </li>
                <li class="active">粉丝列表</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="widget-box widget-color-blue2">

            <div class="widget-header">
                <h4 class="widget-title lighter smaller">粉丝列表</h4>
            </div>
            <div class="widget-body">

                <div class="row">
                    <div class="col-xs-12">
                        <div>
                            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th width="15%">openID</th>
                                    <th width="12%">昵称</th>
                                    <th width="8%">关注状态</th>
                                    <th width="8%">性别</th>
                                    <th width="15%">地址</th>
                                    <!-- <th width="10%">头像</th> -->
                                    <th width="10%">关注时间</th>
                                    <th width="10%">取消关注时间</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php if( ! empty($list)): ?>
                                    <?php foreach($list as $vo): ?>
                                        <tr>
                                            <td><?php echo $vo['openid']; ?></td>
                                            <td><?php echo $vo['nickname']; ?></td>
                                            <td><?php echo $vo['subscribe'] == 1 ? '关注' : '未关注'; ?></td>
                                            <td><?php echo $sexArr[$vo['sex']]; ?></td>
                                            <td><?php echo $vo['country'] . $vo['province'] . $vo['city']; ?></td>
                                           <!--  <td>
                                                <img src="<?php echo $vo['headimgurl']; ?>" style="width:60px;height: 60px;">
                                            </td> -->
                                            <td><?php echo date('Y-m-d H:i:s', $vo['subscribe_time']); ?></td>
                                            <td><?php echo ($vo['unsubscribe_time'] > 0) ? date('Y-m-d H:i:s', $vo['unsubscribe_time']) : '' ; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
{include file="layout/datatable" /}
<script type="text/javascript">
    jQuery(function($) {
        //数据表格
        $('#dynamic-table').wrap("<div class='dataTables_borderWrap' />").dataTable( {
            bAutoWidth: false,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": false,
            "bStateSave": true, //状态保存
            "bProcessing": true,
            "bDestroy":true,
            "bJQueryUI": false,
            "sPaginationType": "full_numbers",
            "bInfo": false,//页脚信息
            "sServerMethod": "GET",
            language: {
                "sProcessing": "处理中...",
                "sLengthMenu": "每页显示 _MENU_ 项结果",
                "sZeroRecords": "没有匹配结果",
                "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                "sInfoFiltered": "（由 _MAX_ 项结果过滤）",
                "sInfoPostFix": "",
                "sSearch": "搜索：",
                "sUrl": "",
                "sEmptyTable": "表中数据为空",
                "sLoadingRecords": "载入中...",
                "sInfoThousands": ",",
                "oPaginate": {
                    "sFirst": "首页",
                    "sPrevious": "上一页",
                    "sNext": "下一页",
                    "sLast": "末页"
                },
                "oAria": {
                    "sSortAscending": ": 以升序排列此列",
                    "sSortDescending": ": 以降序排列此列"
                }
            }
        } );
    })
</script>