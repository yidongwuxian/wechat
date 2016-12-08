<?php
//配置文件
return [
    //当前版本号
    'version' => '3.0.12',

    //业务模块的模式：开发dev、测试test、生产product
    'biz_mode' => 'dev',

    //App模式：wechat微信模式，app为App模式IOS或安卓
    'app_mode' => 'app',

    //应用Trace
    'app_trace'  => true,

    //当前存入数据库的微信号id
    'chat_id' => 4,

    //开发模式是否开启，只有在dev和test模式下此参数才起作用
    'is_dev_mode' => true, //开启后不再通过微信获取openid

    //模糊查询楼盘时，返回的结果数量
    'select_ban_num_default' => 5,

    //一次最多抵押的房产数量
    'max_house_count' => 3,

    //业务列表查询多长时间的评估数据，只计算时间差，不按照自然日计算
    'evaluation_search_time' => 3600 * 24 * 5,

    //默认主页，由于App模式不能关闭页面，所以给出默认主页
    'default_uri' => '/ldd/index/index',

    //默认错误提示
    'default_error_message' => '服务器繁忙，请稍后再试！',

    //业务系统默认初始密码
    'default_password' => 111111,

    //独立日志
    'log'   => [
        'type'          => 'file',
        // error和sql日志单独记录
        'apart_level'   =>  ['ldd','from_ftx'],
    ],

    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => APP_PATH . 'ldd' .DS . 'view' . DS . 'tmpl' . DS . 'success.php',
    'dispatch_error_tmpl'    => APP_PATH . 'ldd' .DS . 'view' . DS . 'tmpl' . DS . 'error.php',

    //房天下接口配置（测试环境），会根据此配置生成token
    'fang_test' => [
        'encrypt_key'   => 'LlEhahqD',
        'username'     => 'sy-dfbx01',
        'password'      => '123456',
        'ip'            => '127.0.0.1',
        'api_address'   => 'http://testpgapi.3g.fang.com',
        'userkey'       => '306fd4cb8f0742c99d2b1d1344a24b03'
    ],

    //房天下接口配置（开发环境），会根据此配置生成token
    'fang_dev' => [
        'encrypt_key'   => 'LlEhahqD',
        'username'     => 'sy-dfbx01',
        'password'      => '123456',
        'ip'            => '127.0.0.1',
        'api_address'   => 'http://testpgapi.3g.fang.com',
        'userkey'       => '306fd4cb8f0742c99d2b1d1344a24b03'
    ],

    //房天下接口配置（生产环境），会根据此配置生成token
    'fang_product' => [
        'encrypt_key'   => 'olEhaGqy',
        'username'     => 'dfbx01',
        'password'      => '654321',
        'ip'            => '127.0.0.1',
        'api_address'   => 'https://pgapi.3g.fang.com'
    ],

    //直辖市
    'municipality' => [
        '110000' => '北京市',
        '120000' => '天津市',
        '310000' => '上海市',
        '500000' => '重庆市',
        '810000' => '香港特别行政区',
        '820000' => '澳门特别行政区'
    ],

    //返佣白名单
    'white_list' => [
        '310000'
    ],

    //房屋朝向
    'forward' => [
        1 => '南北',
        2 => '东西',
        3 => '东南',
        4 => '西南',
        5 => '东北',
        6 => '西北',
        7 => '东',
        8 => '西',
        9 => '南',
        10 => '北'
    ],

    //业务状态
    'biz_status' => [
        '0'  =>  '审批中',
        '1'  =>  '审批中',
        '2'  =>  '未知状态|yellow',
        '4'  =>  '拒绝|red',
        '5'  =>  '面签完成|Lavender',
        '8'  =>  '待尽调|yellow',
        '9'  =>  '尽职调查|lightblue',//未派单
        '10' =>  '尽职调查|lightblue',//已派单
        '11' =>  '尽职调查|lightblue',//已接单
        '12' =>  '尽调完成',
        '13' =>  '审批完成',
        '14' =>  '抵押完成|deepyellow',
        '15' =>  '已放款|blue',
        '16' =>  '评估中|yellow',
        '17' =>  '评估完成',
        '500' =>  '否决|red', //500
        '521' =>  '审批中', //521
        '300' =>  '已签订', //300
        '316' =>  '已放款', //316
        '330' =>  '已逾期|red', //330
        '331' =>  '中止|red', //331
        '422' =>  '已结清', //422
        '421' =>  '已核销', //421
        '423' =>  '放款中', //423
        '425' =>  '失效|red', //425
        '522' =>  '减免结清', //522
        '523' =>  '处置审批中保存后', //523
        '524' =>  '处置中发起审批时', //524
        '525' =>  '处置结清还款后', //525
        '526' =>  '重组审批中|yellow', //526
        '527' =>  '重组正常', //527
        '528' =>  '重组结清', //528
        '999' =>  '未知状态|red'
    ],

    //开发模式
    'openid_list' => [
        ['name' => '王振', 'openid' => 'o6fF8s6Xu_I5WmCQtnxVvzZXXov8']
    ],

    //业务接口地址
    //'biz_api' => 'http://172.16.49.221:8080/', //lirong
    'biz_api' => 'http://172.16.49.228:8080/', //wuxiao
    //'biz_api' => 'http://211.99.230.27:7003/', //linpeng 47
    //'biz_api' => 'http://211.99.230.28:8080/', //wangrui 38

    'test_api_address' => [

    ],

    //模板消息对外接口秘钥
    'external_api_key' => '(8_u&k%9+)', //对外api密钥
];