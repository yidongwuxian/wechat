<?php
/**
 * 文件的简短描述：老舍房贷基础类
 *
 * 文件的详细描述：老舍房贷基础类
 *
 * LICENSE:
 * @author wangzhen 2016/10/25
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\ldd\controller;

use app\base\controller\Base as BaseController;
use app\ldd\model\Ldd;
use think\Config;
use think\Session;

class Base extends BaseController {

    protected $wechatStr = 'ldd';

    protected $defaultErrorMessage = '';

    protected $defaultUri = '';

    protected $appMode = 'wechat';

    protected $bizApi; //业务接口地址

    public function __construct(){
        //读取配置
        $bizMode = Config::get('biz_mode');
        $isDevMode = false; //是否为开发模式，只有在dev或test模式下才能开启
        if($bizMode == 'dev' || $bizMode == 'test'){
            $isDevMode = Config::get('is_dev_mode');
        }
        $this->appMode = Config::get('app_mode');
        parent::__construct($this->wechatStr,$this->appMode,$isDevMode); //开发者模式

        //App模式
        if($this->appMode == 'app'){
            //只有在app模式下，才需要默认页面，wechat模式赋值为空，浏览器做关闭操作
            $this->defaultUri = Config::get('default_uri');
        }

        //根据当前请求验证登录
        //var_dump($this->request->action());die;
        if($this->request->controller() == 'User' && $this->request->action() == 'login'){
            //登录不做校验
        }else if($this->request->controller() == 'User' && $this->request->action() == 'logout'){
            //登出不做校验
        }else if($this->request->controller() == 'Index' && $this->request->action() == 'index'){
            //主页不做校验
        }else{
            $uri = $this->request->url();
            $this->_checkLogin($uri);
        }

        //读取默认错误配置
        $this->defaultErrorMessage = Config::get('default_error_message');
        //业务接口地址
        $this->bizApi = Config::get('biz_api');

        //将版本号记入trace信息
        trace('版本号：' . Config::get('version'),'debug');
        $this->assign('version',time());
    }


    /**
     * 检测登录
     * @param string $uri
     */
    private function _checkLogin($uri = ''){
        //检测登录session
        $isLogin = Session::get('login');
        if( ! $isLogin){
            //如果未登录则跳转到登录页面
            $uri = base64_encode($uri);
            $this->redirect('/ldd/user/login?from=' . $uri);
        }
    }

}