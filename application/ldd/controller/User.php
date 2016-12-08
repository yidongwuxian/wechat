<?php
/**
 * 文件的简短描述：用户类
 *
 * 文件的详细描述：用户类
 *
 * LICENSE:
 * @author wangzhen 2016/10/25
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\ldd\controller;

use app\ldd\model\Ldd;
use think\Config;
use think\Session;
use think\Validate;

class User extends Base {

    protected $lddApi;

    /**
     * 构造函数
     * User constructor.
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * 业务系统用户登录
     * @return mixed
     */
    public function login(){
        if($this->request->isAjax()){
            //用户提交的参数
            $params = [
                'mobile' => $this->request->param('mobile'),
                'password' => $this->request->param('password'),
                'from' => $this->request->param('from')
            ];
            //验证规则
            $rules = [
                'mobile|手机号' => [
                    'regex' => '/^1[3|4|5|7|8][0-9]\d{8}$/',
                    'require'
                ],
                'password|密码' => 'require|min:6',
                'from' => 'require'
            ];
            $message = [
                'mobile.regex' => '手机号格式不正确',
                'from' => '缺少必要参数'
            ];

            $validate = new Validate($rules,$message);
            if( ! $validate->check($params)){
                \Util::echoJson($validate->getError());
            }
            //校验成功，调用接口进行登录验证
            $lddModel = new Ldd(Config::get('biz_api'));
            $login = $lddModel->thirdPartyLogin($this->openid,$params['mobile'],$params['password']);
            if(is_array($login) && $login['success'] == true){
                //登录成功，将用户信息记人session
                Session::set('login',true); //当前登录状态
                $loginInfo = $login['data'];
                $mobile = $this->request->param('mobile');
                $loginInfo['mobile'] = $mobile;
                Session::set('loginInfo',$loginInfo); //登录信息
                Session::set('mobile',$mobile); //手机号记录session，后续会当用户标识使用
                //判断是否需要重置密码
                $defaultPassword = Config::get('default_password');
                if($this->request->param('password') == $defaultPassword){
                    $msg = '首次登陆，请修改初始密码！';
                    $ext = '/ldd/user/changepassword';
                }else{
                    $msg = '登录成功！';
                    $from = $this->request->param('from');
                    if($from != -1){
                        //有页面来源，跳转到来源页面
                        $ext = base64_decode($from);
                    }else{
                        //微信没有页面来源情况
                        $ext = '';
                    }
                }
                \Util::echoJson($msg,true,$ext);
            }elseif (is_array($login) && $login['success'] == false){
                //登录失败
                \Util::echoJson($login['msg']);
            }else{
                \Util::echoJson($this->defaultErrorMessage);
            }
            return false;
        }else{
            $from = $this->request->get('from');
            if( ! $from){
                //如果没有页面来源
                if($this->appMode == 'wechat'){
                    //如果是微信模式，-1表示没有页面来源
                    $from = -1;
                }else{
                    $from = base64_encode($this->defaultUri);
                }
            }
            $this->assign('from',$from);
            return $this->fetch();
        }
    }

    /**
     * Ajax获取用户状态信息
     */
    public function info(){
        if($this->request->isAjax()){
            $userInfo = Session::get('loginInfo');
            if( ! $userInfo){
                \Util::echoJson('用户状态信息失效！',false,$this->defaultUri);
            }
            $userInfo['type'] = $userInfo['type'] == 0 ? 2 : $userInfo['type'];
            //如果有用户信息
            \Util::echoJson('获取用户状态信息成功！',true,$userInfo);
        }
    }

    public function myCenter(){
        $userInfo = Session::get('loginInfo');
        if( ! $userInfo){
            $this->error('用户状态信息失效！',$this->defaultUri);
        }
        $this->assign('userInfo',$userInfo);
        return $this->fetch();
    }

    public function myPoint(){
        return $this->fetch();
    }


    public function logout(){
        Session::clear();
        $this->success('成功登出！',$this->defaultUri);
    }

    //用户修改密码接口
    public function changePassword(){
        if ($this->request->isAjax()) {
            //用户提交的参数
            $params = [
                'mobile' => $this->request->param('mobile'),    //手机号
                'code'   => $this->request->param('code'),      //验证码
                'password'  => $this->request->param('password'),   //密码
                'password2' => $this->request->param('password2'),  //确认密码
            ];
            //验证规则
            $rules = [
                'mobile|手机号' => [
                    'regex' => '/^1[3|4|5|7|8][0-9]\d{8}$/',
                    'require'
                ],
                'code|验证码' => 'require|length:6',
                'password|密码' => 'require|min:6',
                'password2'  => 'confirm:password',
            ];
            $message = [
                'mobile.regex' => '手机号格式不正确',
                'code.length'      => '验证码格式错误',
                'password.min'     => '密码至少6位',
                'password2.confirm'=> '两次密码不一致',
            ];
            $validate = new Validate($rules,$message);
            if ( !$validate->check($params)) {
                \Util::echoJson($validate->getError());
            }

            //校验通过，调取修改密码接口
            $mobile = Session::get('mobile');
            $lddModel = new Ldd($this->bizApi, $mobile);
            $params['password'] = $lddModel->passwordAes($this->request->param('password'));   //新密码加密
            $res = $lddModel->forgetPassword($params);
            if(is_array($res) && $res['success'] == true){
                \Util::echoJson('修改成功', true, '/ldd/user/mycenter');
            }elseif (is_array($res) && $res['success'] == false){
                //修改失败
                \Util::echoJson($res['msg']);
            }else{
                \Util::echoJson($this->defaultErrorMessage);
            }
            return false;

        } else {
            return $this->fetch();
        }
    }

    /**
     * 用户修改密码获取验证码接口
     */
    public function getCode(){
        if ($this->request->isAjax()) {
            //用户提交的参数
            $params = ['mobile' => $this->request->param('mobile')];
            //验证规则
            $rules = [
                'mobile|手机号' => [
                    'regex' => '/^1[3|4|5|7|8][0-9]\d{8}$/',
                    'require'
                ]
            ];
            $message = [
                'mobile.regex' => '手机号格式不正确'
            ];
            //校验
            $validate = new Validate($rules, $message);
            if ( !$validate->check($params)) {
                \Util::echoJson($validate->getError());
            }
            //校验通过，调取发送验证码接口
            $mobile = Session::get('mobile');
            $lddModel = new Ldd($this->bizApi, $mobile);
            $res = $lddModel->getVerificationCode($params['mobile']);

            if(is_array($res) && $res['success'] == true){
                \Util::echoJson('发送成功', true);
            }elseif (is_array($res) && $res['success'] == false){
                //发送失败
                \Util::echoJson($res['msg']);
            }else{
                \Util::echoJson($this->defaultErrorMessage);
            }

        }
    }

    public function pInfo(){
        $userInfo = Session::get('loginInfo');
        var_dump($userInfo);
    }

}