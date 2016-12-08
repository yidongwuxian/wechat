<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;
use app\admin\model\Account;

class Index extends Base
{
    public function index()
    {
        $this->assign('class','index_manage');
        $this->assign('subClass','contact');
        return $this->fetch();
    }

    //退出登录
    public function logout(){
        if (!empty($_SESSION)) {
            $_SESSION = [];
        }
        session_unset();
        session_destroy();
        $this->redirect('admin/login/index');
    }

    //后台切换公众号
    public function changeWe(){
        $request = Request::instance();
        Session::delete('wechat_id');
        if ($request->param('we_id')) {
            $weChat = Account::get($request->param('we_id'));
            Session::set('wechat_id', $weChat['id']);
            Session::set('wechat_orid', $weChat['wechat_orid']);
            Session::set('wechat_appid', $weChat['wechat_appid']);
            Session::set('wechat_appsecret', $weChat['wechat_appsecret']);
            Session::set('wechat_token', $weChat['wechat_token']);
            Session::set('wechat_aeskey', $weChat['wechat_aeskey']);
            $this->redirect('/admin/index/index');
        }
    }

}
