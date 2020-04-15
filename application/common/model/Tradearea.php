<?php

namespace app\common\model;

use think\Collection;
use think\Model;

class Tradearea extends Model
{
    //
    /**
     * 添加商圈数据
     * @param $data
     */
    public function addTradearea($data)
    {
        $data['status'] = 1;
        $this->save($data);
    }

    /**
     * 返回一级(状态为正常或正常和禁用)分类
     * @param $parent_id $normal   分类状态
     * @return Collection
     * @throws \think\exception\DbException
     */
    public function getFirstTradearea($parent_id=0 ,$normal=false)
    {
//        $where[0] = $normal?['status','1']:['status','<>','-1'];  // status: 1 || (0&&1)
//        $where[1] =['parent_id',1];
        $map = [
            $normal?['a.status','=','1']:['a.status','neq','-1'],
            ['a.parent_id','=',$parent_id]
        ];
        $order = [
            'a.listorder' => 'desc',
            'a.id' => 'desc',
        ];
        $result=$this->alias('a')
            ->join('City b','a.city_id = b.id')
            ->field('a.*,b.name as city_name')
            ->where($map)
            ->order($order)
            ->paginate();
//        //输出最终语句
//        echo $this->getLastSql();

        return $result;
    }
}
