<?php
/**
 * 处理请求
 *
 */
namespace wechat;

class RequestLib
{
	public static function switchType(&$request)
	{
		$data = array();
		switch ($request['msgtype'])
		{
			//事件
			case 'event':
				$event = strtolower($request['event']);
				switch ($event)
				{
					case 'subscribe':
						if (isset($request['eventkey']) && isset($request['ticket']))
						{
							$data = self::eventQrsceneSubscribe($request);
						}
						else 
						{
							$data = self::eventSubscribe($request);
						}
						break;
					case 'unsubscribe':
						$data = self::eventUnSubscribe($request);
						break;
					case 'scan':
						$data = self::eventScan($request);
						break;
					case 'location':
						$data = self::eventLocation($request);
						break;
					case 'click':
						$data = self::eventClick($request);
						break;
					case 'view':
						$data = self::eventView($request);
						break;
				}
				break;
			//文本
			case 'text':
				$data = self::text($request);
				break;
			//图片
			case 'image':
				$data = self::image($request);
				break;
			//语音
			case 'voice':
				$data = self::voice($request);
				break;
			//视频
			case 'video':
				$data = self::video($request);
				break;
			//小视频
			case 'shortvideo':
				$data = self::shortvideo($request);
				break;
			//地理位置
			case 'location':
				$data = self::location($request);
				break;
			//链接
			case 'link':
				$data = self::link($request);
				break;
			default:
				break;
		}
		
		return $data;
	}
	
	/**
	 * 文本
	 * @param unknown $request
	 * @param string $content
	 * @return string
	 */
	public static function text(&$request, $content='')
	{
		if (! $content)
		{
			$content = '收到文本信息';
		}
		
		return ResponseLib::text($request['fromusername'], $request['tousername'], $content);		
	}
	
	/**
	 * 图片消息处理
	 * @param unknown $request
	 * @param string $content
	 * @return string
	 */
	public static function image(&$request, $content='')
	{
		if (! $content)
		{
			$content = '收到图片信息';
		}
		
		return ResponseLib::text($request['fromusername'], $request['tousername'], $content);
	}
	
	/**
	 * 语音消息处理
	 * @param unknown $request
	 * @param string $content
	 * @return string
	 */
	public static function voice(&$request, $content='')
	{
		if (! $content)
		{
			if (isset($request['recognition']))
			{
				$content = '收到语音识别消息， 语音识别结果为：' . $request['recognition'];
			}
			else 
			{
				$content = '收到语音信息';
			}
		}
		
		return ResponseLib::text($request['fromusername'], $request['tousername'], $content);
	}
	
	/**
	 * 视频消息处理
	 * @param unknown $request
	 * @param unknown $content
	 * @return string
	 */
	public static function video(&$request, $content='')
	{
		if (! $content)
		{
			$content = '收到视频信息';
		}
		
		return ResponseLib::text($request['fromusername'], $request['tousername'], $content);
	}
	
	/**
	 * 小视频
	 * @param unknown $request
	 * @param string $content
	 * @return string
	 */
	public static function shortvideo(&$request, $content='')
	{
		if (! $content)
		{
			$content = '收到小视频信息';
		}
		
		return ResponseLib::text($request['fromusername'], $request['tousername'], $content);
	}
	
	/**
	 * 上报地理位置信息
	 * @param unknown $request
	 * @param string $content
	 * @return string
	 */
	public static function location(&$request, $content='')
	{
		if (! $content)
		{
			$content = '收到上报的地理信息';
		}
		
		return ResponseLib::text($request['fromusername'], $request['tousername'], $content);
	}
	
	/**
	 * 链接信息
	 * @param unknown $request
	 * @param string $content
	 * @return string
	 */
	public static function link(&$request, $content='')
	{
		if (! $content)
		{
			$content = '收到链接信息';
		}
		
		return ResponseLib::text($request['fromusername'], $request['tousername'], $content);
	}
	
	/**
	 * 关注事件
	 * @param unknown $request
	 * @param string $content
	 * @return string
	 */
	public static function eventSubscribe(&$request, $content='')
	{
		if (! $content)
		{
			$content = '欢迎关注Fuqiang工作室，我们将竭诚为您服务';
		}
		
		return ResponseLib::text($request['fromusername'], $request['tousername'], $content);
	}
	
	/**
	 * 取消关注
	 * @param unknown $request
	 * @param string $content
	 * @return string
	 */
	public static function eventUnSubscribe(&$request, $content='')
	{
		if (! $content)
		{
			$content = '为什么不关注我了呢，亲？';
		}
		
		return ResponseLib::text($request['fromusername'], $request['tousername'], $content);
	}
	
	/**
	 * 扫描二维码关注 （未关注时）
	 * @param unknown $request
	 * @param string $content
	 * @return string
	 */
	public static function eventQrsceneSubscribe(&$request, $content='')
	{
		if (! $content)
		{
			$content = '欢迎关注Fuqiang工作室，我们将竭诚为您服务';
		}
		
		return ResponseLib::text($request['fromusername'], $request['tousername'], $content);
	}
	
	/**
	 * 扫描二维码事件
	 * @param unknown $request
	 * @param string $content
	 */
	public static function eventScan(&$request, $content='')
	{
		if (! $content)
		{
			$content = '感谢您的关注';
		}
		
		return ResponseLib::text($request['fromusername'], $request['tousername'], $content);
	}
	
	/**
	 * 上报地理位置事件
	 * @param unknown $request
	 * @param string $content
	 * @return string
	 */
	public static function eventLocation(&$request, $content='')
	{
		if (! $content)
		{
			$content = '收到您上报的地理位置';
		}
		
		return ResponseLib::text($request['fromusername'], $request['tousername'], $content);
	}
	
	/**
	 * 点击自定义菜单事件
	 * @param unknown $request
	 * @param string $content
	 * @return string
	 */
	public static function eventClick(&$request, $content='')	
	{
		if (! $content)
		{
			$content = '收到点击菜单事件，您设置的key是：' . $request['eventkey'];
		}
		
		return ResponseLib::text($request['fromusername'], $request['tousername'], $content);
	}
	
	/**
	 * 自定义菜单跳转
	 * @param unknown $request
	 */
	public static function eventView(&$request)
	{
		
	}
	
	public static function article(&$request, $article)
	{
		$articles[] = $article;
		return ResponseLib::news($request['fromusername'], $request['tousername'], $articles);
	}
	
	public static function multNews(&$request, $articles)
	{
		return ResponseLib::news($request['fromusername'], $request['tousername'], $articles);
	}
}
?>