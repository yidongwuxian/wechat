<?php
namespace app\admin\controller;

use think\Request;
use app\admin\model\User as UserModel;
use app\admin\model\Account;

/**
 * 类名：User
 *
 * 类的详细描述：后台企业用户控制器类
 *
 * LICENSE:
 * @author zhangpeng 2016/11/09
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
class User extends Base
{
    public function __construct(){
        parent::__construct();
        if(empty($this->isSuper))
            $this->error('权限错误');
        $this->assign('class','user');
        $this->assign('subClass','user_index');
    }

    /**
     * 后台管理员列表
     */
    public function index(){
        $list = UserModel::all(function($query){
            $query->order('id', 'desc');
        });
        $accountModel = new Account();
        foreach ( $list as $key=>$value ) {
            $weInfo = $accountModel->getWechatByIds(unserialize($value['we_ids']));
            if ( !empty($weInfo)) {
                $weName = [];
                foreach ( $weInfo as $v ) {
                    $weName[$key][] = $v['title'];
                }
                $value['we_name'] = implode(',', $weName[$key]);
            } else {
                $value['we_name'] = '';
            }
        }
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 添加公众号管理员
     * 密码由6位以上密码+随机生成的8位密钥 MD5
     */
    public function add(){
        $request = Request::instance();
        if ($request->isAjax()) {
            //接收POST参数
            $data = array(
                'user_name'   => trim($request->post('user_name')),
                'password'    => trim($request->post('password')),
                'password2'   => trim($request->post('password2')),
                'email'       => trim($request->post('email')),
                'we_ids'      => serialize(explode(',', $request->post('we_ids'))),
                'nick_name'   => trim($request->post('nick_name')),
                'status'      => trim($request->post('status')),
                'create_time' => time(),
            );
            //校验参数
            $checkRet = $this->validate($data, 'User');
            if (true !== $checkRet) {
                \Util::echoJson($checkRet);
            }
            $data['verify_key'] = \Util::random(8);
            $encryptData = \Util::encrypt($data['password'], $data['verify_key']);
            $data['password'] = $encryptData;

            $userModel = new UserModel();
            $result = $userModel->checkRegister($data);
            if ($result !== true) {
                \Util::echoJson($result);
            }
            //校验通过，保存数据
            $userModel->data($data);
            if ($userModel->allowField(true)->save()) {
                \Util::echoJson('添加成功！', true);
            } else {
                \Util::echoJson($userModel->getError());
            }
        } else {
            $wechatData = Account::all(['status'=>1]);
            $this->assign('wechatData', $wechatData);
            return $this->fetch();
        }
    }

    /**
     * 修改公众号管理员
     * 如果要修改密码，先验证新密码格式，再生成8位新密钥+新密码 MD5
     * @param $id
     * @return bool|string
     */
    public function edit($id){
        $userModel = model('User');
        $request = Request::instance();
        if ($request->isAjax()) {
            //接收POST参数
            $data = array(
                'email'       => trim($request->post('email')),
                'we_ids'      => trim($request->post('we_ids')),
                'nick_name'   => trim($request->post('nick_name')),
                'status'      => trim($request->post('status')),
                'update_time' => time(),
            );
            //校验参数
            $checkRet = $this->validate($data, 'User.edit');
            if (true !== $checkRet) {
                \Util::echoJson($checkRet);
            }
            //如果要修改密码
            if (($request->post('password')) != null) {
                $password = $request->post('password');
                $password2 = $request->post('password2');
                if(strlen($password) < 6)
                    \Util::echoJson('密码至少6位');
                if($password != $password2)
                    \Util::echoJson('两次密码不一致');
                //生成新密码
                $data['verify_key'] = \Util::random(8);
                $encryptData = \Util::encrypt($password, $data['verify_key']);
                $data['password'] = $encryptData;
            }
            $data['we_ids'] = serialize( explode(',', $request->post('we_ids')) );
            //校验通过，保存数据
            $id = $request->post('id');
            if ($userModel->save($data, ['id' => $id])) {
                \Util::echoJson('编辑成功！', true);
            } else {
                \Util::echoJson($userModel->getError());
            }
        } else {
            //查询数据
            $data = $userModel->get($id);
            if (!$data) {
                $this->error('页面不存在！');
            }
            $wechatData = Account::all(['status'=>1]);
            $hasWechatIds = (!empty($data['we_ids'])) ? unserialize($data['we_ids']) : array();
            $this->assign('data', $data);
            $this->assign('wechatData', $wechatData);
            $this->assign('hasWechatIds', $hasWechatIds);
            return $this->fetch();
        }
    }

    public function delete($id){
        $request = Request::instance();
        if($request->isAjax()){
            $userModel = UserModel::get($id);
            if($userModel->delete()){
                \Util::echoJson('删除成功！',true);
            }else{
                \Util::echoJson($userModel->getError());
            }
        }
    }

}
