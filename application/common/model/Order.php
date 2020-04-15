<?php

namespace app\common\model;

use think\Model;

class Order extends BaseModel
{
    protected $autoWriteTimestamp = true;

    /***
     * 添加订单数据
     * @param $data
     * @return mixed
     */
    public function add($data){
//        if(!is_array($data)){
//            exception('传入的数据不是数组');
//        }
        $data['status']=1;
//        $this->allowField(true)->save($data);
        $this->save($data);
        return $this->id;
    }
}
