<?php
namespace app\index\controller;

use think\Cache;
use think\Request;
use wechat\Wechat;

class Index
{
    public function index(){
        $a = Cache::get('lddtest');
        var_dump($a);
        exit;
    }
}
