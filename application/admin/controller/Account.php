<?php
namespace app\admin\controller;

use app\admin\model\Account as AccountModel;
use think\Controller;
use think\Request;

class Account extends Base
{

    public function index()
    {
        $list = AccountModel::all();
        $this->assign('list', $list);
        $this->assign('class', 'account');
        $this->assign('subClass', 'index');
        return $this->fetch();
    }

    public function add()
    {
        $request = Request::instance();
        if ($request->isAjax()){
            $AccountModel = new AccountModel();
            $isSet = $AccountModel::get(['wechat'=>$request->post('wechat')]);
            if (!empty($isSet)) {
                \Util::echoJson('公众号标志已存在');
            }
            //接收POST参数
            $data = [
                'wechat'          => $request->post('wechat'),
                'title'           => $request->post('title'),
                'wechat_orid'     => $request->post('wechat_orid'),
                'wechat_appid'    => $request->post('wechat_appid'),
                'wechat_appsecret'=> $request->post('wechat_appsecret'),
                'wechat_token'    => $request->post('wechat_token'),
                'wechat_aeskey'   => $request->post('wechat_aeskey'),
                'wechat_type'     => $request->post('wechat_type'),
                'wechat_callback' => $request->post('wechat_callback'),
                'status'          => $request->post('status'),
            ];
            //校验参数
            $result = $this->validate($data, 'Account.add');
            if (true !== $result) {
                \Util::echoJson($result);
            }
            //校验通过，保存数据
            $AccountModel->data($data);
            if ($AccountModel->save()){
                \Util::echoJson('创建成功！', true);
            } else {
                \Util::echoJson($AccountModel->getError());
            }
        } else {
            $this->assign('class', 'account');
            $this->assign('subClass', 'index');
            return $this->fetch();
        }
        return false;
    }

    public function edit($id = 0){
        $AccountModel = new AccountModel();
        $request = Request::instance();
        if ($request->isAjax()) {
            if ($request->post('wechat') != $request->post('old_wechat')) {
                $isSet = $AccountModel::get(['wechat'=>$request->post('wechat')]);
                if (!empty($isSet)) {
                    \Util::echoJson('公众号标志已存在');
                }
            }
            //接收POST参数
            $data = [
                'wechat'          => $request->post('wechat'),
                'title'           => $request->post('title'),
                'wechat_orid'     => $request->post('wechat_orid'),
                'wechat_appid'    => $request->post('wechat_appid'),
                'wechat_appsecret'=> $request->post('wechat_appsecret'),
                'wechat_token'    => $request->post('wechat_token'),
                'wechat_aeskey'   => $request->post('wechat_aeskey'),
                'wechat_type'     => $request->post('wechat_type'),
                'wechat_callback' => $request->post('wechat_callback'),
                'status'          => $request->post('status'),
            ];
            //校验参数
            $result = $this->validate($data, 'Account.add');
            if (true !== $result) {
                \Util::echoJson($result);
            }
            //校验通过，保存数据
            $id = $request->post('id');
            if ($AccountModel->save($data, ['id' => $id])) {
                \Util::echoJson('编辑成功！', true);
            } else {
                \Util::echoJson($AccountModel->getError());
            }
        } else {
            //查询数据
            $Account = $AccountModel->get($id);
            if ( ! $Account) {
                $this->error('页面不存在！');
            }
            $this->assign('account', $Account);
            return $this->fetch();
        }
        return false;
    }

    public function delete($id = 0){
        $request = Request::instance();
        if ($request->isAjax()) {
            $AccountModel = new AccountModel();
            $data = $AccountModel->get($id);
            if ($data->delete()) {
                \Util::echoJson('删除成功！',true);
            } else {
                \Util::echoJson($AccountModel->getError());
            }
        }
    }

}
