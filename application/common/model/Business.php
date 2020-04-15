<?php

namespace app\common\model;

use think\Model;

class Business extends BaseModel
{
    //
    protected $autoWriteTimestamp = true;
//    public function add($data){
//        $data['status'] = 0;
//        $this->save($data);
//        return $this->id;
//    }
    /**
     * @param int $status
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\exception\DbException
     */
    public function getBusinessByStatus($status=0){
        $order = ['id' => 'desc'];
        $map = [['status','=', $status]];
        $result = $this->where($map)
            ->order($order)
            ->paginate();
        return $result;
    }
}
