<?php
/**
 * 文件的简短描述：模板管理
 * 
 * LICENSE:
 * @author lijin
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\admin\controller;

use think\Request;

use app\admin\model\Template as TModel;

class Template extends Base
{
	public function __construct()
    {
        parent::__construct();
        $request = Request::instance();
        $this->assign('class', 'setting');
        $this->assign('subClass', $request->controller());
        $this->assign('action', $request->action());
    }

    // 模板列表
    public function index()
    {
    	$list = TModel::where(['wechat_id' => $this->wechatId])->order('id desc')->select();

    	$this->assign('list', $list);

    	return $this->fetch();
    }

    /**
     * ajax 保存 
     * @return mix
     */
    public function save()
    {
    	$request = Request::instance();
    	$isAjax  = $request->isAjax();
        $id 	 = intval($request->post('id'));
        $data 	 = [
            'title'	  => trim($request->post('title', '', 'strip_tags')),
            'tplid'	  => trim($request->post('tplid', 0)),
            'content' => trim($request->post('content', '', 'htmlspecialchars')),
            'color'   => trim($request->post('color')),
            'top_color' => trim($request->post('topColor')),
            'tpl_num'   => intval($request->post('tplNum')),
        ];

        if (empty($data['title']) || empty($data['tplid']) || 
        	empty($data['content']))
        {
        	if ($isAjax)
        	{
        		return \Util::echoJson('请求参数错误');
        	}

        	$this->error("请求参数错误", 'index');
        }

       	$TObj = new TModel();

        if ($id)
        {
        	$data['update_time'] = time();
            $res = $TObj->save($data, ['id' => $id]);
        }
        else
        {
            $data['tpl_num']     = $data['tpl_num'] ? $data['tpl_num'] : TModel::where(['wechat_id' => $this->wechatId])->count() + 1;
        	$data['wechat_id'] 	 = $this->wechatId;
        	$data['create_time'] = time();
        	$data['update_time'] = time();
            $res = $TObj->save($data);
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

        $this->error("操作成功", 'index');
    }

    /**
     * 获取详情
     * @param  integer $id [description]
     * @return mix
     */
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

    	$info = TModel::get(['wechat_id' => $this->wechatId, 'id' => $id]);

    	if (! $info)
    	{
    		if ($isAjax)
        	{
        		return \Util::echoJson('系统操作失败');
        	}

        	$this->error("系统操作失败", 'index');
    	}

        $info['content'] = htmlspecialchars_decode($info['content']);

    	if ($isAjax)
    	{
    		return \Util::echoJson('操作成功', true, $info);
    	}

    	$this->assign('info', $info);

    	return $this->fetch();
    }
    
    /**
     * ajax 删除模板
     * @param  integer $id [description]
     * @return json
     */
    public function delete($id = 0)
    {
    	$request = Request::instance();
    	$isAjax  = $request->isAjax();
    	$id = intval($id);
    	if (! $id || ! $isAjax)
    	{
    		return \Util::echoJson('请求参数错误');
    	}

        $info = TModel::get(['wechat_id' => $this->wechatId, 'id' => $id]);

        if (! $info)
        {
            return \Util::echoJson('记录不存在');
        }

        // 微信端删除
        $resMsg = '';
        if ($info['tplid'])
        {            
            $wechatObj  = new \wechat\Wechat($this->corpInfo);
            $token      = $wechatObj->getToken();
            $wxDelRes   = $wechatObj->deleteTemplate(['template_id' => $info['tplid']]);
            if (! $wxDelRes)
            {
                $resMsg = $wechatObj->encode . $wechatObj->error;
            }
        }

    	$res = TModel::get(['wechat_id' => $this->wechatId, 'id' => $id])->delete();

    	if (! $res)
    	{
    		return \Util::echoJson('操作失败 ' . $resMsg);
    	}

    	return \Util::echoJson('操作成功 ' . $resMsg, true);
    }

    /**
     * 同步微信端模板到本地
     * 模板消息以微信端为准
     * @return [type] [description]
     */
    public function syncTemplate()
    {
        $list = TModel::all(['wechat_id' => $this->wechatId])->toArray();
        $list = array_column($list, null, 'tplid');

        $wechatObj  = new \wechat\Wechat($this->corpInfo);
        $token      = $wechatObj->getToken();
        $wxList     = $wechatObj->getTemplateList();
        $tplNum     = count($list);

        $wxTplIds   = [];
        $TemplateObj = new TModel();

        $msg = '';

        foreach ($wxList['template_list'] as $key => $value)
        {
            $wxTplIds[] = $value['template_id'];

            $data = [
                'title'         => $value['title'],
                'content'       => $value['content'],
                'tplid'         => $value['template_id'],
                'update_time'   => time(),
                'wechat_id'     => $this->wechatId,
            ];

            if (isset($list[$value['template_id']]))
            {
                $data['id'] = $list[$value['template_id']]['id'];
                $res = $TemplateObj->data($data, true)->isUpdate(true)->save();
            }
            else
            {
                $tplNum += 1;
                $data['tpl_num']    = $tplNum;
                $data['wechat_id']  = $this->wechatId;
                $data['create_time']= time();

                $res = $TemplateObj->data($data, true)->isUpdate(false)->save();
            }

            if ($res === false)
            {
                $msg .= "模板{$value['template_id']}同步失败";
            }
        }
        
        $delArr = array_diff(array_keys($list), $wxTplIds);

        if (! $delArr)
        {
            return \Util::echoJson('同步成功！' . $msg, true);
        }

        $res = $TemplateObj->where(['wechat_id' => $this->wechatId, 'tplid' => ['in', $delArr]])->delete();
        
        if (! $res)
        {
            return \Util::echoJson('本地差异模板删除失败!'  . $msg);
        }
        
        return \Util::echoJson('同步成功！' . $msg, true);
    }

}
