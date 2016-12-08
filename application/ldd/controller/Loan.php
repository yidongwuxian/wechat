<?php
/**
 * 文件的简短描述：老舍房贷主业务
 *
 * 文件的详细描述：老舍房贷主业务
 *
 * LICENSE:
 * @author wangzhen 2016/11/7
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\ldd\controller;

use app\ldd\model\Apply;
use app\ldd\model\Evaluation;
use app\ldd\model\Fang;
use app\ldd\model\Ldd;
use think\Cache;
use think\Config;
use think\Session;
use think\Validate;

/**
 * 类名：Loan
 *
 * 类的详细描述：无
 *
 * LICENSE:
 * @author wangzhen
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
class Loan extends Base {

    protected $mobile = '';

    /**
     * 构造函数
     * Loan constructor.
     */
    public function __construct(){
        parent::__construct();
        $this->mobile = Session::get('mobile');
    }

    /**
     * 业务列表（申请列表）
     * @return mixed
     */
    public function index(){
        return $this->fetch();
    }

    /**
     * 获取业务列表（申请记录）
     */
    public function getApplyList(){
        if($this->request->isAjax()){
            $mobile = Session::get('mobile');
            $lddModel = new Ldd($this->bizApi,$mobile);
            $applyList = $lddModel->applyList();
            if(is_array($applyList) && $applyList['success'] == true){
                //查询成功，返回状态字符
                $applyNewList = [];
                $bizStatus = Config::get('biz_status');
                foreach ($applyList['data'] as $item){
                    $statusId = $item['status'];
                    $applyNewList[] = [
                        'status' => $bizStatus["$statusId"],
                        'projectNo' => $item['projectNo'],
                        'custName' => $item['custName'] ? $item['custName'] : "",
                        'applyAmt' => $item['applyAmt'] ? $item['applyAmt'] : "",
                        'hasContract' => isset($item['hasContract']) ? $item['hasContract'] : 2
                    ];
                }
                \Util::echoJson('查询成功！',true,$applyNewList);
            }elseif (is_array($applyList) && $applyList['success'] == false){
                \Util::echoJson($applyList['msg'],false,$this->defaultUri);
            }else{
                \Util::echoJson($this->defaultErrorMessage,false,$this->defaultUri);
            }
        }
    }

    /**
     * Ajax获取机构业务列表
     */
    public function getOrgApplyList(){
        if($this->request->isAjax()){
            $mobile = Session::get('mobile');
            $lddModel = new Ldd($this->bizApi,$mobile);
            $applyList = $lddModel->orgApplyList();
            if(is_array($applyList) && $applyList['success'] == true){
                //查询成功，返回状态字符
                $applyNewList = [];
                $bizStatus = Config::get('biz_status');
                foreach ($applyList['data'] as $item){
                    $statusId = $item['status'];
                    $applyNewList[] = [
                        'status' => $bizStatus["$statusId"],
                        'projectNo' => $item['projectNo'],
                        'custName' => $item['custName'] ? $item['custName'] : "",
                        'applyAmt' => $item['applyAmt'] ? $item['applyAmt'] : "",
                        'hasContract' => isset($item['hasContract']) ? $item['hasContract'] : 2
                    ];
                }
                \Util::echoJson('查询成功！',true,$applyNewList);
            }elseif (is_array($applyList) && $applyList['success'] == false){
                \Util::echoJson($applyList['msg'],false,$this->defaultUri);
            }else{
                \Util::echoJson($this->defaultErrorMessage,false,$this->defaultUri);
            }
        }
    }

    /**
     * 房产评估（自动） - POST提交时为自动评估
     * @return mixed
     */
    public function evaluation(){
        if($this->request->isAjax()){
            //从缓存中读取用户评估的房产信息，后续用来回显使用
            $address = Cache::get($this->mobile . '_address');
            if( ! $address || empty($address['province']) || empty($address['city']) || empty($address['projectName'])){
                \Util::echoJson('长时间未操作，请重新评估！');
            }
            //必填参数
            $params = [
                'sNewCode'  => $this->request->param('newCode'), //楼盘（小区）code
                'sCity'     => $city = $this->request->param('city'), //城市
                'sBanCode'  => $banCode = $this->request->param('banCode'), //楼栋code
                'sUnitCode' => $unitCode = $this->request->param('unitCode'), //单元id
                'sRoomID'   => $this->request->param('roomId'), //房间Id
                'fArea'     => $this->request->param('area'), //面积
            ];
            /*$params = [
                'sNewCode'  => '1010067721', //楼盘（小区）code
                'sCity'     => '北京市', //城市
                'sBanCode'  => '999999', //楼栋code
                'sUnitCode' => '999999', //单元id
                'sRoomID'   => '999999', //房间Id
                'fArea'     => '60', //面积
            ];*/
            //选填参数
            $params2 = [
                'sForward'  => $this->request->param('forward') ? $this->request->param('forward') : '', //朝向
                'iTotalFloor' => $this->request->param('totalFloor') ? $this->request->param('totalFloor') : '', //总楼层
                'iFloor'    => $this->request->param('floor') ? $this->request->param('floor') : '', //所在楼层
            ];
            /*$params2 = [
                'sForward'  => 1, //朝向
                'iTotalFloor' => 20, //总楼层
                'iFloor'    => 18, //所在楼层
            ];*/
            //如果楼栋code、单元id、房间id中的任何一个为其它，params2中的参数全部不能为空
            if($params['sBanCode'] == '999999' || $params['sUnitCode'] == '999999' || $params['sRoomID'] == '999999'){
                //如果选择了其他，那么缓存中记录的房屋信息需要更新
                if($params['sBanCode'] == '999999' || $params['sUnitCode'] == '999999'){
                    //如果楼栋单元选择了其他，楼栋单元和房间号均需要文字输入
                    $banName = $this->request->param('banName');
                    //$banName = '花园路小区';
                    $roomNo = $this->request->param('roomNo');
                    //$roomNo = '105';
                    if( ! $banName || ! $roomNo){
                        \Util::echoJson('请将楼栋单元、房间号补充完整！');
                    }
                    $address['banName'] = $banName;
                    $address['roomNo'] = $roomNo;
                    Cache::set($this->mobile . '_address',$address);
                }elseif($params['sRoomID'] == '999999'){
                    //如果房间号选择其他，房间号必须手动输入文字
                    $roomNo = $this->request->param('roomNo');
                    if( ! $roomNo){
                        \Util::echoJson('请将房间号补充完整！');
                    }
                    $address['roomNo'] = $roomNo;
                    Cache::set($this->mobile . '_address',$address,3600);
                }
                //校验params2中的参数
                foreach ($params2 as $value){
                    if( ! $value){
                        //如果有值为空
                        \Util::echoJson('请将朝向、总楼层、所在楼层补充完整！');
                    }
                }
            }
            //校验参数正确性
            $rules = [
                'sNewCode'  => 'require|integer',
                'sCity'     => 'require',
                'sBanCode'  => 'require|integer',
                'sUnitCode' => 'require|integer',
                'sRoomID'   => 'require|integer',
                'fArea'     => 'require|number',
                'sForward'  => 'integer',
                'iTotalFloor' => 'integer',
                'iFloor'    => 'integer'
            ];
            $message = [
                'sNewCode'  => '楼盘（小区）信息有误！',
                'sCity'     => '城市信息有误！',
                'sBanCode'  => '楼栋单元信息有误！',
                'sUnitCode' => '楼栋单元信息有误！',
                'sRoomID'   => '房间号信息有误！',
                'fArea'     => '面积必填或输入有误！',
                'sForward'  => '朝向选择有误！',
                'iTotalFloor' => '总楼层输入有误！',
                'iFloor'    => '所在楼层输入有误！'
            ];
            //合并params
            $params = array_merge($params,$params2);
            //校验传参的正确性
            $validate = new Validate($rules,$message);
            if( ! $validate->check($params)){
                \Util::echoJson($validate->getError());
            }

            //处理接口数据，调房天下接口
            $params['sDistrict'] = ''; //区域，自动评估不传此字段
            $params['sCity'] = str_replace('市','',$params['sCity']); //市
            if($params['sForward']){
                $params['sForward'] = Config::get('forward')[$params['sForward']]; //朝向
            }
            $params['sBanCode'] = $params['sBanCode'] == '999999' ? '' :  $params['sBanCode'];
            $params['sUnitCode'] = $params['sUnitCode'] == '999999' ? '' :  $params['sUnitCode'];
            $params['sRoomID'] = $params['sRoomID'] == '999999' ? '' :  $params['sRoomID'];
            $fang = new Fang();
            $resultStr = $fang->getPrice($params); //房天下返回的数据，JSON格式
            $result = json_decode($resultStr,true);    //将房天下返回的数据做decode
            if( ! $result){
                //如果非JSON格式，认为返回数据有误
                \Util::echoJson('评估机构评估失败！请进行人工评估！');
            }
            //判断返回的数据是否有相应的价格
            if(empty($result['fTotalPrice']) || //房产总价
                empty($result['fPrice']) || //房产均价
                empty($result['fDistrictPrice']) || //区县均价
                empty($result['fCityPrice']) || //城市均价
                empty($result['fCityPriceMonth'])){ //城市近六个月均价
                //如果以上指标项房天下返回的数据为空，需要进行人工评估
                \Util::echoJson('评估机构无此房产数据！请进行人工评估！');
            }

            //如果房天下返回数据无误，各项指标正常，创建一条评估记录并入库
            //拼接房产信息
            $address = Cache::get($this->mobile . '_address');
            $addressStr = '';
            foreach ($address as $value){
                $addressStr .= $value . '，';
            }
            $addressStr = trim($addressStr,'，');

            $mobile = Session::get('mobile');
            //存入字段
            $data = [
                'openid'    => $this->openid,
                'mobile'    => $mobile ? $mobile : '',
                'evaluation_time'   => time(),
                'type'      => 1, //自动评估
                'attribute' => 1, //房屋属性为住宅，自动评估肯定是住宅
                'status'    => 3, //评估结束但数据不完整
                'address'   => $addressStr,
                'fang_info' => $resultStr,
                'biz_info'  => json_encode($params)
            ];
            $evaluationModel = new Evaluation();
            if( ! $evaluationModel->save($data)){
                \Util::echoJson('数据存储失败，请重新评估！');
            }
            //保存成功
            $id = $evaluationModel->id; //获取入库的主键id，用于页面跳转
            $url = '/ldd/loan/detail?id=' . $id;
            \Util::echoJson('评估成功！',true,$url);
            return false;
        }else{
            $this->assign('forward',Config::get('forward')); //朝向
            return $this->fetch();
        }
    }

    /**
     * 手动（人工）评估，只接受Ajax方式
     */
    public function mEvaluation(){
        if($this->request->isAjax()){
            //从缓存中读取用户评估的房产信息，后续用来回显使用
            $address = Cache::get($this->mobile . '_address');
            if( ! $address || empty($address['province'])){
                \Util::echoJson('长时间未操作，请重新评估！');
            }
            //必填参数
            $params = [
                'sType'     => $type = $this->request->param('type'), //房产类型，1为住宅，2为商铺
                'sCity'     => $city = $this->request->param('city'), //城市
                'sDistrict' => $district = $this->request->param('district'), //区县
                'sAddress'  => $addressPost = $this->request->param('address'), //POST发来的房产地址（没有省市区）
                'sBan'      => $ban = $this->request->param('banName'), //楼栋中文
                'sUnit'     => '', //单元中文，由于前端页面中与楼栋合并为一个字段了，这里直接赋值为空
                'sRoom'     => $roomNo = $this->request->param('roomNo'), //房间号
                'fArea'     => $this->request->param('area'), //面积
                'sForward'  => $this->request->param('forward'), //朝向
                'iTotalFloor' => $this->request->param('totalFloor'), //总楼层
                'iFloor'    => $this->request->param('floor'), //所在楼层
                'sFacethestreet' => $isFaceStreet = $this->request->param('isFaceStreet') //是否临街
            ];
            /*$params = [
                'sType'     => $type = 2, //房产类型，1为住宅，2为商铺
                'sCity'     => $city = '北京市', //城市
                'sDistrict' => $district = '朝阳区', //区县
                'sAddress'  => $addressPost = '紫南家园', //POST发来的房产地址（没有省市区）
                'sBan'      => $ban = '一号楼2单元', //楼栋中文
                'sUnit'     => '', //单元中文，由于前端页面中与楼栋合并为一个字段了，这里直接赋值为空
                'sRoom'     => $roomNo = '101', //房间号
                'fArea'     => 100.5, //面积
                'sForward'  => 1, //朝向
                'iTotalFloor' => 24, //总楼层
                'iFloor'    => 10, //所在楼层
                'sFacethestreet' => $isFaceStreet = 1 //是否临街
            ];*/
            //校验参数正确性
            $rules = [
                'sType|房产类型'    => 'require|integer',
                'sCity|城市'  => 'require',
                'sDistrict|区县'  => 'require',
                'sAddress|房产地址' => 'require',
                'sBan|楼栋单元' => 'require',
                'sRoom|房间号' => 'require|integer',
                'fArea|面积'  => 'require|number',
                'sForward|朝向'   => 'require|integer',
                'iTotalFloor|总楼层'   => 'require|integer',
                'iFloor|所在楼层'   => 'require|integer',
                'sFacethestreet|是否临街'   => 'requireIf:sType,2' //当选择商铺的时候，此参数必填
            ];
            //校验传参的正确性
            $validate = new Validate($rules);
            if( ! $validate->check($params)){
                \Util::echoJson($validate->getError());
            }
            //处理接口数据，调房天下接口
            $params['sType'] = $type == 1 ? '住宅' : '商铺';
            $params['sCity'] = str_replace('市','',$params['sCity']); //市
            $params['sDistrict'] = str_replace('区','',$params['sDistrict']); //区
            $params['sFacethestreet'] = $isFaceStreet == 1 ? '是' : '否';
            $params['sFacethestreet'] = $isFaceStreet == '' ? '' : $params['sFacethestreet']; //是否临街
            $params['sForward'] = Config::get('forward')[$params['sForward']]; //朝向

            //存入字段
            $addressStr = $address['province'] . '，' . $city . '，' . $district . '，' . $addressPost . '，' . $ban . '，' . $roomNo;
            $mobile = Session::get('mobile');
            $data = [
                'openid'    => $this->openid,
                'mobile'    => $mobile ? $mobile : '',
                'evaluation_time'   => time(),
                'type'      => 2, //自动评估
                'attribute' => $type,
                'status'    => 0, //评估中
                'address'   => $addressStr,
                'fang_info' => '', //房天下还未返回数据
                'biz_info'  => json_encode($params)
            ];
            $evaluationModel = new Evaluation();
            if( ! $evaluationModel->save($data)){
                \Util::echoJson('数据存储失败，请重新评估！');
            }
            //保存成功
            $id = $evaluationModel->id; //获取入库的主键id，用于调用房天下接口

            //调用房天下接口
            $params['sObjID'] = $id;
            $fang = new Fang();
            $result = $fang->assessOffLine($params);
            $result = json_decode($result,true); //将房天下返回的数据json decode
            if( ! $result){
                //如果房天下返回的结果不是成功
                Evaluation::where('id', $id)
                    ->update(['status' => 2]); //更新状态为评估失败
                \Util::echoJson('第三方机构评估失败，请重新评估！');
            }elseif (isset($result['errcode']) && intval($result['errcode']) === 0){
                \Util::echoJson('人工重新评估成功！',true,'/ldd/loan/evaluationlist');
            }else{
                //如果房天下返回的结果不是成功，此时需要查看
                Evaluation::where('id', $id)
                    ->update(['status' => 2]); //更新状态为评估失败
                \Util::echoJson('第三方机构评估失败，联系客服！');
            }
        }else{
            //$this->assign('forward',Config::get('forward')); //朝向
            return $this->fetch();
        }
    }

    /**
     * 查询评估记录
     * @return bool|mixed
     */
    public function evaluationList(){
        if($this->request->isAjax()){
            //如果是Ajax请求，则认为是调用接口获取业务列表
            $mobile = Session::get('mobile');
            if( ! $mobile){
                \Util::echoJson('获取列表有误，请稍后再试！',false,$this->defaultUri);
            }
            //查询评估有效期
            $evaluationTime = time() - Config::get('evaluation_search_time');
            $evaluationList = Evaluation::where('mobile',$mobile)
                ->where('evaluation_time','>=',$evaluationTime)
                //->where('status','<=',1)
                ->order('evaluation_time','desc')
                ->field('id')
                ->field('evaluation_time')
                ->field('attribute')
                ->field('status')
                ->field('address')
                ->field('limit_price_min')
                ->field('limit_price_max')
                ->select()
                ->toArray();
            \Util::echoJson('查询成功',true,$evaluationList);
            return false;
        }else{
            return $this->fetch();
        }
    }

    /**
     * 评估详情及房产信息
     * @return bool|mixed
     */
    public function detail(){
        if($this->request->isAjax()){
            //评估详情保存操作
            $id = $this->request->param('id'); //主键id
            if(empty($id)){
                \Util::echoJson('参数错误',false);
            }
            $mobile = $mobile = Session::get('mobile'); //mobile
            if( ! $mobile){
                \Util::echoJson('登录超时请重新登录！',false,$this->defaultUri);
            }
            $evaluationInfo = Evaluation::get(['id' => $id,'mobile' => $mobile]);
            if( ! $evaluationInfo){
                \Util::echoJson('你不能修改别人的数据！',false,$this->defaultUri);
            }
            //接受POST参数
            $params = [
                'fArea'     => $this->request->param('area'), //面积
                'sForward'  => $this->request->param('forward'), //朝向
                'iTotalFloor' => $this->request->param('totalFloor'), //总楼层
                'iFloor'    => $this->request->param('floor'), //所在楼层
                'sElevator'    => $this->request->param('elevator'), //有无电梯
                'sYear'     => $this->request->param('year'), //竣工年代
                'mortgageType' => $this->request->param('mortgageType'), //抵押类型：1一押，2二押
                'houseType' => $this->request->param('houseType'), //房产类型，注意区分，此处为住宅、普通住宅等
                'oneResidual' => $this->request->param('oneResidual'), //一押剩余本金,
                'isFive' => $this->request->param('isFive'), //交易是否满5年,
                'haveKFC' => $this->request->param('haveKFC'), //周边是否有麦当劳或肯德基,
            ];
            /*$params = [
                'fArea'     => 100.5, //面积
                'sForward'  => 2, //朝向
                'iTotalFloor' => 25, //总楼层
                'iFloor'    => 12, //所在楼层
                'sElevator'    => 1, //有无电梯
                'sYear'     => 2008, //竣工年代
                'mortgageType' => 2, //抵押类型：1一押，2二押
                'houseType' => 1, //房产类型，注意区分，此处为住宅、普通住宅等
                'oneResidual' => 22.5, //一押剩余本金,
                'isFive' => 2, //交易是否满5年,
                'haveKFC' => 2, //周边是否有麦当劳或肯德基,
            ];*/
            //校验参数正确性
            $rules = [
                'fArea|面积'  => 'require|number',
                'sForward|朝向'   => 'require|integer',
                'iTotalFloor|总楼层'   => 'require|integer',
                'iFloor|所在楼层'   => 'require|integer',
                'sElevator|有无电梯'    => 'require|in:1,2', //有无电梯，1有2无
                'sYear|竣工年代'     => 'require|integer|length:4', //竣工年代
                'mortgageType|抵押类型' => 'require|in:1,2', //抵押类型：1一押，2二押
                'houseType|房产类型' => 'require|integer', //房产类型，注意区分，此处为住宅、普通住宅等
                'oneResidual|一押剩余本金' => 'requireIf:mortgageType,2|number', //一押剩余本金，只有在二押的时候验证必填
                'isFive|是否满五年' => 'requireIf:mortgageType,2|in:1,2', //交易是否满5年，只有在二押的时候验证必填
                'haveKFC|周边是否有麦当劳或肯德基' => 'requireIf:mortgageType,2|in:1,2', //周边是否有麦当劳或肯德基，只有在二押的时候验证必填
            ];
            //校验传参的正确性
            $validate = new Validate($rules);
            if( ! $validate->check($params)){
                \Util::echoJson($validate->getError());
            }
            //参数校验完成
            $bizInfo = $evaluationInfo->biz_info; //数据库中用户存储的数据

            //处理用户提交的数据
            $forwardId = $params['sForward'];
            $params['sForward'] = Config::get('forward')[$forwardId];
            $params['sElevator'] = $params['sElevator'] == 1 ? '有' : '无';

            //获取PC端评估价值区间
            //限额为空时查询限额
            $limit_price_max = $evaluationInfo->limit_price_max;
            if(empty($limit_price_max)){
                //只有评估完成但数据不完整的前提下调用PC端接口
                $fangInfo = $evaluationInfo->fang_info; //数据库中房天下返回的存储的数据
                $fangInfo = json_decode($fangInfo,true);
                $totalPrice = $fangInfo['fTotalPrice']; //房天下返回的房产总价
                $oneResidualPrincipal = $params['oneResidual'] ? $params['oneResidual'] : ""; //一押剩余本金没有的话设为空
                $userInfo = Session::get('loginInfo');
                $orgId = $userInfo['orgId']; //用户机构Id
                //调用PC接口
                $lddModel = new Ldd($this->bizApi,$this->mobile);
                $result = $lddModel->getEvaluationPrice($orgId,$params['houseType'],$params['mortgageType'],$totalPrice,$oneResidualPrincipal);
                if(is_array($result) && $result['success'] == true){
                    $price = $result['data'];
                    $priceArray = explode("-",$price);
                    $evaluationInfo->limit_price_min = $priceArray[0];
                    $evaluationInfo->limit_price_max = $priceArray[1];
                }else{
                    \Util::echoJson('查询房产限额失败！');
                }
            }

            $bizInfo = json_decode($bizInfo,true);
            $bizInfo = array_merge($bizInfo,$params);

            //存储
            $evaluationInfo->status = 1; //将状态改为评估完整且数据完整，此时可以提交申请
            $evaluationInfo->biz_info = json_encode($bizInfo);
            if( ! $evaluationInfo->save()){
                \Util::echoJson('存储数据失败，请重新提交！');
            }
            \Util::echoJson('信息保存成功！',true,'/ldd/loan/evaluationlist');
            return false;
        }else{
            //判断id
            /*if(empty($id)) $this->error('参数错误',$this->defaultUri); //必须传第二个参数，否则页面跳转有问题
            $mobile = Session::get('mobile');
            //获取评估信息
            $evaluationInfo = Evaluation::get(['id' => $id,'mobile' => $mobile]);
            if( ! $evaluationInfo){
                $this->error('查询信息有误！',$this->defaultUri); //必须传第二个参数，否则页面跳转有问题
            }
            $evaluationInfo = $evaluationInfo->toArray();

            //判断房产能否编辑
            if($evaluationInfo['status'] == 0){
                //房产处于评估中或评估失败时，不能再次编辑
                $this->error('该房产正在评估中！',$this->defaultUri);
            }elseif ($evaluationInfo['status'] == 2){
                $this->error('房产评估失败，请重新发起评估！',$this->defaultUri);
            }

            //房产信息
            $data['address'] = $evaluationInfo['address']; //中文地址
            $fangInfo = json_decode($evaluationInfo['fang_info'],true); //房天下返回的数据
            $bizInfo = json_decode($evaluationInfo['biz_info'],true); //用户自己输入的数据
            //朝向（优先使用用户自己输入的）
            $forwardList = Config::get('forward');
            $forward = $bizInfo['sForward'] == "" ? $fangInfo['sTowards'] : $bizInfo['sForward'];
            $forwardId = array_search($forward,$forwardList); //找到朝向的键名
            //$data['forward'] = $forwardId ? $forwardId : array_keys($forwardList,end($forwardList)); //如果找不到则显示其他
            $data['forward'] = $forwardId ? $forwardId : 10; //如果找不到则显示其他

            $data['area'] = $bizInfo['fArea'] == "" ? $fangInfo['fArea'] : $bizInfo['fArea']; //房屋面积
            $data['totalFloor'] = $bizInfo['iTotalFloor'] == "" ? $fangInfo['iTotalfloor'] : $bizInfo['iTotalFloor']; //总楼层
            $data['floor'] = $bizInfo['iFloor'] == "" ? $fangInfo['iFloor'] : $bizInfo['iFloor']; //所在楼层
            $data['elevator'] =  empty($bizInfo['sElevator']) ? $fangInfo['sElevator'] : $bizInfo['sElevator']; //有无电梯
            $fangInfo['sYears'] = $fangInfo['sYears'] == '未知' ? '' : $fangInfo['sYears']; //靠，特么的房天下的竣工年代还有未知这一说
            $data['year'] = empty($bizInfo['sYears']) ? substr($fangInfo['sYears'],0,4) : $bizInfo['sYears']; //竣工年代
            $data['city'] = $bizInfo['sCity'];

            $this->assign('id',$id);
            $this->assign('data',$data);*/
            return $this->fetch();
        }
    }

    /**
     * 获取评估详情
     */
    public function getDetail(){
        if($this->request->isAjax()){
            $id = $this->request->param('id');
            if(! $id) \Util::echoJson('参数错误！',false,$this->defaultUri);
            //获取评估信息
            $evaluationInfo = Evaluation::get(['id' => $id,'mobile' => $this->mobile]);
            if( ! $evaluationInfo){
                \Util::echoJson('查询信息有误！',false,$this->defaultUri);
            }
            $evaluationInfo = $evaluationInfo->toArray();

            //判断房产能否编辑
            if($evaluationInfo['status'] == 0){
                //房产处于评估中或评估失败时，不能再次编辑
                \Util::echoJson('该房产正在评估中！',false,$this->defaultUri);
            }elseif ($evaluationInfo['status'] == 2){
                \Util::echoJson('房产评估失败，请重新发起评估！',false,$this->defaultUri);
            }

            //房产信息
            $data['address'] = $evaluationInfo['address']; //中文地址
            $data['attribute'] = $evaluationInfo['attribute']; //房产属性，1住宅，2商铺
            $fangInfo = json_decode($evaluationInfo['fang_info'],true); //房天下返回的数据
            $bizInfo = json_decode($evaluationInfo['biz_info'],true); //用户自己输入的数据
            //朝向（优先使用用户自己输入的）
            $forwardList = Config::get('forward');
            $forward = $bizInfo['sForward'] == "" ? $fangInfo['sTowards'] : $bizInfo['sForward'];
            $forwardId = array_search($forward,$forwardList); //找到朝向的键名
            //$data['forward'] = $forwardId ? $forwardId : array_keys($forwardList,end($forwardList)); //如果找不到则显示其他
            $data['forward'] = $forwardId ? $forwardId : 10; //如果找不到则显示其他

            $data['area'] = $bizInfo['fArea'] == "" ? $fangInfo['fArea'] : $bizInfo['fArea']; //房屋面积
            $data['totalFloor'] = $bizInfo['iTotalFloor'] == "" ? $fangInfo['iTotalfloor'] : $bizInfo['iTotalFloor']; //总楼层
            $data['floor'] = $bizInfo['iFloor'] == "" ? $fangInfo['iFloor'] : $bizInfo['iFloor']; //所在楼层
            $data['elevator'] =  empty($bizInfo['sElevator']) ? $fangInfo['sElevator'] : $bizInfo['sElevator']; //有无电梯
            $fangInfo['sYears'] = $fangInfo['sYears'] == '未知' ? '' : $fangInfo['sYears']; //靠，特么的房天下的竣工年代还有未知这一说
            $data['year'] = empty($bizInfo['sYears']) ? substr($fangInfo['sYears'],0,4) : $bizInfo['sYears']; //竣工年代
            $data['city'] = $bizInfo['sCity'];

            //房产情况
            $data['mortgageType'] = empty($bizInfo['mortgageType']) ? "" : $bizInfo['mortgageType']; //抵押类型，空，1一押，2二押
            $data['houseType'] = empty($bizInfo['houseType']) ? "" : $bizInfo['houseType']; //房产类型
            $data['oneResidual'] = empty($bizInfo['oneResidual']) ? "" : $bizInfo['oneResidual']; //一押剩余本金
            $data['isFive'] = empty($bizInfo['isFive']) ? "" : $bizInfo['isFive']; //房产交易是否满5年
            $data['haveKFC'] = empty($bizInfo['haveKFC']) ? "" : $bizInfo['haveKFC']; //周边是否有肯德基麦当劳

            \Util::echoJson('查询成功！',true,$data);
        }
    }

    /**
     * 获取抵押类型及房产类型列表
     */
    public function getHouseType(){
        if($this->request->isAjax()){
            $city = $this->request->param('city');
            $mobile = Session::get('mobile');
            if( ! $city || ! $mobile){
                \Util::echoJson('参数错误！',false,$this->defaultUri);
            }
            //查看缓存中是否有数据
            $cityEncode = base64_encode($city);
            $houseType = Cache::get($cityEncode);
            if( ! $houseType){
                //如果缓存中没有数据
                //调用接口获取抵押类型
                $lddModel = new Ldd($this->bizApi,$mobile);
                $houseType = $lddModel->getGuarantyAndHouseType($city);
                if(is_array($houseType) && $houseType['success']){
                    $houseType = $houseType['data'];
                    //将结果计入缓存
                    Cache::set($cityEncode,$houseType,600);
                }else{
                    //查询失败
                    \Util::echoJson('查询失败');
                }
            }
            \Util::echoJson('查询成功',true,$houseType);
        }
    }

    /**
     * 校验评估数据能否提交房产申请
     */
    public function checkLoan(){
        if($this->request->isAjax()){
            $ids = $this->request->param('ids/a'); //提交的评估id列表，数组格式
            $mobile = Session::get('mobile');
            $maxHouseCount = Config::get('max_house_count');
            if( ! $ids || ! is_array($ids) || empty($ids)){
                \Util::echoJson('请至少选择一个房产！');
            }elseif (count($ids) > $maxHouseCount){
                \Util::echoJson('最多只能选择' . $maxHouseCount . '个房产！');
            }elseif (! $mobile){
                \Util::echoJson('参数错误！');
            }
            $evaluationList = []; //用了存储评估记录的数组，后面存入缓存，直接展示到页面中
            $evaluationTime = time() - Config::get('evaluation_search_time') - 300; //比五天之前再延长5分钟，考虑页面等待时间
            foreach ($ids as $id){
                //遍历id列表，校验评估记录正确性
                $evaluationInfo = Evaluation::get($id);
                if( ! $evaluationInfo){
                    \Util::echoJson('选择的数据无效！');
                }
                $evaluationInfo = $evaluationInfo->toArray();
                if($evaluationInfo['status'] != 1){
                    \Util::echoJson('有不可申请的评估记录，请重新选择！');
                }elseif ($evaluationInfo['mobile'] != $mobile){
                    \Util::echoJson('有非法数据，请重新选择！');
                }elseif ($evaluationInfo['evaluation_time'] < $evaluationTime){
                    \Util::echoJson('有失效的评估记录！');
                }
                //校验成功
                $evaluationList[] = $evaluationInfo;
            }
            //将记录暂时记录到缓存中
            Cache::set('evaluation_apply_list_' . $mobile,$evaluationList,60); //只存一分钟，一分钟之内页面必须跳转
            \Util::echoJson('校验成功',true,'/ldd/loan/apply');
        }
    }

    /**
     * 贷款申请
     * @return mixed
     */
    public function apply(){
        if($this->request->isAjax()){
            $mobile = Session::get('mobile');
            if( ! $mobile){
                \Util::echoJson('页面请求超时',false,$this->defaultUri);
            }
            $params = [
                'token' => $token = $this->request->param('token'),
                'custName' => $this->request->param('custName'), //客户姓名
                'idCard' => $idCard = $this->request->param('idCard'), //身份证号
                'loanLimit' => $this->request->param('loanLimit'), //贷款期限
                'amount' => $this->request->param('amount'), //贷款金额
            ];
            $rules = [
                'token|页面标识' => 'require',
                'custName|客户姓名' => [
                    'regex' => '/^[a-zA-Z\x{4e00}-\x{9fa5}]+$/u',
                    'require'
                ],
                'idCard|身份证号' => [
                    'regex' => '/(^\d{18}$)|(^\d{17}(\d|X|x)$)/',
                    'require'
                ],
                'loanLimit|贷款期限' => 'require|integer',
                'amount|贷款金额' => 'require|integer'
            ];
            $message = [
                'custName.regex' => '姓名只能为中文或字母！',
                'idCard.regex' => '身份证号格式不正确'
            ];
            //校验正确性
            $validate = new Validate($rules,$message);
            if( ! $validate->check($params)){
                \Util::echoJson($validate->getError());
            }
            //校验年龄
            //检查身份证年龄是否在18-65岁之间
            $birthYear = substr($idCard, 6, 4);
            $year   = getdate()['year'];
            $dValue = $year - $birthYear;
            if($dValue < 18 || $dValue > 65) {
                \Util::echoJson('年龄须在18-65岁之间！',false,$this->defaultUri);
            }
            //校验贷款金额是否在规定区间
            //由于多套抵押物，城市不能确定为一个，接口暂时不调用，也暂时不做校验


            //根据token获取缓存中的评估列表
            $evaluationList = Cache::get($mobile . '_' . $token);
            if(empty($evaluationList)){
                \Util::echoJson('由于您长时间未操作，页面失效！',false,'/ldd/loan/index');
            }

            //用户提交数据正常，缓存有评估记录，整理数据准备调用PC端接口
            $evaluationNewList = [];
            $forwardList = Config::get('forward');
            $min = 0; //可贷额度下限
            $max = 0; //可贷款最大额度
            foreach($evaluationList as $item){
                $arr = [];
                $fangInfo = json_decode($item['fang_info'],true); //房天下返回的数据
                $bizInfo = json_decode($item['biz_info'],true); //业务数据
                $arr['evaluateType'] = $item['type']; //评估类型，1自动，2手动
                $arr['estateType'] = $item['attribute']; //房产类型，1住宅，2商铺
                $arr['city'] = $bizInfo['sCity'];
                $arr['sNewCode'] = empty($bizInfo['sNewCode']) ? "" : $bizInfo['sNewCode']; //小区code
                $arr['sBanCode'] = empty($bizInfo['sBanCode']) ? "" : $bizInfo['sBanCode']; //楼栋单元code
                $arr['sRoomId'] = empty($bizInfo['sRoomID']) ? "" : $bizInfo['sRoomID']; //房间Id
                $arr['sUnitCode'] = empty($bizInfo['sUnitCode']) ? "" : $bizInfo['sUnitCode']; //单元code
                $arr['builtUpArea'] = empty($bizInfo['fArea']) ? 0 : $bizInfo['fArea']; //面积
                $arr['address'] = $item['address']; //房产地址，有省市等信息拼接而成
                $forward = empty($bizInfo['sForward']) ? "" : $bizInfo['sForward']; //朝向文字
                $forwardId = array_search($forward,$forwardList); //找到朝向的键名
                //$arr['housingOrientation'] = $forwardId ? $forwardId : array_keys($forwardList,end($forwardList)); //如果找不到则显示其他
                $arr['housingOrientation'] = $forwardId ? $forwardId : 10; //如果找不到则显示其他
                //上面那个朝向真麻烦了
                $arr['totalFloor'] = empty($bizInfo['iTotalFloor']) ? "" : $bizInfo['iTotalFloor']; //总楼层
                $arr['theFloor'] = empty($bizInfo['iFloor']) ? "" : $bizInfo['iFloor']; //当前楼层
                $arr['mortgageType'] = empty($bizInfo['mortgageType']) ? "" : $bizInfo['mortgageType']; //抵押类型，1一押,2二押
                $arr['housingType'] = empty($bizInfo['houseType']) ? "" : $bizInfo['houseType']; //房产详细类型
                $elevator = isset($bizInfo['sElevator']) ? $bizInfo['sElevator'] : 2;
                $arr['lift'] = $elevator == "有" ? 1 : 2; //有无电梯
                $arr['housingCompletionTime'] = empty($bizInfo['sYear']) ? "" : $bizInfo['sYear']; //竣工年代
                $arr['oneResidualPrincipal'] = empty($bizInfo['oneResidual']) ? "" : $bizInfo['oneResidual']; //一押剩余本金
                $arr['tradeYear'] = empty($bizInfo['isFive']) ? "" : $bizInfo['isFive']; //房产交易是否满五年
                $arr['tradeYear'] = $arr['tradeYear'] == 2 ? 0 : $arr['tradeYear']; //如果存的是2，这里变为0，与PC端吻合
                $arr['fastFoodEstablishment'] = empty($bizInfo['haveKFC']) ? "" : $bizInfo['haveKFC']; //房产交易是否满五年
                $arr['fastFoodEstablishment'] = $arr['fastFoodEstablishment'] == 2 ? 0 : $arr['fastFoodEstablishment']; //如果存的是2，这里变为0，与PC端吻合
                //房天下数据
                $arr['assessmentValue'] = empty($fangInfo['fTotalPrice']) ? 0 : $fangInfo['fTotalPrice']; //房产总价
                $arr['assessmentSingle'] = empty($fangInfo['fPrice']) ? 0 : $fangInfo['fPrice']; //房产单价
                $arr['assessmentAverageMonthly1'] = empty($fangInfo['fCityPrice']) ? 0 : $fangInfo['fCityPrice']; //城市均价
                $arr['assessmentAverage1'] = empty($fangInfo['fCityPriceMonth']) ? 0 : $fangInfo['fCityPriceMonth']; //城市近六个月均价
                $arr['assessmentAverageMonthly2'] = empty($fangInfo['fDistrictPrice']) ? 0 : $fangInfo['fDistrictPrice']; //区县均价
                //可贷款最大额度
                $min += $item['limit_price_min'];
                $max += $item['limit_price_max'];
                $evaluationNewList[] = $arr;
            }
            //校验贷款额度
            $showMax = floor($max / 10000);
            if($params['amount'] > $showMax){
                \Util::echoJson('可贷款最大额度为：' . $showMax . '万元！');
            }
            //贷款申请参数
            $applyInfo = [
                'custName' => $params['custName'],
                'custIdCard' => $params['idCard'],
                'loanTerm' => $params['loanLimit'], //贷款期限
                'applyLimit' => $params['amount'], //申请额度
                'min' => $min, //所有房产可贷额度下限之和
                'max' => $max, //所有房产可贷额度上限之和
                'realEstateInfoList' => $evaluationNewList
            ];
            $applyInfo = json_encode($applyInfo);
            $lddModel = new Ldd($this->bizApi,$mobile);
            $result = $lddModel->loanApply($applyInfo); //调PC端接口

            //存储数据
            $data = [
                'openid' => $this->openid,
                'mobile' => $mobile,
                'apply_info' => json_encode($evaluationNewList),
                'receive_info' => $result
            ];
            $applyModel = new Apply();
            $applyModel->save($data); //存入数据库

            if(is_array($result) && $result['success'] == true){
                //页面调用成功
                \Util::echoJson('申请成功',true,'/ldd/loan/index');
            }elseif (is_array($result) && $result['success'] == false){
                \Util::echoJson($result['msg'],false,'/ldd/loan/evaluationlist');
            }else{
                \Util::echoJson($this->defaultErrorMessage,false,'/ldd/loan/evaluationlist');
            }
            return false;
        }else{
            $mobile = Session::get('mobile');
            $evaluationList = Cache::get('evaluation_apply_list_' . $mobile); //从缓存中读取评估记录
            if( ! $evaluationList){
                $this->error('页面失效！',$this->defaultUri);
            }
            //如果页面不失效，继续保存5分钟
            $token = md5(time() . rand(10,99));
            Cache::set($mobile . '_' . $token,$evaluationList,300); //存储5分钟
            $this->assign('evaluationList',$evaluationList);
            $this->assign('token',$token);
            return $this->fetch();
        }
    }

    /**
     * 订单详情
     * @return bool|mixed
     */
    public function orderDetail(){
        if($this->request->isAjax()){
            $projectNo = $this->request->param('projectNo');
            //$projectNo = 'Y201011611300002';
            if( ! $projectNo){
                \Util::echoJson('缺少参数！');
            }
            //获取订单信息
            $mobile = Session::get('mobile');
            $lddModel = new Ldd($this->bizApi,$mobile);
            $res = $lddModel->bizInfo($projectNo);
            if(is_array($res) && $res['success'] == TRUE){
                $detail = $res['data'];
                $statusList = Config::get('biz_status');
                //整理返回数据
                $data = [
                    'projectNo' => $detail['projectNo'],
                    'custName' => $detail['customerName'],
                    'projectStatus' => $statusList[$detail['projectStatus']],
                    'createTime' => $detail['sysCreateTime'] ? date('Y-m-d H:i:s',strtotime(substr($detail['sysCreateTime'],0,strlen($detail['sysCreateTime']) - 2))) : "",
                    'enableLoanMoney' => $detail['enableLoanMoney'],
                    'remark' => $detail['remark'] ? $detail['remark'] : "",
                    'description' => $detail['description'] ? $detail['description'] : "",
                    'stage' => $detail['stageValue'] ? $detail['stageValue'] : 1,
                ];
                //订单是否失败
                $data['isFailed'] = $detail['projectStatus'] == 4 ? 1 : 2;
                $data['canAppoint'] = $detail['projectStatus'] == 8 ? 1 : 2; //能否预约尽调
                //能否修改预约
                if($detail['projectStatus'] == 9 || $detail['projectStatus'] == 10){
                    $data['canModifyAppoint'] = 1;
                }else{
                    $data['canModifyAppoint'] = 2;
                }
                \Util::echoJson('查询成功',true,$data);
            }else if(is_array($res) && $res['success'] == FALSE){
                \Util::echoJson($res['msg'],false,$this->defaultUri);
            }else{
                \Util::echoJson($this->defaultErrorMessage,false,$this->defaultUri);
            }
            return false;
        }else{
            return $this->fetch();
        }
    }

    /**
     * 预约尽调
     * @param string $projectNo - 业务编号
     * @return bool|mixed
     */
    public function appointTime($projectNo = ''){
        if($this->request->isAjax()){
            $params = [
                'projectNo' => $this->request->param('projectNo'),
                'appointTime' => $this->request->param('appointTime'),
                'remark' => $this->request->param('remark'),
            ];
            $rules = [
                'projectNo|订单编号' => 'require',
                'appointTime|预约时间' => 'require',
                'remark|备注' => 'max:50'
            ];
            //校验正确性
            $validate = new Validate($rules);
            if( ! $validate->check($params)){
                \Util::echoJson($validate->getError());
            }
            //校验完成调用接口
            $mobile = Session::get('mobile');
            $lddModel = new Ldd($this->bizApi,$mobile);
            $appointmentTime = strtotime($params['appointTime']);
            $params['appointTime'] = date('Y-m-d H:i:s',$appointmentTime);
            $result = $lddModel->appointTime($params);
            if(is_array($result) && $result['success'] == TRUE){
                \Util::echoJson($result['msg'],true,'/ldd/loan/index');
            }else if(is_array($result) && $result['success'] == FALSE){
                \Util::echoJson($result['msg'],false);
            }else{
                \Util::echoJson($this->defaultErrorMessage,false);
            }
            return false;
        }else{
            empty($projectNo) && $this->error('参数错误！',$this->defaultUri);
            //获取订单信息
            $mobile = Session::get('mobile');
            $lddModel = new Ldd($this->bizApi,$mobile);
            $res = $lddModel->bizInfo($projectNo);
            if(is_array($res) && $res['success'] == TRUE){
                $detail = $res['data'];
                //能否修改预约
                if($detail['projectStatus'] == 8){
                    $data['canAppoint'] = 1;
                }elseif($detail['projectStatus'] == 9 || $detail['projectStatus'] == 10){
                    $data['canModifyAppoint'] = 1;
                }else{
                    $data['canModifyAppoint'] = 2;
                    $this->error('不能进行预约尽调！',$this->defaultUri);
                }
                $statusList = Config::get('biz_status');
                //整理返回数据
                $data = [
                    'projectNo' => $detail['projectNo'],
                    'custName' => $detail['customerName'],
                    'projectStatus' => $statusList[$detail['projectStatus']],
                    'appointTime' => $detail['appointTime'] ? date('Y-m-d H:i',strtotime($detail['appointTime'])) : "", //前端不显示秒
                    'enableLoanMoney' => $detail['enableLoanMoney'],
                    'remark' => $detail['remark'] ? $detail['remark'] : "",
                    'description' => $detail['description'] ? $detail['description'] : "",
                    'address' => $detail['address']
                ];
                $this->assign('data',$data);
            }else if(is_array($res) && $res['success'] == FALSE){
                $this->error($res['msg'],$this->defaultUri);
            }else{
                $this->error($this->defaultErrorMessage,$this->defaultUri);
            }
            return $this->fetch();
        }
    }

    public function contractDetail($projectNo = ''){
        empty($projectNo) && $this->error('参数错误！',$this->defaultUri);
        //获取订单信息
        $lddModel = new Ldd($this->bizApi,$this->mobile);
        $res = $lddModel->contractInfo($projectNo);
        if(is_array($res) && $res['success'] == TRUE){
            $detail = $res['data'];
            if( ! count($detail)){
                $this->error('无合同数据！',$this->defaultUri);
            }
            $this->assign('detail',$detail);
        }else if(is_array($res) && $res['success'] == FALSE){
            $this->error($res['msg'],$this->defaultUri);
        }else{
            $this->error($this->defaultErrorMessage,$this->defaultUri);
        }
        return $this->fetch();
    }

    public function test(){
        echo '这是默认页！';
    }


}
