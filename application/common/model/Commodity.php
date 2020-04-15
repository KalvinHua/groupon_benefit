<?php

namespace app\common\model;

use think\Model;
use think\Db;

class Commodity extends BaseModel
{
    protected $autoWriteTimestamp = true;


    /*****商户控制台*****/
    /**
     * 获取对应商户下的所有状态为0或1的商品信息
     * @param $business_id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\exception\DbException
     */
    public function getCommodityByBisID($business_id){
        $order = ['id' => 'desc'];
        $map = [['business_id','=',$business_id],['status','>=', 0]];
        $result = $this->where($map)
            ->order($order)
            ->paginate();
        return $result;
    }
    /**
     * 获取所有所有对应商户下的所有状态为1的商品信息
     * @param int $business_id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\exception\DbException
     */
    public function getNormalCommodityByBisId($business_id){
        $map = [['business_id','=',$business_id],['status','=', 1]];
        $order = ['id' => 'desc'];
        $result = $this->where($map)
            ->order($order)
            ->paginate();
        return $result;
    }


    /*****主控制台*****/
    /*****
     * @param array $map
     * @param int $status
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function getNormalCommodities($map=[]){
        $map[]=['status','=',1];
        $order = ['id'=>'desc'];
        $result = $this->where($map)
            ->order($order)
            ->paginate();
        return $result;
    }
    /**
     * 获取所有状态为0或1的商品信息
     * @param $map
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\exception\DbException
     */
    public function getAllCommodities($map=[]){
        $order = ['id' => 'desc'];
        $result = $this->where($map)
            ->order($order)
            ->paginate();
        return $result;
    }

    /***
     * 根据分类以及城市来获取数据
     * @param $categoryId 分类id
     * @param $cityId     城市
     * @param int $limit  条数
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function getNormalCommodityByCategoryCityId($categoryId,$cityId,$limit=10){
        $map = [
          ['end_time','>',time()],
          ['category_id','=',$categoryId],
          ['city_id','=',$cityId],
          ['status','=',1],
        ];

        $order = [
          'listorder' => 'desc',
          'id' => 'desc',
        ];

        $result = $this->where($map)
            ->order($order);
        if($limit){
            $result = $result->limit($limit);
        }
        return $result->select();
    }

    public function getCommodityByConditions($selectQuery=[],$orderQuery=[])
    {
        if(!empty($orderQuery['order_sales'])){
            $order['buy_count'] = 'desc';
        }
        if(!empty($orderQuery['order_price'])){
            $order['current_price'] = 'desc';
        }
        if(!empty($orderQuery['order_time'])){
            $order['create_time'] = 'asc';
        }
        $order['id'] = 'desc';

//        //find_in_set用法
//        $where[]=['','exp',Db::raw("FIND_IN_SET($categoryId(id传参), product_id(要查询的字段名))")];
//        //find_in_set(11,'se_category_id');
//        //判断分类id/分类path是否存在
//        if(!empty($selectQuery[0])) {
//            if ($selectQuery[0][0] == 'category_path') {
//                $sedCategoryId = $selectQuery[0][2];
//                $selectQuery[0] = ['', 'EXP', Db::raw("FIND_IN_SET(" . $sedCategoryId . ",category_path)")];
//            }
//        }

        $selectQuery[] = ['status','=',1];
        //linux crontab 检测时间是否合理  不合理将status = 2
        $selectQuery[] = ['end_time','>=',time()];

        $result = $this->where($selectQuery)
            ->order($order)
            ->paginate(5);

//        dump($this->getLastSql());exit;
        return $result;
    }
}
