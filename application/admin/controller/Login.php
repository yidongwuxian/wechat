<?php
/**
 * 类名：Login
 *
 * 类的详细描述：登录类
 *
 * LICENSE:
 * @author zhangpeng 2016/11/09
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;
use app\admin\model\Account;
use app\admin\model\User;

class Login extends Controller {

    public function _initialize(){
        if(session('user_id') && session('wechat_id'))
            $this->redirect('admin/index/index');
    }

    public function index(){
        $this->view->engine->layout(false);
        return $this->fetch();
    }

    public function login(){
        $request = Request::instance();
        if ($request->isAjax()) {
            $userModel = new User();
            $data['user_name'] = trim($this->request->post('user_name'));
            $data['password'] = trim($this->request->post('password'));
            $checkRet = $this->validate($data,'User.login');
            if (true !== $checkRet) {
                \Util::echoJson($checkRet);
            }
            $result = $userModel->login($data);
            if ($result == false) {
                \Util::echoJson('用户名或密码错误');
            } else {
                $userInfo = User::get(session('user_id'));
                $hasWeIds = unserialize($userInfo['we_ids']);   //获取当前用户所管理的公众号ids
                //如果登录用户拥有的可管理公众号大于1个，跳到选择公众号页面
                if (count($hasWeIds) > 1) {
                    \Util::echoJson('登录成功！',true,'/admin/login/selectwechat');
                } else {
                    //如果没有可管理的公众号,提示错误信息，删除session
                    if (empty($hasWeIds[0])) {
                        if(!empty($_SESSION))
                            $_SESSION = [];
                        session_unset();
                        session_destroy();
                        \Util::echoJson('您没有可管理的企业，请联系客服！');
                    } else {
                        //如果只有一个公众号，把公众号id,appid,secret存到session
                        $weChat = Account::get($hasWeIds[0]);
                        Session::set('wechat_id', $weChat['id']);
                        Session::set('wechat_orid', $weChat['wechat_orid']);
                        Session::set('wechat_appid', $weChat['wechat_appid']);
                        Session::set('wechat_appsecret', $weChat['wechat_appsecret']);
                        Session::set('wechat_token', $weChat['wechat_token']);
                        Session::set('wechat_aeskey', $weChat['wechat_aeskey']);
                        \Util::echoJson('登录成功！', true, '/admin/index/index');
                    }
                }
            }
        }
    }

    public function selectWechat(){
        $request = Request::instance();
        if ($request->param('we_id')) {
            //把公众微信id,appid,secret存到session
            $weChat = Account::get($request->param('we_id'));
            Session::set('wechat_id', $weChat['id']);
            Session::set('wechat_orid', $weChat['wechat_orid']);
            Session::set('wechat_appid', $weChat['wechat_appid']);
            Session::set('wechat_appsecret', $weChat['wechat_appsecret']);
            Session::set('wechat_token', $weChat['wechat_token']);
            Session::set('wechat_aeskey', $weChat['wechat_aeskey']);
            $this->redirect('/admin/index/index');
        } else {
            $userInfo = User::get(session('user_id'));
            $hasWyIds = unserialize($userInfo['we_ids']);   //获取当前用户所管理的公众号ids
            if ($hasWyIds) {
                $accountModel = new Account();
                $list = $accountModel::all($hasWyIds);
                $listPage = ceil(count($list)/3);
                $this->assign('list', $list);
                $this->assign('listPage', $listPage);
                $this->view->engine->layout(false);
                return $this->fetch();
            } else {
                $this->error('您没有可管理的企业，请联系客服','/admin/login/index');
            }
        }
    }


}