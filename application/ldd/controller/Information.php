<?php
/**
 * 文件的简短描述：无
 *
 * 文件的详细描述：无
 *
 * LICENSE:
 * @author wangzhen 2016/11/18
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\ldd\controller;

use app\ldd\model\Community;
use app\ldd\model\Fang;
use app\ldd\model\ProvinceCity;
use think\Cache;
use think\Config;
use think\Session;

/**
 * 类名：Information
 *
 * 类的详细描述：无
 *
 * LICENSE:
 * @author wangzhen
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
class Information extends Base {

    protected $mobile = '';

    /**
     * 构造函数
     * Baseinfo constructor.
     */
    public function __construct(){
        parent::__construct();
        $this->mobile = Session::get('mobile');
    }

    /**
     * 获取省份列表，状态为已开启状态
     */
    public function getProvince(){
        if($this->request->isAjax()){
            //获取省份列表（已启用）
            $provincelist = ProvinceCity::where('region_level',1)
                ->where('parent_nation_area',156) //中国
                ->where('enable_province',1) //省份启用
                ->order('code')
                ->field('code')
                ->field('nation_area_name as province')
                ->select()
                ->toArray();
            \Util::echoJson('查询成功',true,$provincelist);
        }
    }

    /**
     * 获取城市列表
     */
    public function getCity(){
        if($this->request->isAjax()){
            $province = $this->request->param('province');
            $provinceName = $this->request->param('provinceName');
            if( ! $province || ! $provinceName){
                \Util::echoJson('参数不完整',false,[]);
            }
            //记录当前省份到缓存
            $address = [
              'province' => $provinceName
            ];
            Cache::set($this->mobile . '_address',$address,3600);
            //读取配置
            $municipality = Config::get('municipality');
            if(isset($municipality[$province])){
                //如果该省份是直辖市，直接返回信息
                $citylist[] = [
                    'code' => $province,
                    'city' => $municipality[$province]
                ];
                \Util::echoJson('查询成功',true,$citylist);
            }
            //获取城市列表，特别注意直辖市
            $citylist = ProvinceCity::where('parent_nation_area',$province)
                ->where('enable_status',1)
                ->order('code')
                ->field('code')
                ->field('nation_area_name as city')
                ->select()
                ->toArray();
            \Util::echoJson('查询成功',true,$citylist);
        }
    }

    /**
     * 获取区县列表，用于手动评估（手动评估时，需要传入区县才能评估）
     */
    public function getArea(){
        if($this->request->isAjax()){
            $city = $this->request->param('cityCode'); //城市的code值
            if( ! $city){
                \Util::echoJson('参数不完整',false,[]);
            }
            //获取区县列表
            $arealist = ProvinceCity::where('parent_nation_area',$city)
                ->order('code')
                ->field('code')
                ->field('nation_area_name as area')
                ->select()
                ->toArray();
            \Util::echoJson('查询成功',true,$arealist);
        }
    }

    /**
     * 查询小区
     */
    public function getProjectList(){
        if($this->request->isAjax()){
            $city = $this->request->param('city');
            $name = $this->request->param('name');
            if( ! $city || ! $name){
                \Util::echoJson('参数不完整',false,[]);
            }
            //记录当前城市到缓存
            $address = Cache::get($this->mobile . '_address');
            $address['city'] = $city;
            Cache::set($this->mobile . '_address',$address,3600);
            //默认查询条目
            $selectBanNumDefault = Config::get('select_ban_num_default');
            //获取楼栋列表，模糊查询
            $city = str_replace('市','',$city);
            $buildinglist = Community::where('city',$city)
                ->where('name','LIKE',"%{$name}%")
                ->limit($selectBanNumDefault)
                ->field('code')
                ->field('name')
                ->field('area')
                ->select()
                ->toArray();
            \Util::echoJson('查询成功',true,$buildinglist);
        }
    }

    /**
     * 根据关键字查询小区列表，即业务中的按地址查询
     */
    public function getProjectListByKeyword(){
        if($this->request->isAjax()){
            $city = $this->request->param('city');
            $keyword = $this->request->param('name');
            if( ! $city || ! $keyword){
                \Util::echoJson('参数不完整',false,[]);
            }
            //记录当前城市到缓存
            $address = Cache::get($this->mobile . '_address');
            $address['city'] = $city;
            Cache::set($this->mobile . '_address',$address,3600);
            //默认查询条目
            $selectBanNumDefault = Config::get('select_ban_num_default');
            //获取楼栋列表，模糊查询
            $city = str_replace('市','',$city);
            $fang = new Fang();
            $projectList = $fang->getProjectListByKeyword($selectBanNumDefault,$city,$keyword);
            if( ! $projectList){
                \Util::echoJson('查询失败',false,[]);
            }
            $projectList = json_decode($projectList,true);
            $projectList = $projectList['itemlist'];
            //整理返回数据，与按小区名称查询返回的格式一致，方便前端处理
            $projectListNew = [];
            foreach ($projectList as $item){
                $projectListNew[] = [
                    'code' => $item['sNewCode'],
                    'name' => $item['sProjName']
                ];
            }
            \Util::echoJson('查询成功',true,$projectListNew);
        }
    }

    /**
     * 获取楼栋单元列表
     */
    public function getBanUnitList(){
        if($this->request->isAjax()){
            $city = $this->request->param('city');
            $newCode = $this->request->param('newCode');
            $projectName = $this->request->param('projectName');
            if( ! $city || ! $newCode || ! $projectName){
                \Util::echoJson('参数不完整',false,[]);
            }
            //记录当前楼盘到缓存
            $address = Cache::get($this->mobile . '_address');
            $address['projectName'] = $projectName;
            Cache::set($this->mobile . '_address',$address,3600);
            //查询楼栋单元
            $fang = new Fang();
            $city = str_replace('市','',$city);
            $banUnitList = $fang->getBanUnitList($newCode,$city);
            if( ! $banUnitList){
                \Util::echoJson('查询失败',false,[]);
            }
            $banUnitList = json_decode($banUnitList,true);
            $banUnitList = $banUnitList['itemlist'];
            \Util::echoJson('查询成功',true,$banUnitList);
        }
    }

    /**
     * 获取房间列表
     */
    public function getRoomList(){
        if($this->request->isAjax()){
            $city = $this->request->param('city');
            $newCode = $this->request->param('newCode'); //楼盘Code
            $banCode = $this->request->param('banCode'); //楼栋Code
            $unitCode = $this->request->param('unitCode'); //单元Code
            $banName = $this->request->param('banName'); //楼栋单元名称
            if( ! $city || ! $newCode || ! $banCode || ! $unitCode || ! $banName){
                \Util::echoJson('参数不完整',false,[]);
            }
            //记录当前楼栋单元到缓存
            $address = Cache::get($this->mobile . '_address');
            $address['banName'] = $banName;
            Cache::set($this->mobile . '_address',$address,3600);
            //查询房间列表
            $fang = new Fang();
            $city = str_replace('市','',$city);
            $roomList = $fang->getRoomList($newCode,$city,$banCode,$unitCode);
            if( ! $roomList){
                \Util::echoJson('查询失败',false,[]);
            }
            $roomList = json_decode($roomList,true);
            $roomList = $roomList['itemlist'];
            \Util::echoJson('查询成功',true,$roomList);
        }
    }

    /**
     * 获取房屋信息 - 此处用来获取面积
     */
    public function getRoomInfoById(){
        if($this->request->isAjax()){
            $city = $this->request->param('city');
            $newCode = $this->request->param('newCode'); //楼盘Code
            $roomId = $this->request->param('roomId'); //房间Id
            $roomNo = $this->request->param('roomNo'); //房间号
            if( ! $city || ! $newCode || ! $roomId || ! $roomNo){
                \Util::echoJson('参数不完整',false,[]);
            }
            //记录当前楼栋单元到缓存
            $address = Cache::get($this->mobile . '_address');
            $address['roomNo'] = $roomNo;
            Cache::set($this->mobile . '_address',$address,3600);
            //查询房屋信息
            $fang = new Fang();
            $city = str_replace('市','',$city);
            $roomInfo = $fang->getPriceByRoomID($newCode,$city,$roomId);
            if( ! $roomInfo){
                \Util::echoJson('查询失败',false,[]);
            }
            $roomInfo = json_decode($roomInfo,true);
            \Util::echoJson('查询成功',true,$roomInfo);
        }
    }

    /**
     * 获取朝向列表
     */
    public function getForward(){
        if($this->request->isAjax()){
            $forwardList = Config::get('forward');
            \Util::echoJson('查询成功',true,$forwardList);
        }
    }

}