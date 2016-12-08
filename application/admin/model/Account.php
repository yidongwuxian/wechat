<?php
namespace app\admin\model;
use think\Model;

class Account extends Model
{
    protected $table = 'wechat_account';

    //根据id获取公众号信息
    public function getWechatByIds($ids){
        $result = Account::all($ids);
        return $result->toArray();
    }
}