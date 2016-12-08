<?php
/**
 * 文件的简短描述：自定义回复
 *
 * LICENSE:
 * @author wangzhen 2016/11/3
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\admin\controller;

use think\Request;

use app\admin\model\ReplyNews as NewsModel;
use app\admin\model\ReplyText as TextModel;
use app\admin\model\Keyword as KWModel;

class Reply extends Base
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
        $this->redirect('textlist');
    }
    // 文本消息回复列表
    public function textList()
    {
        $list = TextModel::all(['wechat_id' => $this->wechatId]);

        $this->assign('list', $list);

        return $this->fetch();
    }
    // 文本消息保存
    public function textSave()
    {
        $request = Request::instance();
        $isAjax  = $request->isAjax();
        $id      = intval($request->post('id'));
        $data    = [
            'state'     => intval($request->post('state', 0)),
            'name'      => trim($request->post('name', '', 'strip_tags')),
            'keyword'   => $request->post('keyword/a'),
            'content'   => trim($request->post('content', '', 'htmlspecialchars')),
            'start_time'=> trim($request->post('startTime')),
            'end_time'  => trim($request->post('endTime')),
        ];

        foreach ($data['keyword'] as $key => $value)
        {
            if (empty($value['key']))
            {
                unset($data['keyword'][$key]);
            }
        }

        if (empty($data['keyword']) || empty($data['name']) || empty($data['content']))
        {
            if ($isAjax)
            {
                return \Util::echoJson('请求参数错误');
            }

            $this->error("请求参数错误", 'textlist');
        }

        $keys = array_column($data['keyword'], 'key');
        $keys = array_unique(array_filter($keys));
        if (count($keys) != count($data['keyword']))
        {
            if ($isAjax)
            {
                return \Util::echoJson('关键词重复');
            }

            $this->error("关键词重复", 'textlist');  
        }

        $data['start_time'] = $data['start_time'] ? strtotime($data['start_time']) : '';
        $data['end_time']   = $data['end_time'] ? strtotime($data['end_time']) : '';
        $data['keyword']    = json_encode($data['keyword']);

        $data = array_filter($data);

        $textObj    = new TextModel();
        $keywordObj = new KWModel();

        if ($id)
        {
            $data['update_time'] = time();
            $res = $textObj->save($data, ['id' => $id, 'wechat_id' => $this->wechatId]);
        }
        else
        {
            $data['wechat_id']   = $this->wechatId;
            $data['create_time'] = time();
            $data['update_time'] = time();
            $res  = $textObj->insertGetId($data);
        }

        if ($res === false)
        {
            if ($isAjax)
            {
                return \Util::echoJson('操作失败');
            }

            $this->error("操作失败", 'textlist');
        }

        if ($id)
        {
            $keywordObj->where(['wechat_id' => $this->wechatId, 'addonModel' => KWModel::MODEL_TEXT, 'aimId' => $id])->delete();
        }
        else
        {
            $id = $res;
        }

        $rows = [];
        $keywords = json_decode($data['keyword'], true);
        foreach ($keywords as $val)
        {
            $rows[] = [
                'keyword'     => $val['key'],
                'wechat_id'   => $this->wechatId,
                'addonModel'  => KWModel::MODEL_TEXT,
                'aimId'       => $id,
                'keywordType' => $val['type'],
                'start_time'  => $data['start_time'],
                'end_time'    => $data['end_time'],
                'create_time' => time(),
                'state'       => $data['state'],
            ];
        }

        if ($rows)
        {
            $res = $keywordObj->saveAll($rows);

            if (! $res)
            {
                if ($isAjax)
                {
                    return \Util::echoJson('关联keyword添加失败', true);
                }

                $this->error("关联keyword添加失败", 'textlist');
            }
        }

        if ($isAjax)
        {
            return \Util::echoJson('操作成功', true);
        }

        $this->error("操作成功", 'textlist');
    }

    // ajax 获取详情
    public function textInfo($id = 0)
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

        $info = TextModel::get(['wechat_id' => $this->wechatId, 'id' => $id]);

        if (! $info)
        {
            if ($isAjax)
            {
                return \Util::echoJson('系统操作失败');
            }

            $this->error("系统操作失败", 'index');
        }

        $info['startTime'] = $info['start_time'] ? date('Y-m-d H:i:s', $info['start_time']) : '';
        $info['endTime']   = $info['end_time'] ? date('Y-m-d H:i:s', $info['end_time']) : '';
        $info['keyword']   = json_decode($info['keyword'], true);
        $info['content']   = htmlspecialchars_decode($info['content']);

        if ($isAjax)
        {
            return \Util::echoJson('操作成功', true, $info);
        }

        $this->assign('info', $info);

        return $this->fetch();
    }

    // ajax 删除记录
    public function textDel($id = 0)
    {
        $request = Request::instance();
        $isAjax  = $request->isAjax();
        $id = intval($id);
        if (! $id || ! $isAjax)
        {
            return \Util::echoJson('请求参数错误');
        }

        $res = TextModel::get(['wechat_id' => $this->wechatId, 'id' => $id])->delete();

        if (! $res)
        {
            return \Util::echoJson('操作失败');
        }

        $res = KWModel::where(['wechat_id' => $this->wechatId, 'aimId' => $id])->delete();

        if (! $res)
        {
            return \Util::echoJson('关联keyword删除失败');
        }

        return \Util::echoJson('操作成功', true);
    }


    // 图文消息
    public function newsList()
    {
        $list = NewsModel::all(['wechat_id' => $this->wechatId]);

        $this->assign('list', $list);

        return $this->fetch();
    }
    // 保存
    public function newsSave()
    {
        $request = Request::instance();
        $isAjax  = $request->isAjax();
        $id      = intval($request->post('id'));
        $data    = [
            'state'     => intval($request->post('state', 0)),
            'name'      => trim($request->post('name', '', 'strip_tags')),
            'keyword'   => $request->post('keyword/a'),
            'content'   => trim($request->post('content', '', 'htmlspecialchars')),
            'start_time'=> trim($request->post('startTime')),
            'end_time'  => trim($request->post('endTime')),
            'title'     => trim($request->post('title', '', 'strip_tags')),
            'cover'     => trim($request->post('cover', '', 'strip_tags')),
            'sort'      => intval($request->post('sort', 0)),
            'jumpUrl'   => trim($request->post('jumpUrl', '', 'strip_tags')),
            'author'    => trim($request->post('author', '', 'strip_tags')),
        ];

        foreach ($data['keyword'] as $key => $value)
        {
            if (empty($value['key']))
            {
                unset($data['keyword'][$key]);
            }
        }

        if (empty($data['keyword']) || empty($data['title']) || empty($data['content']) || empty($data['name']))
        {
            if ($isAjax)
            {
                return \Util::echoJson('请求参数错误');
            }

            $this->error("请求参数错误", 'newslist');
        }

        $keys = array_column($data['keyword'], 'key');
        $keys = array_unique(array_filter($keys));
        if (count($keys) != count($data['keyword']))
        {
            if ($isAjax)
            {
                return \Util::echoJson('关键词重复');
            }

            $this->error("关键词重复", 'newslist');  
        }

        $data['start_time'] = $data['start_time'] ? strtotime($data['start_time']) : '';
        $data['end_time']   = $data['end_time'] ? strtotime($data['end_time']) : '';
        $data['keyword']    = json_encode($data['keyword']);

        $data = array_filter($data);

        $textObj    = new NewsModel();
        $keywordObj = new KWModel();

        if ($id)
        {
            $data['update_time'] = time();
            $res = $textObj->save($data, ['id' => $id, 'wechat_id' => $this->wechatId]);
        }
        else
        {
            $data['wechat_id']   = $this->wechatId;
            $data['create_time'] = time();
            $data['update_time'] = time();
            $res = $textObj->insertGetId($data);
        }

        if ($res === false)
        {
            if ($isAjax)
            {
                return \Util::echoJson('操作失败');
            }

            $this->error("操作失败", 'newslist');
        }

        if ($id)
        {
            $keywordObj->where(['wechat_id' => $this->wechatId, 'addonModel' => KWModel::MODEL_NEWS, 'aimId' => $id])->delete();
        }
        else
        {
            $id = $res;
        }

        $rows = [];
        $keywords = json_decode($data['keyword'], true);
        foreach ($keywords as $val)
        {
            $rows[] = [
                'keyword'     => $val['key'],
                'wechat_id'   => $this->wechatId,
                'addonModel'  => KWModel::MODEL_NEWS,
                'aimId'       => $id,
                'keywordType' => $val['type'],
                'start_time'  => $data['start_time'],
                'end_time'    => $data['end_time'],
                'create_time' => time(),
                'state'       => $data['state'],
            ];
        }

        if ($rows)
        {
            $res = $keywordObj->saveAll($rows);
            if (! $res)
            {
                if ($isAjax)
                {
                    return \Util::echoJson('关联keyword添加失败', true);
                }

                $this->error("关联keyword添加失败", 'newslist');
            }
        }

        if ($isAjax)
        {
            return \Util::echoJson('操作成功', true);
        }

        $this->error("操作成功", 'newslist');
    }

    // ajax 获取详情
    public function newsInfo($id = 0)
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

        $info = NewsModel::get(['wechat_id' => $this->wechatId, 'id' => $id]);

        if (! $info)
        {
            if ($isAjax)
            {
                return \Util::echoJson('系统操作失败');
            }

            $this->error("系统操作失败", 'index');
        }

        $info['startTime'] = $info['start_time'] ? date('Y-m-d H:i:s', $info['start_time']) : '';
        $info['endTime']   = $info['end_time'] ? date('Y-m-d H:i:s', $info['end_time']) : '';
        $info['keyword']   = json_decode($info['keyword'], true);
        $info['content']   = htmlspecialchars_decode($info['content']);
        $info['isCover']   = ! empty($info['cover']) && is_file($this->imgPath . $info['cover']) ? 1 : 0;

        if ($isAjax)
        {
            return \Util::echoJson('操作成功', true, $info);
        }

        $this->assign('info', $info);

        return $this->fetch();
    }

    // ajax 删除记录，删除微信端图片
    public function newsDel($id = 0)
    {
        $request = Request::instance();
        $isAjax  = $request->isAjax();
        $id = intval($id);
        if (! $id || ! $isAjax)
        {
            return \Util::echoJson('请求参数错误');
        }

        $res  = NewsModel::get(['wechat_id' => $this->wechatId, 'id' => $id])->delete();
        if (! $res)
        {
            return \Util::echoJson('操作失败');
        }

        $res = KWModel::where(['wechat_id' => $this->wechatId, 'aimId' => $id])->delete();
        if (! $res)
        {
            return \Util::echoJson('关联keyword删除失败');
        }
        return \Util::echoJson('操作成功', true);
    }

}