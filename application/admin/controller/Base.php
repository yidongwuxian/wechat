<?php
/**
 * 文件的简短描述：基础类文件
 *
 * 文件的详细描述：基础类文件
 *
 * LICENSE:
 * @author wangzhen 2016/11/3
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Account;
use app\admin\model\User;

class Base extends Controller {

    protected $userName;
    protected $userId;
    protected $isSuper;
    protected $wechatId;
    protected $corpInfo;

    /**
     * 构造函数
     */
    public function __construct(){
        parent::__construct();
        $wechatList = $this->getWechatList();
        $this->checkLogin();
        $weChat = Account::get($this->wechatId);
        $this->assign('wechatName', $weChat['title']);
        $this->assign('isSuper', $this->isSuper);
        $this->assign('userName', $this->userName);
        $this->assign('wechatList', $wechatList);
    }

    //判断当前是否有用户登录
    public function checkLogin(){
        if (!session('user_id') && !session('wechat_id')) {
            $this->error('请先登录', '/admin/login/index');
        } elseif (session('user_id') && !session('wechat_id')) {
            $this->error('请选择要管理的企业微信号', '/admin/login/selectwechat');
        } else {
            $this->userId = session('user_id');
            $this->isSuper = session('is_super');
            $this->userName = session('user_name');
            $this->wechatId = session('wechat_id');
            $this->corpInfo = array(
                'orid'  => session('wechat_orid'),
                'appid' => session('wechat_appid'),
                'secret' => session('wechat_appsecret'),
                'token' => session('wechat_token'),
                'aeskey' => session('wechat_aeskey'),
            );
            return true;
        }
    }

    //获取当前管理员所管理的公众号列表
    public function getWechatList(){
        $userInfo = User::get(session('user_id'));
        $hasWechatIds = unserialize($userInfo['we_ids']);   //获取当前用户所管理的公众号ids
        $wechatList = Account::all($hasWechatIds);
        return $wechatList;
    }

}