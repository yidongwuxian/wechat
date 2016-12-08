<?php
/**
 * 文件的简短描述：基础类
 *
 * 文件的详细描述：基础类
 *
 * LICENSE:
 * @author wangzhen 2016/11/9
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\base\controller;

use app\base\model\Follow;
use think\Cache;
use think\Config;
use think\Controller;
use think\Request;
use think\Session;
use wechat\Wechat;

class Base extends Controller
{
    protected $openid = '';
    protected $wechatUserInfo;

    /**
     * 构造函数
     * @param string $wechatStr - 公众号标识
     * @param string $appMode - 应用模式
     * @param bool $isDevMode - 是否为开发模式，开发模式不从微信端获取用户信息
     * @throws \think\Exception
     */
    public function __construct($wechatStr = '',$appMode = 'wechat',$isDevMode = false)
    {
        parent::__construct();
        if($appMode == 'wechat'){
            //微信模式
            if( ! $isDevMode){
                //获取session当前用户openid
                $openid = Session::get('openid');
                if (!$openid) {
                    //如果没有openid，网页授权获取用户信息
                    //获取微信号微信号配置
                    $wechatConfig = Config::get($wechatStr);
                    $wechatApi = new Wechat($wechatConfig);
                    //获取access_token
                    $accessToken = $wechatApi->getOauthAccessToken();
                    if (!$accessToken) {
                        //如果返回false
                        //获取当前URI
                        $request = Request::instance();
                        $callbackUri = $request->url(true);
                        $redirectUri = $wechatApi->getOAuthRedirect($callbackUri);
                        Header("Location: $redirectUri");
                        exit;
                    } else {
                        //获取openid
                        $openid = $accessToken['openid'];

                        //获取服务端access_token
                        //先从缓存查找
                        $wechatInfo = Cache::get($wechatStr . '_info');
                        if (!$wechatInfo) {
                            //如果缓存中没有微信配置信息
                            $wechatAccessToken = $wechatApi->getToken();
                            if (!$wechatAccessToken) {
                                die('参数错误！');
                            }
                            $wechatConfig['access_token'] = $wechatAccessToken;
                            Cache::set($wechatStr . '_info', $wechatConfig, 7100);
                            $wechatInfo = $wechatConfig;
                        }

                        //获取用户信息
                        $wechatApi = new Wechat($wechatInfo);
                        $userInfo = $wechatApi->user($openid);
                        $userInfo['nickname_base64'] = base64_encode($userInfo['nickname']);
                        unset($userInfo['tagid_list']); //新接口带这个参数，暂时不需要
                        //根据openid查询用户信息，有则更新，无则新建
                        $user = Follow::get(['openid' => $openid]);
                        if (!$user) {
                            //如果没有用户信息，新建
                            Follow::create($userInfo);
                        } else {
                            //有用户信息，更新数据
                            Follow::where('openid', $openid)->update($userInfo);
                        }
                        //用户信息记入session
                        Session::set('openid', $openid);
                        Session::set('wechat_user_info', $userInfo);
                    }
                }
            }else{
                $openid = 'o6fF8s6Xu_I5WmCQtnxVvzZXXov8';
            }
        }elseif ($appMode == 'app'){
            //app模式
            $openid = '-1';
        }else{
            $openid = '-1';
        }
        $this->openid = $openid;
        //$this->wechatUserInfo = Session::get('wechat_user_info');
        //var_dump(Session::get('wechat_user_info'));
    }
}