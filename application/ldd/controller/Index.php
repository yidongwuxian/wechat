<?php
namespace app\ldd\controller;

class Index extends Base {

    /**
     * 构造函数
     * Loan constructor.
     */
    public function __construct(){
        parent::__construct();
    }


    /**
     * 默认页
     * @return mixed
     */
    public function index(){
        return $this->fetch();
    }
}
