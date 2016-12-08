<?php
namespace app\admin\validate;
use think\Validate;

class User extends Validate
{
    protected $rule = [
        'user_name'  =>  'require|max:25',
        'password'   =>  'require|min:6',
        'password2'  =>  'confirm:password',
        'email'      =>  'email',
        'we_ids'     =>  'require',
        'status'     =>  'require',
    ];

    protected $message = [
        'user_name.require'=> '用户名不能为空',
        'user_name.max'    => '用户名不能超过25个字符',
        'password.require' => '密码不能为空',
        'password.min'     => '密码至少6位',
        'password2.confirm'=> '两次密码不一致',
        'email'            => '邮箱格式错误',
        'we_ids.require'   => '至少选择一个公众号',
        'status.require'   => '请选择状态',
    ];

    protected $scene = [
        'edit'   =>  ['status','email'],
        'login'  =>  ['user_name','password'],
    ];

}