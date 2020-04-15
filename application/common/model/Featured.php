<?php

namespace app\common\model;

use think\Model;

class Featured extends BaseModel
{
    protected $autoWriteTimestamp = true;

    /**
     * 根据类型获取列表数据
     * @param array $map 0,1
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function getFeaturedsBytype($map=[]){
        $map[] = ['status','neq',-1];
        $order = ['id'=>'desc'];

        $result = $this->where($map)
            ->order($order)
            ->paginate();
        return $result;
    }
}
