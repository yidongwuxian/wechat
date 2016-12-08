<?php
namespace app\admin\validate;
use think\Validate;

class Account extends Validate
{
    protected $rule = [
        'wechat'  =>  'require|max:25',
        'title'   =>  'require',
        'wechat_orid'     =>  'require',
        'wechat_appid'    =>  'require',
        'wechat_appsecret'=>  'require',
        'wechat_token'    =>  'require',
    ];

    protected $message = [
        'wechat.require'          => '标志不能为空',
        'title.require'           => '名称不能为空',
        'wechat_orid.require'     => '原始ID不能为空',
        'wechat_appid.require'    => 'AppId不能为空',
        'wechat_appsecret.require'=> 'AppSecret不能为空',
        'wechat_token.require'    => '至少选择一个企业微信号',
    ];

    protected $scene = [
        'add'   =>  ['wechat','title','wechat_orid','wechat_appid','wechat_appsecret','wechat_token'],
    ];

}