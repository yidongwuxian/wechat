<?php
/**
 * 文件的简短描述：老舍房贷业务接口
 *
 * 文件的详细描述：老舍房贷业务接口
 *
 * LICENSE:
 * @author wangzhen 2016/11/11
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\ldd\model;

use think\Log;
use think\Model;

class Ldd extends Model {

    /**
     * api前缀
     * @var string
     */
    protected $apiUrlPre = 'http://23.4.160.41:7003/bxloan/';

    /**
     * openid
     * @var string
     */
    protected $openid = '';

    protected $mobile = '';

    /**
     * AES加密秘钥
     * @var string
     */
    protected $aes_key_str = 'wechat_encrypt_k';

    public function __construct($apiUrlPre = '',$mobile = ''){
        parent::__construct();
        $this->mobile = $mobile; //手机号作为用户的唯一标识
        if($apiUrlPre != ''){
            //如果传入接口地址，则不使用默认生产地址
            $this->apiUrlPre = $apiUrlPre . 'bxloan/';
        }
    }

    /**
     * 代理人或机构负责人登录
     * @param $openid
     * @param $mobile
     * @param $password
     * @return bool|mixed
     */
    public function thirdPartyLogin($openid,$mobile,$password){
        $url = $this->apiUrlPre . 'wxcust/thirdPartyLogin/';
        $param = array(
            'openid'    => $openid,
            'mobile'    => $mobile,
            'password'  => $this->passwordAes($password)
        );
        $param['sign'] = self::getSign($param);
        $data = $this->curlPost($url,$param);
        return $data;
    }

    /**
     * 获取房产类型和抵押类型
     * @param $city
     * @return bool|mixed
     */
    public function getGuarantyAndHouseType($city){
        $url = $this->apiUrlPre . 'wxcust/getGuarantyWithHouseType/';
        $param = array(
            'account'    => $this->mobile,
            'city'       => $city
        );
        $param['sign'] = self::getSign($param);
        $data = $this->curlPost($url,$param);
        return $data;
    }

    /**
     * 贷款金额预估，根据城市返回贷款最高额度
     * @param $city
     * @return bool|mixed
     */
    public function loadAmount($city){
        $url = $this->apiUrlPre . 'wxLoan/loanAmount/';
        $param = array(
            'account'  => $this->mobile,
            'orgId'    => $city
        );
        $param['sign'] = self::getSign($param);
        $data = $this->curlPost($url,$param);
        return $data;
    }

    /**
     * 贷款申请
     * @param $applyInfo
     * @return bool|mixed
     */
    public function loanApply($applyInfo){
        $url = $this->apiUrlPre . 'wxLoan/loanApply';
        $param = array(
            'account'    => $this->mobile,
            'json'       => $applyInfo
        );
        $param['sign'] = self::getSign($param);
        $data = $this->curlPost($url,$param);
        return $data;
    }

    /**
     * 业务列表
     * @return bool|mixed
     */
    public function applyList(){
        $url = $this->apiUrlPre . 'wxLoan/projectList/';
        $param = array(
            'account'    =>  $this->mobile
        );
        $param['sign'] = self::getSign($param);
        $data = $this->curlPost($url,$param);
        return $data;
    }

    /**
     * 获取业务（订单）详情
     * @param $projectNo
     * @return bool|mixed
     */
    public function bizInfo($projectNo){
        $url = $this->apiUrlPre . 'wxLoan/projectInfo/';
        $param = array(
            'account'    =>  $this->mobile,
            'projectNo' =>  $projectNo
        );
        $param['sign'] = self::getSign($param);
        $data = $this->curlPost($url,$param);
        return $data;
    }

    /**
     * 预约尽调
     * @param $params
     * @return bool|mixed
     */
    public function appointTime($params){
        $url = $this->apiUrlPre . 'wxLoan/updateAppointmentResearchTime/';
        $param = array(
            'account'           =>  $this->mobile,
            'projectNo'         =>  $params['projectNo'],
            'appointmentTime'   =>  $params['appointTime'],
            'remark'            =>  $params['remark']
        );
        $param['sign'] = self::getSign($param);
        $data = $this->curlPost($url,$param);
        return $data;
    }

    /**
     * 合同信息
     * @param $projectNo
     * @return bool|mixed
     */
    public function contractInfo($projectNo){
        $url = $this->apiUrlPre . 'wxLoan/loanDetail/';
        $param = array(
            'account'   =>  $this->mobile,
            'projectNo' =>  $projectNo
        );
        $param['sign'] = self::getSign($param);
        $data = $this->curlPost($url,$param);
        return $data;
    }

    /**
     * 获取房产预估价格区间
     * @param $orgId
     * @param $houseType
     * @param $subMorTgageType
     * @param $thirdOrgAmt
     * @param $oneResidualPrincipal
     * @return bool|mixed
     */
    public function getEvaluationPrice($orgId, $houseType, $subMorTgageType, $thirdOrgAmt, $oneResidualPrincipal){
        $url = $this->apiUrlPre . 'wxLoan/limitLoan/';
        $param = array(
            'account'   =>  $this->mobile,
            'orgId'     =>  $orgId, //机构Id
            'houseType' =>  $houseType, //房产类型，普通住宅，商住两用等
            'subMorTgageType'   => $subMorTgageType, //抵押类型
            'thirdOrgAmt'   =>  $thirdOrgAmt, //房天下评估的房产总价
            'oneResidualPrincipal'  => $oneResidualPrincipal //一押剩余本金
        );
        $param['sign'] = self::getSign($param);
        $data = $this->curlPost($url,$param);
        return $data;
    }

    /**
     * 机构负责人获取结构业务列表
     * @return bool|mixed
     */
    public function orgApplyList(){
        $url = $this->apiUrlPre . 'wxloan/mgrBizList/';
        $param = array(
            'account'    =>  $this->mobile
        );
        $param['sign'] = self::getSign($param);
        $data = $this->curlPost($url,$param);
        return $data;
    }

    /**
     * 第三方代理人员列表
     * @return bool|mixed
     */
    public function thirdAgentList(){
        $url = $this->apiUrlPre . 'wxcust/agentList/';
        $param = array(
            'account' => $this->mobile
        );
        $param['sign'] = self::getSign($param);
        $data = $this->curlPost($url,$param);
        return $data;
    }

    /**
     * 添加业务员
     * @param $params
     * @param $password
     * @return bool|mixed
     */
    public function thirdAgentAdd($params, $password){
        $url = $this->apiUrlPre . 'wxcust/agentAdd/';
        $param = array(
            'account'               => $this->mobile,
            'businessPeopleName'    => $params['agentName'],
            'tel'                   => $params['mobile'],
            'password'              => self::passwordAes($password)
        );
        $param['sign'] = self::getSign($param);
        $data = $this->curlPost($url,$param);
        return $data;
    }

    /**
     * 编辑业务员（业务上，机构负责人操作密码重置）
     * @param $params
     * @return bool|mixed
     */
    public function thirdAgentEdit($params){
        $url = $this->apiUrlPre . 'wxcust/agentEdit/';
        $param = array(
            'account'               => $this->mobile,
            'id'                    => $params['id'],
            'businessPeopleName'    => $params['agentName'],
            'tel'                   => $params['mobile']
        );
        $param['sign'] = self::getSign($param);
        $data = $this->curlPost($url,$param);
        return $data;
    }

    /**
     * 根据当前请求参数生成签名
     * 签名方法：将$data所有参数键值对进行倒序排序，然后用16拼接成一个字符串，再连接密钥进行md5加密
     *
     * @param array $data
     * @return string
     */
    public static function getSign($data = array()){

        $sign_arr = array();
        foreach ($data as $kk => $vv){
            if(in_array($kk,array('sign','saeut','decode'))){
                continue;
            }
            $sign_arr[$kk] = $vv;
        }

        krsort($sign_arr);

        $sign_str = '';
        $sign_key = "i`8#u_@+lcy#9=0";

        foreach ($sign_arr as $kk=>$vv){
            $sign_str = $sign_str ? $sign_str."16"."$kk=$vv" : "$kk=$vv";
        }

        $sign_str = $sign_str ? $sign_str."16".$sign_key : $sign_key;//exit($sign_str);
        $str = $sign_str;
        $sign_str = md5($str);
        return $sign_str;
    }

    /**
     * 对密码进行AES加密
     * @param $password
     * @return string
     */
    public function passwordAes($password){
        //password做AES加密
        $crypt_aes = new \CryptAES();
        $crypt_aes->set_key($this->aes_key_str);
        $crypt_aes->require_pkcs5();
        return $crypt_aes->encrypt($password);
    }

    /**
     * CURL方法，增加记录日志
     * @param $url
     * @param $param
     * @param int $isDecode
     * @return bool|mixed
     */
    public function curlPost($url, $param, $isDecode = 1){
        $data = \Util::curlPost($url,$param,$isDecode,40);
        //记录日志
        self::addApiLog($url,$param,$data);
        if( ! $data){
            return FALSE;
        } else {
            return $data;
        }
    }

    /**
     * 对外API Log
     * @param $url
     * @param $data
     * @param $receive
     */
    public static function addApiLog($url,$data,$receive){
        //从url找出接口
        $url_arr_1 = $url_arr_2 = explode('/',$url);
        $url_last = end($url_arr_1);
        if(empty($url_last)){
            array_pop($url_arr_2);
            $url_api = end($url_arr_2);
        }else{
            $url_api = $url_last;
        }
        //为方便调试，记录组装的url尾部
        $query = http_build_query($data);
        $url_str = $url . '?' . $query;
        //增加数据
        $receive = json_encode($receive);
        $row = 'BX-API:' . $url_api . '===###===Send:' . json_encode($data) . '===###===Receive:' . $receive . '===###===URL:' . $url_str;
        Log::write($row,'ldd');
    }

    /**
     * 获取验证码接口
     * @param $mobile
     * @return bool|mixed
     */
    public function getVerificationCode($mobile){
        $url = $this->apiUrlPre . 'wxcust/getVerificationCode/';
        $param = array(
            'account'    => $this->mobile,
            'mobile'     => $mobile
        );
        $param['sign'] = self::getSign($param);
        $data = $this->curlPost($url,$param);
        return $data;
    }

    /**
     * 用户修改密码接口
     * @param $param
     * @return bool|mixed
     */
    public function forgetPassword($param){
        $url = $this->apiUrlPre . 'wxcust/forgetPassword/';
        $param = array(
            'account'    => $this->mobile,
            'mobile'     => $param['mobile'],
            'code'       => $param['code'],
            'userName'   => '',
            'password'   => $param['password'],
        );
        $param['sign'] = self::getSign($param);
        $data = $this->curlPost($url,$param);
        return $data;
    }

}