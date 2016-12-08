<?php
/**
 * 文件的简短描述：文件上传
 * 
 * LICENSE:
 * @author lijin
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Image;

class Upload extends Controller
{
	// 上传一张图片到本地
    public function image($fid)
    {
    	$request = Request::instance();
	    $fileId  = trim($fid);
	    if (! $fileId)
        {
            \Util::echoJson('缺少fileId');
        }

        $file 		= $request->file($fileId);
        $fileMime 	= $_FILES[$fileId]["type"];
        $extension  = pathinfo($_FILES[$fileId]["name"], PATHINFO_EXTENSION);
        $extInfo 	= self::getImgExt();

        if (! in_array($extension, $extInfo['ext']) || ! in_array($fileMime, $extInfo['mime']))
        {
            \Util::echoJson('文件类型不支持');
        }

	    $info = $file->move(ROOT_PATH . '/public/uploads/', true, false);

	    if (! is_object($info))
        {
            \Util::echoJson('上传失败');
        }

        $imgUrl	= '/uploads/' . date('Ymd') . '/'. $info->getFilename();        

        \Util::echoJson('上传成功', true, $imgUrl);
    }

    // 删除本地上传的文件
    public function imageDel()
    { 
    	$request  = Request::instance();
	    $filePath = $request->post('path');

	    if (! $filePath)
	    {
	    	\Util::echoJson('请求参数错误');
	    }

	    $path = ROOT_PATH . '/public' . $filePath;
		if (is_dir($path))
		{
			\Util::echoJson('不是文件不能删除'. $path);
		}

		if (! file_exists($path) ||  ! is_file($path))
		{
			\Util::echoJson('文件不存在');
		}

		if (! unlink($path))
		{
			\Util::echoJson('操作失败');
		}

		\Util::echoJson('操作成功', true);
    }

    public static function getImgExt()
    {
        $ext = ["jpg", "jpg", "png"];

        $mime = ["image/jpeg", "image/gif", "image/png"];

        return ['ext' => $ext, 'mime' => $mime];
    }
   
}