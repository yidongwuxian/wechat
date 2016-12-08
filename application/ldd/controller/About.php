<?php
/**
 * 文件的简短描述：关于类控制器
 *
 * 文件的详细描述：关于类控制器
 *
 * LICENSE:
 * @author wangzhen 2016/11/29
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\ldd\controller;

use think\Controller;

class About extends Controller {

    public function __construct(){
        parent::__construct();
        $version = time();
        $this->assign('version',$version);
    }

    /**
     * 产品介绍
     * @return mixed
     */
    public function introduce(){
        return $this->fetch();
    }

    /**
     * 准入条件
     * @return mixed
     */
    public function permition(){
        return $this->fetch();
    }

    /**
     * 业务流程
     * @return mixed
     */
    public function procedure(){
        return $this->fetch();
    }

    /**
     * 下户调查资料
     * @return mixed
     */
    public function information(){
        return $this->fetch();
    }

    /**
     * 使用说明
     * @return mixed
     */
    public function instruction(){
        return $this->fetch();
    }

    /**
     * 联系我们
     * @return mixed
     */
    public function contact(){
        return $this->fetch();
    }

    /**
     * 常见问题
     * @return mixed
     */
    public function help(){
        return $this->fetch();
    }

}