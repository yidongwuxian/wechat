<?php
namespace app\admin\controller;

use app\admin\model\Follow as FollowModel;
use think\Controller;

class Follow extends Base
{

    public function index()
    {
        $list = FollowModel::all(['wechat_id' => $this->wechatId])->toArray();
        $sexArr = [ 0=>'未知', 1=>'男', 2=>'女' ];
        $this->assign('list', $list);
        $this->assign('class', 'follow');
        $this->assign('sexArr', $sexArr);
        return $this->fetch();
    }


}
