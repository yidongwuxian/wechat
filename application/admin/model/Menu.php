<?php
/**
 * 文件的简短描述：菜单
 *
 *
 * LICENSE:
 * @author lijin 2016/11/15
 * @copyright Copyright (c) 2016 DFJK
 * @version 2.0.0
 * @since File available since Release 1.0.0
**/
namespace app\admin\model;

use think\Model;

class Menu extends Model {

    protected $table = 'wechat_menu';

	//菜单类型	
	const TYPE_CLICK 			= 'click';
	const TYPE_VIEW				= 'view';
	const TYPE_MEDIA_ID			= 'media_id';
	const TYPE_SCANCODE_PUSH	= 'scancode_push';
	const TYPE_SCANCODE_WAITMSG = 'scancode_waitmsg';
	const TYPE_PIC_SYSPHOTO		= 'pic_sysphoto';
	const TYPE_PIC_PHOTO_OR_ALBUM = 'pic_photo_or_album';
	const TYPE_PIC_WEIXIN		= 'pic_weixin';
	const TYPE_LOCATION_SELECT  = 'location_select';
	const TYPE_NONE				= 'none';
	
	public static $typeConf = array(
		self::TYPE_CLICK 			=> '点击推事件',
		self::TYPE_VIEW	 			=> '跳转URL',
		self::TYPE_MEDIA_ID 		=> '点击获取Media事件',
		self::TYPE_SCANCODE_PUSH 	=> '扫码推事件',
		self::TYPE_SCANCODE_WAITMSG => '扫码带提示',
		self::TYPE_PIC_SYSPHOTO		=> '弹出系统拍照发图',
		self::TYPE_PIC_PHOTO_OR_ALBUM => '弹出拍照或相册发图',
		self::TYPE_PIC_WEIXIN 		=> '弹出微信相册发图器',
		self::TYPE_LOCATION_SELECT 	=> '弹出地理位置选择器',
		self::TYPE_NONE				=> '无事件的一级菜单'
		
	);
}