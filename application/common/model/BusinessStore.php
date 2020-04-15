<?php

namespace app\common\model;

use think\Model;

class BusinessStore extends BaseModel
{

    protected $autoWriteTimestamp = true;

//    public function add($data){
//        $data['status'] = 0;
//        $this->save($data);
//        return $this->id;
//    }
    /**
     * 获取所有状态为0或1的店铺信息
     * @param $business_id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\exception\DbException
     */
    public function getStoreByStatus($business_id){
        $order = ['id' => 'desc'];
        $map = [['business_id','=',$business_id],['status','>=', 0]];
        $result = $this->where($map)
            ->order($order)
            ->paginate();
        return $result;
    }
    /**
     * 获取所有状态为1的店铺信息
     * @param int $business_id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\exception\DbException
     */
    public function getNormalStoreByBisId($business_id){
        $map = [['business_id','=',$business_id],['status','=', 1]];
        $order = ['id' => 'desc'];
        $result = $this->where($map)
            ->order($order)
            ->paginate();
        return $result;
    }
}
