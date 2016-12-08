<?php
/**
 * 文件的简短描述：测试
 *
 * LICENSE:
 * @author lijin 2016/11/16
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\admin\controller;

use think\Request;

class Test extends Base
{

    public function __construct()
    {
        parent::__construct();
        $request = Request::instance();

        $this->assign('class', 'setting');
        $this->assign('subClass', $request->controller());
        $this->assign('action', $request->action());
    }

    public function testTplMsg()
    {
    	$chatId = 10;
    	$tplId  = 1;
    	$toUser = 'opJ0uxLnRioIlLn-fsExXKoEb7ZY';
    	$data 	= [
            "keyword1" => "lijin",
            "keyword2" => "人民币100.00元",
            "keyword3" => "网银在线",
            "keyword4" => "",
            "keyword5" => "充值成功",
            "first"	   => "尊敬的柳先生，您已于 2016年11月15日 00:19成功充值",
            "remark"   => "测试哈哈哈"
        ];
        $url 	= "http://www.baidu.com";
        $msg = "";
    	$res 	= \wechat\TemplateMsg::send($chatId, $tplId, $toUser, $data, $url, '', '', $msg );
    	var_dump( $msg );
    	die;
    }

    public function testSend(){
        return $this->fetch();
    }
}