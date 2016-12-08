<?php
/**
 * 文件的简短描述：微信服务器请求接口
 *
 * 文件的详细描述：微信服务器请求接口
 *
 * LICENSE:
 * @author wangzhen 2016/10/31
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\api\controller;

use app\base\model\Follow;
use think\Config;
use think\Controller;
use think\Cache;
use think\Request;
use wechat\Wechat as WechatApi;

class Wechat extends Controller {

    protected $wechatStr = 'ldd';

    public function index(){
        $request = Request::instance();
        if($request->isPost()){
            //获取配置
            $wechatConfig = Config::get($this->wechatStr);
            $wechatApi = new WechatApi($wechatConfig);
            //接收微信服务器的请求
            $requestData = $wechatApi->request();
            if($requestData['msgtype'] == 'event'){
                //该请求为事件类型
                $event = $requestData['event'];
                switch($event){
                    case 'subscribe':
                        //关注事件：获取用户信息并存入数据库
                        $openid = $requestData['fromusername']; //获取关注者openid
                        //调用接口获取用户信息
                        //获取服务端access_token
                        //先从缓存查找
                        $wechatInfo = Cache::get($this->wechatStr . '_info');
                        if( ! $wechatInfo){
                            //如果缓存中没有微信配置信息
                            $wechatAccessToken = $wechatApi->getToken();
                            if( ! $wechatAccessToken){
                                die('参数错误！');
                            }
                            $wechatConfig['access_token'] = $wechatAccessToken;
                            Cache::set($this->wechatStr . '_info',$wechatConfig,7100);
                            $wechatInfo = $wechatConfig;
                        }
                        //获取用户信息
                        $wechatApi = new WechatApi($wechatInfo);
                        $userInfo = $wechatApi->user($openid);
                        $userInfo['nickname_base64'] = base64_encode($userInfo['nickname']);
                        unset($userInfo['tagid_list']); //新接口带这个参数，暂时不需要
                        //根据openid查询用户信息，有则更新，无则新建
                        $user = Follow::get(['openid' => $openid]);
                        if( ! $user){
                            //如果没有用户信息，新建
                            Follow::create($userInfo);
                        }else{
                            //有用户信息，更新数据
                            Follow::where('openid',$openid)->update($userInfo);
                        }
                        break;
                    case 'unsubscribe':
                        //取消关注：将用户信息表中的关注状态改为未关注
                        $openid = $requestData['fromusername']; //获取关注者openid
                        //根据openid查询用户信息，有则更新，无则新建
                        $user = Follow::get(['openid' => $openid]);
                        if($user){
                            //如果有用户信息，更新状态
                            $userInfo = [
                                'subscribe' => 0,
                                'unsubscribe_time' => time()
                            ];
                            Follow::where('openid',$openid)->update($userInfo);
                        }
                        break;
                }
            }
            echo 'success';
        }else{
            echo $request->param('echostr');
        }
    }

}