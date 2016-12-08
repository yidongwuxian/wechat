<?php
/**
 * 文件的简短描述：文章管理
 *
 * LICENSE:
 * @author lijin 2016/11/25
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\admin\controller;

use think\Request;

use app\admin\model\Article as ArticleModel;
use app\admin\model\ArticleType as ArticleTypeModel;

class Article extends Base
{

    public function __construct()
    {
        parent::__construct();
        $request = Request::instance();

        $this->assign('class', 'site');
        $this->assign('subClass', $request->controller());
        $this->assign('action', $request->action());
    }

    public function index()
    {
    	$where 	= ['wechat_id' => $this->wechatId];
    	$list 	= ArticleModel::all($where);
    	$type 	= ArticleTypeModel::all($where)->toArray();

        $tree = new \Tree();
        $tree->tree($type);
        $str  = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $typeList = $tree->getTree(0, $str, 0);

    	$this->assign('list', $list);
    	$this->assign('type', array_column($type, 'name', 'id'));
        $this->assign('typeList', $typeList);
    	$this->assign('status', ArticleModel::$status);

    	return $this->fetch();
    }

    // 保存
    public function save()
    {
    	$request = Request::instance();
    	$isAjax  = $request->isAjax();
        $id 	 = intval($request->post('id'));
        $data 	 = [
            'type_id' => intval($request->post('type', 0)),
            'title'	  => trim($request->post('title', '', 'strip_tags')),
            'brief'	  => trim($request->post('brief', '', 'strip_tags')),
            'content' => $request->post('content', '', 'htmlspecialchars'),
            'keyword' => trim($request->post('keyword/a')),
            'status'  => intval($request->post('status')),
        ];

        if (empty($data['title']) || empty($data['type_id']))
        {
        	if ($isAjax)
        	{
        		return \Util::echoJson('请求参数错误');
        	}

        	$this->error("请求参数错误", 'index');
        }

        $data['keyword'] = json_encode($data['keyword']);

       	$articleObj = new ArticleModel();

        if ($id)
        {
        	$data['update_time'] = time();
            $res = $articleObj->save($data, ['wechat_id' => $this->wechatId, 'id' => $id]);
        }
        else
        {
        	$data['wechat_id'] 	 = $this->wechatId;
        	$data['create_time'] = time();
        	$data['update_time'] = time();
            $res = $articleObj->save($data);
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

    // ajax 删除
    public function delete($id = 0)
    {
    	$request = Request::instance();
    	$isAjax  = $request->isAjax();
    	$id = intval($id);
    	if (! $id || ! $isAjax)
    	{
    		return \Util::echoJson('请求参数错误');
    	}

    	$res = ArticleModel::get(['wechat_id' => $this->wechatId, 'id' => $id])->delete();

    	if (! $res)
    	{
    		return \Util::echoJson('操作失败');
    	}

    	return \Util::echoJson('操作成功', true);
    }

    // 查询详情
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

    	$info = ArticleModel::get(['wechat_id' => $this->wechatId, 'id' => $id]);
    	$info['content'] = htmlspecialchars_decode($info['content']);

    	if (! $info)
    	{
    		if ($isAjax)
        	{
        		return \Util::echoJson('系统操作失败');
        	}

        	$this->error("系统操作失败", 'index');
    	}

    	if ($isAjax)
    	{
    		return \Util::echoJson('操作成功', true, $info);
    	}

    	$type 	= ArticleTypeModel::all($where);

    	$this->assign('type', $type);
    	$this->assign('status', ArticleModel::$status);
    	$this->assign('info', $info);

    	return $this->fetch();
    }

    // 文章分类管理
    public function type()
    {
    	$list = ArticleTypeModel::all(['wechat_id' => $this->wechatId])->toArray();

        $tree = new \Tree();
        $tree->tree($list);
        $str  = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $typeList = $tree->getArray();

        unset($list);

    	$this->assign('list', $typeList);

    	return $this->fetch();
    }

    // 保存分类
    public function saveType()
    {
    	$request = Request::instance();
    	$isAjax  = $request->isAjax();
        $id 	 = intval($request->post('id'));
        $data 	 = [
            'pid' 	=> intval($request->post('pid', 0)),
            'name'	=> trim($request->post('name', '', 'strip_tags')),
            'sort'  => intval($request->post('sort', 0)),
        ];
        if (empty($data['name']))
        {
        	if ($isAjax)
        	{
        		return \Util::echoJson('请求参数错误');
        	}

        	$this->error("请求参数错误", 'index');
        }

       	$aTypeObj = new ArticleTypeModel();

        if ($id)
        {
        	$data['update_time'] = time();
            $res = $aTypeObj->save($data, ['wechat_id' => $this->wechatId, 'id' => $id]);
        }
        else
        {
        	$data['wechat_id'] 	 = $this->wechatId;
        	$data['create_time'] = time();
        	$data['update_time'] = time();
            $res = $aTypeObj->save($data);
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

    // 删除分类
    public function delType($id = 0)
    {
    	$request = Request::instance();
    	$isAjax  = $request->isAjax();
    	$id = intval($id);
    	if (! $id || ! $isAjax)
    	{
    		return \Util::echoJson('请求参数错误');
    	}

    	$arcount = ArticleModel::where(['wechat_id' => $this->wechatId, 'type_id' => $id])->count();
    	if ($arcount > 0)
    	{
    		return \Util::echoJson('该分类下还有文章，不能删除');
    	}

    	$res = ArticleTypeModel::get(['wechat_id' => $this->wechatId, 'id' => $id])->delete();

    	if (! $res)
    	{
    		return \Util::echoJson('操作失败');
    	}

    	return \Util::echoJson('操作成功', true);
    }

    // 分类详情
    public function infoType($id = 0)
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

    	$info = ArticleTypeModel::get(['wechat_id' => $this->wechatId, 'id' => $id]);

    	if (! $info)
    	{
    		if ($isAjax)
        	{
        		return \Util::echoJson('系统操作失败');
        	}

        	$this->error("系统操作失败", 'index');
    	}

    	if ($isAjax)
    	{
    		return \Util::echoJson('操作成功', true, $info);
    	}

    	$this->assign('info', $info);

    	return $this->fetch();
    }

 }