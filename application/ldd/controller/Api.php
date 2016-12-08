<?php
/**
 * 文件的简短描述：Api类文件，此处用于给房天下提供接口
 *
 * 文件的详细描述：Api类文件，此处用于给房天下提供接口
 *
 * LICENSE:
 * @author wangzhen 2016/11/27
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\ldd\controller;

use app\ldd\model\Evaluation;
use think\Controller;
use think\Log;
use think\Request;
use think\Config;

class Api extends Controller {

    public $appid = 'jk65824f3a7c50e0';
    public $appsecret = 'a34480cecae39c5d001604t7';
    public $token = 'fangtianxia';

    //手动评估
    public function manualEstimate(){
        if($this->request->isPost()){
            //接收流方式的POST
            $post_data = file_get_contents("php://input");
            //POST数据记录日志
            self::addApiLog($post_data);
            //字符串JSON解析
            $param_data = json_decode($post_data,true);
            if( ! $param_data){
                $this->echoJson(-7,'数据格式错误');
            }
            $token = trim($param_data['token']);
            $timestamp = trim($param_data['timestamp']);
            $encrypt = trim($param_data['encrypt']);
            $data = trim($param_data['data']);
            if( ! $token || ! $timestamp || ! $encrypt || ! $data){
                $this->echoJson(-2,'缺少必要参数');
            }else if($token != $this->token){
                $this->echoJson(-3,'token错误');
            }else if( ! is_numeric($timestamp) || ! is_int($timestamp + 0)){
                $this->echoJson(-4,'时间格式不正确');
            }else if(abs($timestamp - time()) > 1200){
                $this->echoJson(-5,'时间过期');
            }
            //校验参数
            $check_encrypt = md5($this->appid . $this->token . $this->appsecret . $timestamp);
            if(strcasecmp($check_encrypt,$encrypt) != 0){
                $this->echoJson(-6,'校验失败');
            }
            //校验成功
            //$data = '{"objId":"Y109011608050005","fTotalPrice":"0.00","fPrice":"0.00","sForward":"朝南","iTotalfloor":"19","iFloor":"8","fArea":"88.34","sProperty":"住宅","sElevator":"无","sYears":"未知","fCityPrice":"45610","fCityPriceMonth":"43979","fDistrictPrice":"52927","fDistrictPriceMonth":"40517","fProjectPrice":"","fProjectPriceMonth":""}';
            //参数校验成功，校验数据正确性
            //判断返回的数据是否有相应的价格
            $data = json_decode($data,true);
            if(empty($data['objId']) || //评估记录主键id
                empty($data['fTotalPrice']) || //房产总价
                empty($data['fPrice']) || //房产均价
                empty($data['fDistrictPrice']) || //区县均价
                empty($data['fCityPrice']) || //城市均价
                empty($data['fCityPriceMonth'])){ //城市近六个月均价
                //如果以上指标项房天下返回的数据为空，需要进行人工评估
                $this->echoJson(-8,'房产价格有为空或者为0的情况！');
            }
            //返回的数据价格无格式错误，更新数据库记录（如果是微信模式，还需要发送模板消息）
            $evaluation = Evaluation::get($data['objId']);
            if( ! $evaluation){
                $this->echoJson(-9,'业务编号有误！');
            }
            $openid = $evaluation->openid;
            $evaluation->evaluation_time = time(); //更新评估时间
            $evaluation->status = 3; //评估状态更新为：评估完成但数据不完整
            $evaluation->fang_info = json_encode($data);
            if( ! $evaluation->save()){
                $this->echoJson(-10,'数据存储出错！');
            }
            //更新完数据库，发送微信消息
            if($openid != -1){
                $address = $evaluation->address;
                $address = str_replace('，','',$address);
                $data = [
                    'first' => '您提交的房产评估已得到回复。',
                    'keyword1' => '评估完成',
                    'keyword2' => $address,
                    'remark' => '点此链接查看。'
                ];
                $appid = Config::get('chat_id');
                $msg = '';
                \wechat\TemplateMsg::send($appid, 17, $openid, $data, '', '', '', $msg);
            }

            $this->echoJson(1,'接口1调用成功！');
        }else{
            $this->echoJson(-1,'请求方式错误');
        }
        exit;
    }

    /**
     * 输出JSON
     * @param int $status
     * @param string $msg
     * @param array $data
     */
    public function echoJson($status = 1,$msg = '操作成功',$data = array()){
        $arr = array(
            'status' => $status,
            'message' => $msg,
            'data' => $data,
        );
        exit(json_encode($arr));
    }

    /**
     * 对外API Log
     * @param $data
     */
    public static function addApiLog($data){
        if(is_array($data)){
            $data = json_encode($data);
        }
        $row = 'TO-FTX-API:POST:' . $data;
        Log::write($row,'from_ftx');
    }

    /**
     * 模板消息发送接口
     */
    public function sendTplMsgNew()
    {
        $request = Request::instance();
        $key = Config::get('external_api_key');  //暂定密钥
        if ($this->request->isPost()) {
            $appid = intval($request->post('appid'));   //公众号编号
            $type  = intval($request->post('type'));    //模板消息类型
            $encrypt = trim($request->post('encrypt')); //加密字符串
            $param_data  = json_decode($request->post('data'), true);   //所需数据 uid,url,first,keyowrds,remark
            $uid = $param_data['uid'];
            $url = $param_data['url'];

            if ( !$appid || !$type || !$encrypt || !$uid) {
                $this->echoJson(-1, '缺少必要参数');
            }
            //校验参数
            $check_encrypt = md5($appid . $type . $key);
            if (strcasecmp($check_encrypt, $encrypt) != 0) {
                $this->echoJson(-1, '校验失败');
            }

            $msg = "";
            $res = \wechat\TemplateMsg::send($appid, $type, $uid, $param_data, $url, '', '', $msg);
            if ( !$res) {
                $this->echoJson(-1, $msg);
            }
            $this->echoJson(0, $msg);

        } else {
            $this->echoJson(-1, '请求方式错误');
        }
        exit;
    }

}