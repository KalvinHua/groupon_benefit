<?php

namespace app\common\model;

use think\Model;

class User extends BaseModel
{
    protected $autoWriteTimestamp = true;

    /****
     * 添加商品数据
     * @param array $data
     * @return bool|mixed
     * @throws \Exception
     */
    public function add($data=[]){
        //判断是否为数组
        if(!is_array($data)){
            exception('传入的数据不是数组');
        }
        $data['status']=1;
        $result = $this->allowField(true)->save($data);
//        return $this->id;
        return $result;
    }
}
