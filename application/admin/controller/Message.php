<?php
/**
 * 文件的简短描述：群发消息
 *
 * LICENSE:
 * @author lijin 2016/11/16
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\admin\controller;

use think\Request;


class Message extends Base
{

    public function __construct()
    {
        parent::__construct();
        $request = Request::instance();

        $this->assign('class', 'setting');
        $this->assign('subClass', $request->controller());
        $this->assign('action', $request->action());

        $this->imgPath  = ROOT_PATH . '/public';
    }

    public function index()
    {
        
    }
}