<?php
/**
 * 文件的简短描述：生成二维码
 *
 * LICENSE:
 * @author lijin 2016/11/16
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\admin\controller;

use think\Request;

use app\admin\model\Qrcode as QrcodeModel;

class Qrcode extends Base
{

    public function __construct()
    {
        parent::__construct();
        $request = Request::instance();

        $this->assign('class', 'setting');
        $this->assign('subClass', $request->controller());
        $this->assign('action', $request->action());
    }

    public function index()
    {
    	$list = QrcodeModel::all(['wechat_id' => $this->wechatId]);

        $this->assign('list', $list);

        return $this->fetch();
    }

    // 提交表单
    public function save()
    {
        $request = Request::instance();
        $isAjax  = $request->isAjax();
        $data    = [
            'wechat_id' => $this->wechatId,
            'name'      => trim($request->post('name', '', 'strip_tags')),
            'type'      => intval($request->post('type', 0)),
            'sid'       => intval($request->post('sid', 0)),
        ];

        $long = intval($request->post('time'));
        $long = $long ? ($long > 604800 ? 604800 : $long) : 1800;

        if (empty($data['type']) || empty($data['sid']) || $data['sid'] > 100000 || $data['sid'] < 1)
        {
            if ($isAjax)
            {
                return \Util::echoJson('请求参数错误');
            }

            $this->error("请求参数错误", 'index');
        }
       
        $wechatObj = new \wechat\Wechat($this->corpInfo);
        $token  = $wechatObj->getToken();
        $res = $wechatObj->getQRUrl($data['sid'], $data['type'] == 1 ? true : false, $long);
        if (! $res)
        {
            if ($isAjax)
            {
                return \Util::echoJson('生成二维码失败' . $wechatObj->error);
            }

            $this->error("生成二维码失败" . $wechatObj->error, 'index');
        }

        $data['url']      = $res;
        $data['end_time'] = $data['type'] == 1 ? 0 : time() + $long - 100;

        $qrcodeObj = new QrcodeModel();

        $res = $qrcodeObj->save($data);

        if (! $res)
        {
            if ($isAjax)
            {
                return \Util::echoJson('保存记录失败');
            }

            $this->error("保存记录失败", 'index');
        }

        if ($isAjax)
        {
            return \Util::echoJson('操作成功', true);
        }

        $this->success("操作成功", 'index');
    }

    // ajax 删除记录
    public function delete($id = 0)
    {
        $request = Request::instance();
        $isAjax  = $request->isAjax();
        $id = intval($id);
        if (! $id || ! $isAjax)
        {
            return \Util::echoJson('请求参数错误');
        }

        $info = QrcodeModel::get(['wechat_id' => $this->wechatId, 'id' => $id]);

        if ($info['type'] == 1)
        {
            return \Util::echoJson('永久二维码不必删除');
        }

        $res = QrcodeModel::where(['wechat_id' => $this->wechatId, 'id' => $id])->delete();

        if (! $res)
        {
            return \Util::echoJson('操作失败');
        }
        
        return \Util::echoJson('操作成功', true);
    }

    public function shorUrl()
    {
        
    }
}