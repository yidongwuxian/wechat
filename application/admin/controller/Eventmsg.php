<?php
/**
 * 文件的简短描述：事件消息
 *
 * 文件详细描述：微信的event事件触发后，回复的消息维护。event事件有:subscribe、unsubscribe、scan、click
 * 
 * LICENSE:
 * @author lijin
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\admin\controller;

use think\Request;

use app\admin\model\EventMsg as EventMsgModel;

class Eventmsg extends Base
{
	public function __construct()
    {
        parent::__construct();
        $request = Request::instance();
        $this->assign('class', 'setting');
        $this->assign('subClass', $request->controller());
        $this->assign('action', $request->action());
        $this->imgPath  = ROOT_PATH . '/public';
    }

    public function index()
    {
    	$list = EventMsgModel::all(['wechat_id' => $this->wechatId]);

    	$this->assign('list', $list);
    	$this->assign('typeConf', EventMsgModel::$typeConf);

    	return $this->fetch();
    }

    // 查询记录详情
    public function info($id = 0)
    {
    	$request = Request::instance();
        $isAjax  = $request->isAjax();
    	$id = intval($id);
		if (! $id)
		{
			if ($isAjax)
            {
                return \Util::echoJson('请求参数错误');
            }

            $this->error("请求参数错误", 'index');
		}
		
		$info = EventMsgModel::get(['wechat_id' => $this->wechatId, 'id' => $id]);

        if (! $info)
        {
            if ($isAjax)
            {
                return \Util::echoJson('该记录不存在');
            }

            $this->error("该记录不存在", 'index');
        }

        $info['isCover']    = ! empty($info['cover']) && is_file($this->imgPath . $info['cover']) ? 1 : 0;
        $info['content']    = htmlspecialchars_decode($info['content']);
        
        if ($isAjax)
        {
             return \Util::echoJson('操作成功', true, $info);
        }

        $this->assign('typeConf', EventMsgModel::$typeConf);
		$this->assign('info', $info);
		
		return $this->fetch();
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

        $res = EventMsgModel::where(['wechat_id' => $this->wechatId, 'id' => $id])->delete();

		if (! $res)
		{
			return \Util::echoJson('操作失败');
		}
		
		return \Util::echoJson('操作成功', true);
    }

    // 提交表单
    public function save()
    {
    	$request = Request::instance();
    	$isAjax  = $request->isAjax();
        $id      = intval($request->post('id'));
        $data    = [
        	'event'  	=> trim($request->post('event', '', 'strip_tags,strtolower')),
            'type'   	=> intval($request->post('type', 0)),
            'content'  	=> trim($request->post('intro', '', 'htmlspecialchars')),
            'title'		=> trim($request->post('title', '', 'strip_tags')),
            'cover'		=> trim($request->post('cover', '', 'strip_tags')),
            'url'		=> trim($request->post('jumpUrl', '', 'strip_tags')),
            'sceneId'	=> trim($request->post('sceneId', '', 'strip_tags')),
            'update_time'=> time(),
        ];

        if (empty($data['event']) || empty($data['type']) ||
        	$data['type'] == 1 && empty($data['content']) ||
        	$data['type'] == 2 && empty($data['title']))
        {
            if ($isAjax)
            {
                return \Util::echoJson('请求参数错误');
            }

            $this->error("请求参数错误", 'index');
        }

        $eventMgsObj = new EventMsgModel();

		if ($id)
		{
			$where = [
				'wechat_id' => $this->wechatId,
				'id'		=> $id,
			];

			$res = $eventMgsObj->save($data, $where);
		}
		else
		{
        	$data['wechat_id'] 	= $this->wechatId;
        	$data['create_time']= time();
        	$res = $eventMgsObj->save($data);
		}

		if ($res === false)
        {
            if ($isAjax)
            {
                return \Util::echoJson('操作失败');
            }

            $this->error("操作失败", 'index');
        }

        if ($isAjax)
        {
            return \Util::echoJson('操作成功', true);
        }

        return $this->success('操作成功', true);
    }
}