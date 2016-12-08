<?php
/**
 * 文件的简短描述：消息 关键字
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

class Keyword extends Model {

    protected $table = 'wechat_keyword';

    const MODEL_TEXT	= 'ReplyTextModel';   //简单文本回复
	const MODEL_NEWS	= 'ReplyNewsModel';   //简单图文回复
}