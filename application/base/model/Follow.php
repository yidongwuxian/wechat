<?php
/**
 * 文件的简短描述：用户关注模型类文件
 *
 * 文件的详细描述：用户关注模型类文件
 *
 * LICENSE:
 * @author wangzhen 2016/10/25
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\base\model;

use think\Model;

class Follow extends Model {

    // 设置完整的数据表（包含前缀）
    protected $table = 'wechat_follow';

}