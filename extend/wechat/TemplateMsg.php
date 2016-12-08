<?php
/**
 * 模板消息发送
 *
 */
namespace wechat;

use app\admin\model\Template as TemplateModel;
use app\admin\model\Account as ChatModel;

class TemplateMsg
{
	/**
	 * 发送模板消息
	 * @param integer $chatId
	 * @param integer $tplId
	 * @param array   $data 	格式类似入下：
	 *     $data =  array(
	 *			"keyword1"  => "李金",
	 *          "keyword2"  => "人民币100.00元",
	 *          "keyword3"  => "网银在线",
	 *          "keyword4"  => "",
	 *          "keyword5"  => "充值成功",
	 *          "first"		=> "尊敬的李先生，您已于 2016年11月15日 00:19成功充值",
	 *          "remark"    => ""	
     *    	),
	 * @param string  $toUser
	 * @param string  $url
	 * @param string  $color
	 * @param string  $topColor
	 * @param string  $msg   结果消息
	 * @return boolean
	 */
	public static function send($chatId, $tplId, $toUser, $data, $url, $color = '', $topColor = '', &$msg = '')
	{
		$wechatId 	= intval($chatId);
		$tplId 		= intval($tplId);
		$color 		= trim($color);
		$topColor 	= trim($topColor);

		if (! $wechatId || ! $tplId || ! $data)
		{
			$msg 	= '请求参数错误';
			return false;
		}

		$chatInfo = ChatModel::get($wechatId);
		if (! $chatInfo)
		{
			$msg 	= '公众号信息不存在';
			return false;
		}

		$tplMsgInfo = self::getTplMsg($chatId, $tplId, $data, $color);

		if (! $tplMsgInfo)
		{
			$msg 	= '模板消息出错';
			return false;
		}

		$topColor = $topColor ? $topColor : ($tplMsgInfo['tplInfo']['top_color'] ? $tplMsgInfo['tplInfo']['top_color'] : '#FF0000');

		$params   = [
			'touser'		=> $toUser,
			'template_id'	=> $tplMsgInfo['tplInfo']['tplid'],
			'url'			=> $url,
			'topcolor'		=> $topColor,
			'data'			=> $tplMsgInfo['data'],
		];

		$corpInfo = [
			'appid'	=> $chatInfo['wechat_appid'],
			'secret'=> $chatInfo['wechat_appsecret'],
			'token' => $chatInfo['wechat_token'],
			'aeskey'=> $chatInfo['wechat_aeskey'],
		];

		$wechatObj 	= new Wechat($corpInfo);
		$token 		= $wechatObj->getToken();

		if (! $token)
		{
			$msg 	= '获取token失败';
			return false;
		}

		$res = $wechatObj->sendTemplate($params);

		if (! $res)
		{
			$msg 	= '模板消息发送失败';
			return false;
		}

		$msg 	= '模板消息发送成功';
		return true;
	}

	/**
	 * 获取模板消息发送内容
	 * @param integer $chatId
	 * @param integer $tplId
	 * @param array   $data
	 * @param string  $color
	 * @return mix    array|false
	 *  array(
     *    	'data' => array(
	 *			'first'=>array('value'=>'您好，您已成功消费。', 'color'=>'#0A0A0A')
     *          'keynote1'=>array('value'=>'巧克力', 'color'=>'#CCCCCC')
     *          'keynote2'=>array('value'=>'39.8元', 'color'=>'#CCCCCC')
     *          'keynote3'=>array('value'=>'2014年9月16日', 'color'=>'#CCCCCC')
     *          'keynote3'=>array('value'=>'欢迎再次购买。', 'color'=>'#173177')
     *    	),
     *    	'tplInfo' => array(模板消息明细)
     * );      
	 */
	public static function getTplMsg($chatId, $tplId, $data, $color = '')
	{
		$wechatId 	= intval($chatId);
		$tplId 		= intval($tplId);
		$color 		= trim($color);

		if (! $wechatId || ! $tplId || ! $data || count($data) < 3)
		{
			return false;
		}

		$tplInfo = TemplateModel::get(['wechat_id' => $wechatId, 'tpl_num' => $tplId])->toArray();

		if (! $tplInfo)
		{
			return false;
		}

		$tplInfo['content'] = htmlspecialchars_decode($tplInfo['content']);
		$color = $color ? $color : ($tplInfo['color'] ? $tplInfo['color'] : '#173177');

		$tplAry = array();
		preg_match_all("/\{\{(.*?)\.DATA\}\}/i", $tplInfo['content'], $match);
		if ($match[1])
		{
			$tplAry = $match[1];
			foreach ($tplAry as $v)
			{
				$val = trim($data[$v]);		
				$tplAry[$v] = array(
						'value'	=> trim($data[$v]),
						'color' => $color,
				);
			}
		}

		return [
			"data"		=> $tplAry,
			"tplInfo"	=> $tplInfo,
		];
	}

}
