<?php
/**
 * 文件的简短描述：无
 *
 * 文件的详细描述：无
 *
 * LICENSE:
 * @author wangzhen 2016/8/17
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
class Util {

    public static function echoJson($message = '参数错误！ ', $status = false, $extension = ''){
        $status = $status ? 1 : 0;
        $result = array(
            'result'   => $status,
            'message'  => $message,
            'extension'=> $extension
        );

        $msg = json_encode($result);
        
        header("Cache-Control: no-cache");
        header('Content-Length: ' . strlen($msg));

        die($msg);
    }

    //密码加密
    public static function encrypt($password, $verify_key){
        return md5($password.$verify_key);
    }

    //生成随机密钥
    public static function random( $length ) {
        $strChars = 'abcdefghijklmopqrstuvwxyzABCDEFGHIJKLMOPQRSTUVWXYZ0123456789';
        $max = strlen( $strChars ) - 1;
        mt_srand( ( double )microtime() * 1000000 );
        $strStartName = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $strStartName .= $strChars[mt_rand( 0, $max )];
        }
        return $strStartName;
    }

    /**
     * 根据当前请求参数生成签名
     * 签名方法：将$data所有参数键值对进行倒序排序，然后用16拼接成一个字符串，再连接密钥进行md5加密
     *
     * @param array $data
     * @return string
     */
    public static function checkSign($data = array()){

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
     * CURL方法
     *
     * @param $url
     * @param array $param
     * @param int $decode
     * @param int $timeout
     * @return mixed
     */
    public static function curlPost($url, $param = array(), $decode = 1, $timeout = 10)
    {
        $data = http_build_query($param);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec ($ch);
        curl_close ($ch);
        unset($ch);

        return $decode ? json_decode($result,TRUE) : $result;
    }

    /**
     * 发送HTTP请求方法，目前只支持CURL发送请求
     * @param  string  $url    请求URL
     * @param  array   $params 请求参数
     * @param  string  $method 请求方法GET/POST
     * @return boolean|array   $data   响应数据
     * @author 、小陈叔叔 <cjango@163.com>
     */
    public static function http($url, $params = array(), $method = 'GET'){
        $opts = array(
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        );
        /* 根据请求类型设置特定参数 */
        switch(strtoupper($method)){
            case 'GET':
                $getQuerys = !empty($params) ? '?'. http_build_query($params) : '';
                $opts[CURLOPT_URL] = $url . $getQuerys;
                break;
            case 'POST':
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
        }

        /* 初始化并执行curl请求 */
        $ch     = curl_init();
        curl_setopt_array($ch, $opts);
        $data   = curl_exec($ch);
        $err    = curl_errno($ch);
        $errmsg = curl_error($ch);
        //$httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE); //需要知道返回状态码时使用
        curl_close($ch);
        if ($err > 0) {
            return false;
        }else {
            return $data;
        }
    }
}


