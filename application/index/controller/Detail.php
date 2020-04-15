<?php
namespace app\index\controller;

use function PHPSTORM_META\type;
use think\Controller;

class Detail extends Base
{
    public function index($id)
    {
        if(!intval($id)){
            $this->error('ID不合法');
        }
        //根据id查询商品的数据
        $commodity = model('Commodity')->get(['id'=>$id]);
        if(!$commodity|| $commodity->status!=1){
            $this->error('该商品不存在');
        }else{
            //获取分类信息
            $category = model('Category')->get($commodity->category_id);
            //获取分店信息
            $stores = getStoreIdsName($commodity->store_ids);
            //获取商家数据
            $business = model("Business")->get($commodity['business_id']);
            //判断商品是否允许购买
            $time_flag = 0;
            $timeData = 0;
            //距离开够时间倒计时
            //当购买时间大于当前时间,显示距离购买时间倒计时
            if($commodity->start_time>time()){
                $time_flag = 1;
                $timeData = $commodity->start_time-time();
//                //计算有多少天 floor只保留整数
//                $d = floor($timeData/(3600*24));
//                if($d){
//                    $countdown .= $d."天";
//                }
//                $h = floor($timeData%(3600*24)/3600);
//                if($h){
//                    $countdown .= $h."小时";
//                }
//                $m = floor($timeData%(3600*24)%3600/60);
//                if($m){
//                    $countdown .= $m."分";
//                }
//                $s = floor($timeData%(3600*24)%3600%60);
//                if($s){
//                    $countdown .= $m."秒";
//                }
            }
            $buy_flag=false;
            if($commodity->start_time<time()&&$commodity->end_time>time()){
                $buy_flag = true;
            }
            $this->assign([
                'title'=>$commodity->name,
                'categoryname'=>$category->name,
                'stores'=>$stores,
                'commodity'=>$commodity,
                'time_flag'=>$time_flag,
                'timecount'=>$timeData,
                'buy_flag'=>$buy_flag,
                'mapstr' =>$stores[0]['xpoint'].','.$stores[0]['ypoint'],
                'business'=>$business
            ]);
            return $this->fetch();
        }
    }

}
