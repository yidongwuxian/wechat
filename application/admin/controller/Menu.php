<?php
/**
 * 文件的简短描述：菜单管理
 *
 * LICENSE:
 * @author lijin 2016/11/15
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\admin\controller;

use think\Request;

use app\admin\model\Menu as MenuModel;

class Menu extends Base
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
    	$menuList = MenuModel::where(['wechat_id' => $this->wechatId])->order('level asc, sort asc')->select();

    	//处理菜单
		$newMenuList = array();
		foreach ($menuList as $menu)
		{
			if ($menu['level'] == 2)
			{
				continue;
			}
			$newMenuList[] = $menu;
			foreach ($menuList as $subMenu)
			{
				if ($subMenu['pid'] == $menu['id'])
				{
					$newMenuList[] = $subMenu;
				}
			}
		}

		$levelOneList = MenuModel::where(['wechat_id' => $this->wechatId, 'level' => 1])->order('sort asc')->select();

		$this->assign('levelOneList', $levelOneList);
		$this->assign('list', $newMenuList);

		$this->assign('typeConf', MenuModel::$typeConf);

		return $this->fetch();
    }

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
		
		$info = MenuModel::get(['wechat_id' => $this->wechatId, 'id' => $id]);

        if (! $info)
        {
            if ($isAjax)
            {
                return \Util::echoJson('该菜单不存在');
            }

            $this->error("该菜单不存在", 'index');
        }

        if ($isAjax)
        {
             return \Util::echoJson('操作成功', true, $info);
        }

        $levelOneList = MenuModel::all(['wechat_id' => $this->wechatId, 'level' => 1]);
        
        $this->assign('levelOneList', $levelOneList);
		$this->assign('info', $info);
		
		return $this->fetch();
    }

    public function delete($id = 0)
    {
    	$request = Request::instance();
        $isAjax  = $request->isAjax();
    	$id = intval($id);
		if (! $id || ! $isAjax)
		{
			return \Util::echoJson('请求参数错误');
		}

		$info = MenuModel::get(['wechat_id' => $this->wechatId, 'id' => $id]);

        if (! $info)
        {
            return \Util::echoJson('菜单不存在');
        }

        $childCount = MenuModel::where(['wechat_id' => $this->wechatId, 'pid' => $id])->count();

        if ($childCount > 0)
        {
        	return \Util::echoJson('存在子菜单，不允许删除');
        }

        $res = MenuModel::where(['wechat_id' => $this->wechatId, 'id' => $id])->delete();

		if (! $res)
		{
			return \Util::echoJson('操作失败');
		}
		
		return \Util::echoJson('操作成功', true);
    }

    public function save()
    {
    	$request = Request::instance();
    	$isAjax  = $request->isAjax();
        $id      = intval($request->post('id'));
        $data    = [
        	'name'  => trim($request->post('name', '', 'strip_tags')),
            'pid'   => intval($request->post('pid', 0)),
            'sort'  => intval($request->post('sort', 0)),
            'type'  => trim($request->post('type', '', 'strip_tags,strtolower')),
            'code'	=> trim($request->post('code', '', 'strip_tags,strtolower')),
            'level'	=> 1,
        ];

        if (empty($data['name']) || empty($data['type']))
        {
            if ($isAjax)
            {
                return \Util::echoJson('请求参数错误');
            }

            $this->error("请求参数错误", 'index');
        }

		//校验父菜单
        if ($data['pid'])
		{
			$parentMenu = MenuModel::get(['wechat_id' => $this->wechatId, 'id' => $data['pid']]);
			if (! $parentMenu)
			{
				if ($isAjax)
	            {
	                return \Util::echoJson('父菜单不存在');
	            }

	            $this->error("父菜单不存在", 'index');
			}
						
			$data['level'] = $parentMenu['level'] + 1;
		}

		$menuObj = new MenuModel();

		if ($id)
		{
			$where = [
				'wechat_id' => $this->wechatId,
				'id'		=> $id,
			];

			$res = $menuObj->save($data, $where);
		}
		else
		{
        	$data['wechat_id'] = $this->wechatId;

        	$res = $menuObj->save($data);
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

    public function createMenu()
    {
    	$request 	= Request::instance();
    	$isAjax 	= $request->isAjax();
	
		$menuList 	= MenuModel::where(['wechat_id' => $this->wechatId])->field('id,pid,name,type,code')->select();

		if (! $menuList)
		{
			if ($isAjax)
            {
                return \Util::echoJson('微信菜单为空,请先设置');
            }
			$this->error("微信菜单为空,请先设置", 'index');
		}
		
		$menuList  = $menuList->toArray();

		$sendMenu  = self::setMenu($menuList);

		$wechatObj = new \wechat\Wechat($this->corpInfo);
		$token 	= $wechatObj->getToken();
		if (! $token)
		{
			if ($isAjax)
            {
                return \Util::echoJson($wechatObj->error);
            }

			$this->error($wechatObj->error, 'index');
		}
		
		// $res 	= $wechatObj->menu_delete();
		$res 	= $wechatObj->menu_create($sendMenu);

		if (! $res)
		{
			if ($isAjax)
            {
                return \Util::echoJson('生成微信自定义菜单失败' . $wechatObj->error);
            }

			$this->error("生成微信自定义菜单失败" . $wechatObj->error);
		}
		
		if ($isAjax)
        {
            return \Util::echoJson('生成微信自定义菜单成功', true);
        }

		$this->success("生成微信自定义菜单成功");
    }

    /**
	 * 添加菜单，一级菜单最多3个，每个一级菜单最多可以有5个二级菜单
	 * @param $menuList
	 *          array(
	 *              array('id'=>'', 'pid'=>'', 'name'=>'', 'type'=>'', 'code'=>''),
	 *              array('id'=>'', 'pid'=>'', 'name'=>'', 'type'=>'', 'code'=>''),
	 *              array('id'=>'', 'pid'=>'', 'name'=>'', 'type'=>'', 'code'=>''),
	 *          );
	 *          'code'是view类型的URL或者其他类型的key
	 *          'type'是菜单类型，如下:
	 *              1、click：点击推事件，用户点击click类型按钮后，微信服务器会通过消息接口推送消息类型为event的结构给开发者（参考消息接口指南），并且带上按钮中开发者填写的key值，开发者可以通过自定义的key值与用户进行交互；
	 *              2、view：跳转URL，用户点击view类型按钮后，微信客户端将会打开开发者在按钮中填写的网页URL，可与网页授权获取用户基本信息接口结合，获得用户基本信息。
	 *              3、scancode_push：扫码推事件，用户点击按钮后，微信客户端将调起扫一扫工具，完成扫码操作后显示扫描结果（如果是URL，将进入URL），且会将扫码的结果传给开发者，开发者可以下发消息。
	 *              4、scancode_waitmsg：扫码推事件且弹出“消息接收中”提示框，用户点击按钮后，微信客户端将调起扫一扫工具，完成扫码操作后，将扫码的结果传给开发者，同时收起扫一扫工具，然后弹出“消息接收中”提示框，随后可能会收到开发者下发的消息。
	 *              5、pic_sysphoto：弹出系统拍照发图，用户点击按钮后，微信客户端将调起系统相机，完成拍照操作后，会将拍摄的相片发送给开发者，并推送事件给开发者，同时收起系统相机，随后可能会收到开发者下发的消息。
	 *              6、pic_photo_or_album：弹出拍照或者相册发图，用户点击按钮后，微信客户端将弹出选择器供用户选择“拍照”或者“从手机相册选择”。用户选择后即走其他两种流程。
	 *              7、pic_weixin：弹出微信相册发图器，用户点击按钮后，微信客户端将调起微信相册，完成选择操作后，将选择的相片发送给开发者的服务器，并推送事件给开发者，同时收起相册，随后可能会收到开发者下发的消息。
	 *              8、location_select：弹出地理位置选择器，用户点击按钮后，微信客户端将调起地理位置选择工具，完成选择操作后，将选择的地理位置发送给开发者的服务器，同时收起位置选择工具，随后可能会收到开发者下发的消息。
	 *
	 * @return bool
	 */
	private function setMenu($menuList)
	{
		//整理菜单
		$menuList2 = $menuList;
		foreach ($menuList as $key => $menu)
		{
			foreach ($menuList2 as $k2 => $menu2)
			{
				if ($menu['id'] == $menu2['pid'])
				{
					$menuList[$key]['sub_button'][] = $menu2;
					unset($menuList[$k2]);
				}
			}
		}

		unset($menulist);
		unset($menuList2);
		//处理数据
		foreach ($menuList as $key => $menu)
		{
			if ($menu['type'] == 'view')
			{
				//view 跳转url
				$menuList[$key]['url'] = urlencode($menu['code']);
			} 
			elseif ($menu['type'] == 'click')
			{
				$menuList[$key]['key'] = $menu['code'];
			}
			elseif ($menu['type'] == 'media_id' || $menu['type'] == 'view_limited')
			{
				$menuList[$key]['media_id'] = $menu['code'];
			}
			else
			{
				$menuList[$key]['key'] = $menu['code'];
				/* if (! isset($menu['sub_button']))
				{
					$menuList[$key]['sub_button'] = array();
				} */
			}
			
			unset($menuList[$key]['code']);
			unset($menuList[$key]['id']);
			unset($menuList[$key]['pid']);
			
			//处理菜单名称，用urlencode处理
			$menuList[$key]['name'] = urlencode($menu['name']);
			//处理子菜单
			if (isset($menu['sub_button']))
			{
				//有子菜单的主菜单没有type类型，需要unset掉
				unset($menuList[$key]['type']);
				foreach ($menu['sub_button'] as $sonKey => $sonMenu)
				{
					//处理type和code
					if ($sonMenu['type'] == 'view')
					{
						$menuList[$key]['sub_button'][$sonKey]['url'] = urlencode($sonMenu['code']);
					}
					elseif ($sonMenu['type'] == 'click')
					{
						$menuList[$key]['sub_button'][$sonKey]['key'] = urlencode($sonMenu['code']);
					}
					elseif ($sonMenu['type'] == 'media_id' || $sonMenu['type'] == 'view_limited')
					{
						$menuList[$key]['sub_button'][$sonKey]['media_id'] = urlencode($sonMenu['code']);
					}
					else 
					{
						$menuList[$key]['sub_button'][$sonKey]['key'] = urlencode($sonMenu['code']);
					}
					
					unset($menuList[$key]['sub_button'][$sonKey]['id']);
					unset($menuList[$key]['sub_button'][$sonKey]['pid']);
					unset($menuList[$key]['sub_button'][$sonKey]['code']);
					
					$menuList[$key]['sub_button'][$sonKey]['name'] = urlencode($sonMenu['name']);
				}
			}
		}
		//整理数据
		$data['button'] = array_values($menuList);
		return $data;
	}
}