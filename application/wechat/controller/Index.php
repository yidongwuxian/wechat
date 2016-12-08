<?php
/**
 * 文件的简短描述：微信相关事件入口
 *
 * LICENSE:
 * @author lijin 2016/11/14
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\wechat\controller;

use think\Request;
use think\Controller;
use app\admin\model\Account as AccountModel;

class Index extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // /wechat/index/index/chatId/1
    public function index()
    {
        $request = Request::instance();
        $chatId  = intval($request->param('chatId'));
        if (! $chatId)
        {
            exit('param err');
        }

        $chatInfo = AccountModel::get(['id' => $chatId]);
        if (! $chatInfo)
        {
            exit('param err');
        }
        
        // 校验是否来自微信后台
        if (isset($_GET['echostr']) && \wechat\WechatLib::validateSignature($chatInfo['wechat_token']))
        {
            echo $_REQUEST['echostr'];
            exit;
        }

        // 事件分发
        $wechatObj = new \wechat\Events($chatId, true);
        echo $wechatObj->run();
        exit;
    }

}