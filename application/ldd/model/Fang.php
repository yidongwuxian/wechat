<?php
/**
 * 文件的简短描述：房天下接口
 *
 * 文件的详细描述：房天下接口
 *
 * LICENSE:
 * @author wangzhen 2016/11/11
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\ldd\model;

use think\Config;
use think\Log;
use think\Model;
use DES as Desclass;

/**
 * 类名：Fang
 *
 * 类的详细描述：无
 *
 * LICENSE:
 * @author wangzhen
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
class Fang extends Model {

    /**
     * 调房天下接口的配置
     * @var mixed
     */
    protected $fangConfig;

    /**
     * token
     * @var string
     */
    protected $token;

    /**
     * 构造函数
     * Fang constructor.
     */
    public function __construct(){
        parent::__construct();
        //根据不同的模式加载相应的配置，开发模式dev，测试模式test，生产模式product
        $bizMode = Config::get('biz_mode');
        switch ($bizMode){
            case 'dev':
                $this->fangConfig = Config::get('fang_dev');
                break;
            case 'test':
                $this->fangConfig = Config::get('fang_test');
                break;
            case 'product':
                $this->fangConfig = Config::get('fang_product');
                break;
            default:
                $this->fangConfig = Config::get('fang_product');
                break;
        }
        $this->token = $this->_getToken($this->fangConfig);
    }

    /**
     * 获取区县列表
     * @param $city
     * @return mixed
     */
    public function getDistrictByCityName($city){
        $url = $this->fangConfig['api_address'] . '/Assess/GetDistructByCityName';
        $param = [
            'access_token'  => $this->token,
            'sCity'         => $city,
            'sUserKey'      => $this->fangConfig['userkey']
        ];
        $res = $this->curlPost($url,$param,0);
        return $res;
    }

    /**
     * 根据关键字查询小区列表，即业务中的按地址查询
     * @param int $pageSize
     * @param $city
     * @param $keyword
     * @return mixed
     */
    public function getProjectListByKeyword($pageSize = 5, $city, $keyword){
        $url = $this->fangConfig['api_address'] . '/Assess/GetProjectList';
        $param = [
            'access_token'  => $this->token,
            'iPageIndex'    => 1,
            'iPageSize'     => $pageSize,
            'sCity'         => $city,
            'sDistrict'     => '',
            'sKeyWord'      => $keyword,
            'sUserKey'      => $this->fangConfig['userkey']
        ];
        $res = $this->curlPost($url,$param,0);
        return $res;
    }

    /**
     * 获取市下楼盘信息
     * @param $city
     * @param $startTime
     * @return mixed
     */
    public function getProjectByCity($city, $startTime){
        $url = $this->fangConfig['api_address'] . '/Assess/GetProjectByCity';
        $param = [
            'access_token'  => $this->token,
            'sCity'         => $city,
            'dStartTime'    => $startTime,
            'sUserKey'      => $this->fangConfig['userkey']
        ];
        $res = $this->curlPost($url,$param,0);
        return $res;
    }

    /**
     * 获取楼栋单元列表
     * @param $newCode - 楼盘code
     * @param $city
     * @return mixed
     */
    public function getBanUnitList($newCode, $city){
        $url = $this->fangConfig['api_address'] . '/Assess/GetBanUnitList';
        $param = [
            'access_token'  => $this->token,
            'sNewCode'      => $newCode,
            'sCity'         => $city,
            'sUserKey'      => $this->fangConfig['userkey']
        ];
        $res = $this->curlPost($url,$param,0);
        return $res;
    }

    /**
     * 获取房间列表
     * @param $newCode - 楼盘Code
     * @param $city
     * @param $banCode - 楼栋Code
     * @param $unitCode - 单元Code
     * @return mixed
     */
    public function getRoomList($newCode, $city, $banCode, $unitCode){
        $url = $this->fangConfig['api_address'] . '/Assess/GetRoomList';
        $param = [
            'access_token'  => $this->token,
            'sNewCode'      => $newCode,
            'sCity'         => $city,
            'sBanCode'      => $banCode,
            'sUnitCode'     => $unitCode,
            'sUserKey'      => $this->fangConfig['userkey']
        ];
        $res = $this->curlPost($url,$param,0);
        return $res;
    }

    /**
     * 获取房间详细信息和价格 - 此方法用来为前端提供面积
     * @param $newCode
     * @param $city
     * @param $roomId
     * @return mixed
     */
    public function getPriceByRoomID($newCode, $city, $roomId){
        $url = $this->fangConfig['api_address'] . '/Assess/GetPriceByRoomID';
        $param = [
            'access_token'  => $this->token,
            'sNewCode'      => $newCode,
            'sCity'         => $city,
            'sRoomID'       => $roomId,
            'sUserKey'      => $this->fangConfig['userkey']
        ];
        $res = $this->curlPost($url,$param,0);
        return $res;
    }

    /**
     * 评估价格
     * @param $newCode - 楼盘code
     * @param $city - 城市
     * @param $banCode - 楼栋code
     * @param $unitCode - 单元code
     * @param $roomId - 房间Id
     * @param string $area - 面积
     * @param string $district - 区域
     * @param string $forward - 朝向
     * @param string $totalFloor - 总楼层
     * @param string $floor - 所在楼层
     * @return mixed
     */
    public function getPriceOld($newCode, $city, $banCode, $unitCode, $roomId, $area = '', $district = '', $forward = '', $totalFloor = '', $floor = ''){
        $url = $this->fangConfig['api_address'] . '/Assess/GetPrice';
        $param = [
            'access_token'  => $this->token,
            'sNewCode'      => $newCode,
            'sCity'         => $city,
            'sDistrict'     => $district,
            'sBanCode'      => $banCode,
            'sUnitCode'     => $unitCode,
            'sRoomID'       => $roomId,
            'fArea'         => $area,   //面积
            'sForward'      => $forward,    //朝向
            'iTotalFloor'   => $totalFloor, //总楼层
            'iFloor'        => $floor,  //当前楼层
            'sUserKey'      => $this->fangConfig['userkey']
        ];
        $res = $this->curlPost($url,$param,0);
        return $res;
    }

    /**
     * 评估价格（参数为数组格式）
     * @param $param
     * @return mixed
     */
    public function getPrice($param){
        $url = $this->fangConfig['api_address'] . '/Assess/GetPrice';
        $param['access_token'] = $this->token;
        $param['sUserKey'] = $this->fangConfig['userkey'];
        $res = $this->curlPost($url,$param,0);
        return $res;
    }

    /**
     * 手动评估提交到房天下（参数为数组格式）
     * @param $param
     * @return mixed
     */
    public function assessOffLine($param){
        $url = $this->fangConfig['api_address'] . '/Assess/AssessOffLine';
        $param['access_token'] = $this->token;
        $param['sNewCode'] = '';
        $param['sProjectName'] = '';
        $param['sUserKey'] = $this->fangConfig['userkey'];
        $res = $this->curlPost($url,$param,0);
        return $res;
    }

    /**
     * 生成token
     * @param $fangConfig
     * @return string
     */
    private function _getToken($fangConfig){
        $randomNum = rand(99,999999);
        $userName = $fangConfig['username']; //别问我为啥这样的驼峰式，为了跟他们的文档一致
        $password = $fangConfig['password'];
        $ip = $fangConfig['ip'];
        $tick = date('Y-m-d H:i:s'); //别问我为啥是这个变量名
        $pToEncrypt = "{$randomNum}^{$userName}^{$password}^{$ip}^{$tick}";
        $des = new Desclass($fangConfig['encrypt_key']);
        $token = strtoupper($des->encrypt($pToEncrypt));
        return $token;
    }

    /**
     * CURL POST请求房天下接口，需要注意Header
     * @param $url
     * @param array $param
     * @param int $decode
     * @return mixed
     */
    public function curlPost($url, $param = array(), $decode = 1){
        $data = http_build_query($param);

        //设定header，房天下比较特殊，得这么设置
        $this_header = array(
            'User-Agent: Mozilla/5.0 (Linux; X11)',
            'Content-Type: application/x-www-form-urlencoded'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this_header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec ($ch);
        curl_close ($ch);
        unset($ch);
        //记录日志
        self::addApiLog($url,$param,$result);

        $result = $decode ? json_decode($result,TRUE) : $result;
        if( ! $result){
            return false;
        }elseif (isset($result['errcode']) && $result['errcode'] !== 0){
            return false;
        }else{
            return $result;
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
        $row = 'FTX-API:' . $url_api . '===###===Send:' . json_encode($data) . '===###===Receive:' . $receive . '===###===URL:' . $url_str;
        Log::write($row,'ldd');
    }

}
