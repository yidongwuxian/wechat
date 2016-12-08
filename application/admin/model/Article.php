<?php
/**
 * 文件的简短描述：文章明细
 *
 *
 * LICENSE:
 * @author lijin 2016/11/25
 * @copyright Copyright (c) 2016 DFJK
 * @version 2.0.0
 * @since File available since Release 1.0.0
**/
namespace app\admin\model;

use think\Model;

class Article extends Model 
{

    protected $table = 'wechat_article';

    public static $status = [
    	1 => '启用',
    	2 => '禁用',
    ];
}