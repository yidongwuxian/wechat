<?php
/**
 * 微信事件入口
 */
namespace wechat;

use think\Log;

use app\admin\model\Account as AccountModel;
use app\admin\model\Follow as FollowModel;
use app\admin\model\EventMsg as EMsgModel;
use app\admin\model\ReplyText as TextModel;
use app\admin\model\ReplyNews as NewsModel;
use app\admin\model\Keyword as KWModel;

class Events
{
	public $debug = false;
	
	public $request;
	
	public $chatId; 
	
	public $chatConfig;

	public function __construct($chatId, $debug = false)
	{
		$chatId = intval($chatId);

		if (! $chatId)
		{
			return false;
		}

		$chatInfo = AccountModel::get(['id' => $chatId]);
		if (! $chatInfo)
		{
			return false;
		}

		$this->chatConfig = [
            'appid'     => $chatInfo['wechat_appid'],
            'secret'	=> $chatInfo['wechat_appsecret'],
            'token' 	=> $chatInfo['wechat_token'],
            'aeskey' 	=> $chatInfo['wechat_aeskey'],
            'debug'		=> $debug,
        ];
		
		$this->chatId = $chatId;
		$this->debug  = $debug;
	}

	public function run()
	{
		$this->request = WechatLib::decryptRequest($this->chatConfig['appid'], $this->chatConfig['token'], $this->chatConfig['aeskey']);

        if (! $this->request)
		{
			if ($this->debug)
			{
				Log::record("[{$this->chatId} request] no request");
			}
			exit;
		}
		
		if ($this->debug)
		{
			Log::record(var_export($this->request, true));
		}
		
		//回复消息
		$responseMsg = '';
		
		//分发请求
		switch ($this->request['msgtype'])
		{
			case 'event':  //事件
				$event = strtolower($this->request['event']);
				switch ($event)
				{
					case 'subscribe':
						$responseMsg = $this->subscribe();
						break;
					case 'unsubscribe':
						$responseMsg = $this->unSubscribe();
						break;
					case 'scan':
						$responseMsg = $this->scanForParam();
						break;
					case 'location':
						$responseMsg = RequestLib::eventLocation($this->request);
						break;
					case 'click':
						$responseMsg = $this->eventClick();
						break;
					case 'view':
						$responseMsg = RequestLib::eventView($this->request);
						break;
				}
				break;
			case 'text':  //文本
				$responseMsg = $this->textResponse();
				break;
			case 'image': //图片
				break;
			case 'voice': //语音
				break;
			case 'video': //视频
				break;
			case 'shortvideo':  //小视频
				break;
			case 'location':
				break;
			case 'link':
				break;
			default:
				break;
		}
		
		if ($this->debug)
		{
			Log::record("[{$this->chatId} error] [Response]: {$responseMsg}");
		}
		
		return WechatLib::encryptMsg($this->chatConfig['appid'], $this->chatConfig['token'], $this->chatConfig['aeskey'], $responseMsg);
	}

	// 关注
	public function subscribe()
	{
		if (isset($this->request['eventkey']) && $this->request['eventkey'])
		{
			//扫描二维码关注
			return self::subscribeForParam();
		}
	
		//将用户添加至关注粉丝表
		$res = $this->addUserToFollow();

		// 关注推送消息
		$now = time();
		$where = [
			'wechat_id' => $this->chatId,
			'event' 	=> EMsgModel::MODEL_SUBSCRIBE,
		];

		$msg = EMsgModel::get($where);

		if (!$msg || $msg && $msg['type'] == 1)
		{
			$content = $msg && $msg['content'] ? htmlspecialchars_decode($msg['content']) : '感谢您的关注';

			return RequestLib::text($this->request, $content);
		}
	
		$article = [
            'title' 		=> $msg['title'],
            'description' 	=> htmlspecialchars_decode($msg['content']),
            'picurl' 		=> empty($msginfo['cover']) ? '' : "http://" . $_SERVER['HTTP_HOST'] . $msg['cover'],
            'url' 			=> $msg['url'],
        ];

		return RequestLib::article($this->request, $article);
	}

	// 取消关注
	public function unsubscribe()
	{
		if (! $this->request)
	    {
	        return false;
	    }

		$data = [
			'unsubscribe_time'	=> time(),
			'subscribe'			=> 0,
		];

		$where = [
	    	'wechat_id' => $this->chatId,
	    	'openid'	=> $this->request['fromusername'],
	    ];

		$followObj  = new FollowModel();		
		$res = $followObj->save($data, $where);		
		
		return ;
	}

	//扫描二维码关注
	public function subscribeForParam()
	{
		$eventKey = $this->request['eventkey'];
		if (false !== strpos($eventKey, 'qrscene'))
	    {
	        $eventKeyAry = explode("_", $eventKey);
	        $sceneId 	 = $eventKeyAry[1];
	    }
	    else
	    {
	        $sceneId = $eventKey;
	    }

	    $res = $this->addUserToFollow();

	    if (! $sceneId)
	    {
	    	return '';
	    }

	    $msg = EMsgModel::get([
	    	'wechat_id' => $this->chatId,
	    	'event' 	=> EMsgModel::MODEL_SCANSCRIBE,
	    	'sceneId'	=> $sceneId,
	    ]);

	    if (! $msg || $msg && $msg['type'] == 1)
		{
			$content = $msg && $msg['content'] ? htmlspecialchars_decode($msg['content']) : '';
			
			if (! $content)
			{
				return '';
			}

			return RequestLib::text($this->request, $content);
		}

		$article = [
            'title' 		=> $msg['title'],
            'description' 	=> htmlspecialchars_decode($msg['content']),
            'picurl' 		=> empty($msginfo['cover']) ? '' : "http://" . $_SERVER['HTTP_HOST'] . $msg['cover'],
            'url' 			=> $msg['url'],
        ];

		return RequestLib::article($this->request, $article);
	}

	// 用户已关注扫描二维码
	// 具体业务端根据情况重写该方法
	public function scanForParam()
	{
		$eventKey = $this->request['eventkey'];
		if (false !== strpos($eventKey, 'qrscene'))
	    {
	        $eventKeyAry = explode("_", $eventKey);
	        $sceneId 	 = $eventKeyAry[1];
	    }
	    else
	    {
	        $sceneId = $eventKey;
	    }

	    $res = $this->addUserToFollow();

	    if (! $sceneId)
	    {
	    	return '';
	    }

		$msg = EMsgModel::get([
	    	'wechat_id' => $this->chatId,
	    	'event' 	=> EMsgModel::MODEL_SCANPARAM,
	    	'sceneId'	=> $sceneId,
	    ]);

		if (! $msg || $msg && $msg['type'] == 1)
		{
			$content = $msg && $msg['content'] ? htmlspecialchars_decode($msg['content']) : '';
			
			if (! $content)
			{
				return '';
			}

			return RequestLib::text($this->request, $content);
		}

		$article = [
            'title' 		=> $msg['title'],
            'description' 	=> htmlspecialchars_decode($msg['content']),
            'picurl' 		=> empty($msg['cover']) ? '' : "http://" . $_SERVER['HTTP_HOST'] . $msg['cover'],
            'url' 			=> $msg['url'],
        ];

		return RequestLib::article($this->request, $article);
	}

	// 点击菜单，推送消息到客户端
	// 具体业务端根据情况重写该方法
	public function eventClick()
	{
        $key = trim($this->request['eventkey']);

        if ($key)
        {
        	return '';
        }

        $where = [
        	'wechat_id' 	=> $this->chatId,
        	'event'			=> EventMsg::MODEL_CLICK,
        	'sceneId'		=> $key,
        ];

        $msginfo = EMsgModel::get($where);

        if (! $msginfo || $msginfo && $msginfo['type'] == 1)
        {
        	$content = $msginfo && ! empty($msginfo['content']) ? htmlspecialchars_decode($msginfo['content']) : '您好，有任何疑问请给我们留言！';

        	return RequestLib::text($this->request, $content);
        }

        $article = [
            'title' 		=> $msginfo['title'],
            'description' 	=> htmlspecialchars_decode($msginfo['content']),
            'picurl' 		=> empty($msginfo['cover']) ? '' : "http://" . $_SERVER['HTTP_HOST'] . $msginfo['cover'],
            'url' 			=> $msginfo['url'],
        ];

		return RequestLib::article($this->request, $article);
	}

	// 根据关键字回复用户消息
	public function textResponse()
	{
		$content 	= '';
		$recontent  = trim($this->request['content']);
		if (empty($recontent))
		{
			return $content;
		}
		$now = time();
		$where = [
			'wechat_id'		=> $this->chatId,
			'keyword'		=> $recontent,
			'keywordType'	=> 1,
			'start_time'	=> ['elt', $now],
			'end_time'		=> ['egt', $now],
			'state'			=> 1,
		];

		$keyInfo = KWModel::get($where);

		if (! $keyInfo)
		{Log::record("sercach news");
			$where['keywordType']	= 2;
			$where['keyword']		= ['like', $recontent];

			$keyInfo = KWModel::get($where);
			Log::record(var_export($keyInfo, true));
		}

		if (! $keyInfo)
		{
			return $this->connectServer();
		}

		if ($keyInfo['addonModel'] == KWModel::MODEL_TEXT)
		{
			$info = TextModel::get(['wechat_id' => $this->chatId, 'id' => $keyInfo['aimId']]);

			$content  = $info ? RequestLib::text($this->request, htmlspecialchars_decode($info['content'])) : $content;
		}
		else
		if ($keyInfo['addonModel'] == KWModel::MODEL_NEWS)
		{
			$info = NewsModel::get(['wechat_id' => $this->chatId, 'id' => $keyInfo['aimId']]);
			if ($info)
			{
				$article = [
		            'title' 		=> $info['title'],
		            'description' 	=> htmlspecialchars_decode($info['content']),
		            'picurl' 		=> empty($info['cover']) ? $content : "http://" . $_SERVER['HTTP_HOST'] . $info['cover'],
		            'url' 			=> $info['jumpUrl'],
	        	];

	        	$content = RequestLib::article($this->request, $article);
			}			
		}

		return $content;
	}

	// 添加关注用户
	public function addUserToFollow()
	{
	    if (! $this->request)
	    {
	        return false;
	    }
	    
	    $where = [
	    	'wechat_id' => $this->chatId,
	    	'openid'	=> $this->request['fromusername'],
	    ];

	    $followObj  = new FollowModel();
	    $followInfo = FollowModel::get($where);
	    $wechatObj 	= new Wechat($this->chatConfig);
	    $token 		= $wechatObj->getToken();
	    $userInfo 	= $wechatObj->user($this->request['fromusername']);

	    if (! $userInfo)
	    {
	        return false;
	    }

	    $data = [
	        'nickname' 			=> $userInfo['nickname'],
	        'nickname_base64'	=> base64_encode($userInfo['nickname']),
	        'sex' 				=> intval($userInfo['sex']),
	        'language' 			=> $userInfo['language'],
	        'city'				=> $userInfo['city'],
	        'province' 			=> $userInfo['province'],
	        'country' 			=> $userInfo['country'],
	        'headimgurl'		=> $userInfo['headimgurl'],
	        'subscribe_time'	=> $userInfo['subscribe_time'], //关注时间
	        'unionid' 			=> '', //多平台唯一id，可根据openid换取
	        'groupid' 			=> intval($userInfo['groupid']),
	        'remark' 			=> $userInfo['remark'],
	        'update_time'		=> time(),
	    ];

	    if ($followInfo)
	    {
	    	if ($followInfo['subscribe'] == 0)
	    	{
				$data['subscribe'] 		= 1;
				$data['subscribe_time'] = time();    		
	    	}

    		$res = $followObj->save($data, $where);

    		if ($res === false)
    		{
    			return false;
    		}

	    	return true; 
	    }

	    $data = $data + $where;
	    $data['create_time'] = time();
	    $data['subscribe']	 = intval($userInfo['subscribe']);

	    $res = $followObj->save($data);

	    if (! $res)
	    {
	    	return false;
	    }
	    
	    return true;
	}

	//链接客服
	public function connectServer()
	{
		return '';
	}

}           