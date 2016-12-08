<?php
/**
 * 文件的简短描述：事件信息推送
 *
 *
 * LICENSE:
 * @author lijin 2016/11/10
 * @copyright Copyright (c) 2016 DFJK
 * @version 2.0.0
 * @since File available since Release 1.0.0
**/
namespace app\admin\model;

use think\Model;

class EventMsg extends Model {

    protected $table = 'wechat_event_msg';

    const MODEL_SUBSCRIBE	= 'subscribe';    	//关注
	const MODEL_SCANSCRIBE	= 'scanscribe';   	//扫描二维码关注
	const MODEL_SCANPARAM	= 'scanparam';   	//用户已关注扫描二维码
	const MODEL_CLICK		= 'click';    		//点击
	const MODEL_VIEW		= 'view';    		//点击

	public static $typeConf = array(
		self::MODEL_SUBSCRIBE 	=> '关注',
		self::MODEL_SCANSCRIBE	=> '扫描二维码关注',
		self::MODEL_SCANPARAM 	=> '扫描带参二维码',
		self::MODEL_CLICK 		=> '点击事件',
		// self::MODEL_VIEW 		=> '点击链接',		
	);
}