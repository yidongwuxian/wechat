<?php
/**
 * 文件的简短描述：无
 *
 * 文件的详细描述：无
 *
 * LICENSE:
 * @author wangzhen 2016/11/29
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\ldd\controller;

use app\ldd\model\Ldd;
use think\Cache;
use think\Config;
use think\Session;
use think\Validate;

class Agent extends Base {

    public function __construct(){

        parent::__construct();

    }

    /**
     * 查询业务员列表
     * @return bool|mixed
     */
    public function agentList(){
        if($this->request->isAjax()){
            //JS获取业务列表
            $mobile = Session::get('mobile');
            $lddModel = new Ldd($this->bizApi,$mobile);
            $result = $lddModel->thirdAgentList();
            if(is_array($result) && $result['success'] == true){
                //存储列表
                Cache::set('agentList_' . $mobile,$result['data'],1800); //存储30分钟
                \Util::echoJson('查询成功！',true,$result['data']);
            }elseif(is_array($result) && $result['success'] == false){
                \Util::echoJson($result['msg'],false,$this->defaultUri);
            }else{
                \Util::echoJson($this->defaultErrorMessage,false,$this->defaultUri);
            }
            return false;
        }else{
            return $this->fetch();
        }
    }

    /**
     * 添加业务员
     * @return bool|mixed
     */
    public function add(){
        if($this->request->isAjax()){
            $params = [
                'agentName' => $this->request->param('agentName'),
                'mobile' => $this->request->param('mobile'),
            ];
            $rules = [
                'agentName|业务员姓名' => [
                    'regex' => '/^[a-zA-Z\x{4e00}-\x{9fa5}]+$/u',
                    'require'
                ],
                'mobile|手机号' => [
                    'regex' => '/^1[3|4|5|7|8][0-9]\d{8}$/',
                    'require'
                ],
            ];
            $message = [
                'mobile.regex' => '手机号格式不正确',
                'agentName.regex' => '业务员姓名只能是中文或英文'
            ];
            $validate = new Validate($rules,$message);
            if( ! $validate->check($params)){
                \Util::echoJson($validate->getError());
            }
            //校验成功，调用接口
            //添加代理人
            $accountMobile = Session::get('mobile');
            $lddModel = new Ldd($this->bizApi,$accountMobile);
            $defaultPassword = Config::get('default_password');
            $res = $lddModel->thirdAgentAdd($params,$defaultPassword);
            if(is_array($res) && $res['success'] == TRUE){
                //返回正确结果
                \Util::echoJson('添加成功！',true,'/ldd/agent/agentlist');
            }else if(is_array($res) && $res['success'] == FALSE){
                \Util::echoJson($res['msg'],false,'/ldd/agent/agentlist');
            }else{
                \Util::echoJson($this->defaultErrorMessage,false,'/ldd/agent/agentlist');
            }
            return false;
        }else{
            return $this->fetch();
        }
    }

    /**
     * 机构负责人编辑业务员（密码重置）
     * 业务员姓名、手机号都在前端设置为不可编辑了
     * @param int $id
     * @return bool|mixed
     */
    public function edit($id = 0){
        if($this->request->isAjax()){
            $params = [
                'id' => $this->request->param('id'),
                'agentName' => $this->request->param('agentName'),
                'mobile' => $this->request->param('mobile'),
            ];
            $rules = [
                'id' => 'require|integer',
                'agentName|业务员姓名' => [
                    'regex' => '/^[a-zA-Z\x{4e00}-\x{9fa5}]+$/u',
                    'require'
                ],
                'mobile|手机号' => [
                    'regex' => '/^1[3|4|5|7|8][0-9]\d{8}$/',
                    'require'
                ],
            ];
            $message = [
                'mobile.regex' => '手机号格式不正确',
                'agentName.regex' => '业务员姓名只能是中文或英文'
            ];
            $validate = new Validate($rules,$message);
            if( ! $validate->check($params)){
                \Util::echoJson($validate->getError());
            }
            //校验成功，调用接口
            //编辑代理人
            $accountMobile = Session::get('mobile');
            $lddModel = new Ldd($this->bizApi,$accountMobile);
            $res = $lddModel->thirdAgentEdit($params);
            if(is_array($res) && $res['success'] == TRUE){
                //返回正确结果
                \Util::echoJson('编辑成功！',true,'/ldd/agent/agentlist');
            }else if(is_array($res) && $res['success'] == FALSE){
                \Util::echoJson($res['msg'],false,'/ldd/agent/agentlist');
            }else{
                \Util::echoJson($this->defaultErrorMessage,false,'/ldd/agent/agentlist');
            }
            return false;
        }else{
            $id OR $this->error('参数错误！',$this->defaultUri);
            //从缓存中获取业务员列表，因为PC端没有给出获取单个业务员信息的接口，所以需要从列表中筛选
            $mobile = Session::get('mobile');
            $agentList = Cache::get('agentList_' . $mobile);
            $agentList OR $this->error('页面失效，请重新从列表页进入！',$this->defaultUri);
            //遍历数组，获取业务员详情
            $agentInfo = [];
            //var_dump($agentList);die;
            foreach ($agentList['list'] as $item){
                if($item['businessPeopleNum'] == $id){
                    $agentInfo = $item;
                }
            }
            //var_dump($agentInfo);die;
            $this->assign('agentInfo',$agentInfo);
            return $this->fetch();
        }
    }

}