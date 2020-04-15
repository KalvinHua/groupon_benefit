<?php

namespace app\common\model;

use think\Collection;
use think\Model;

class City extends Model
{
    protected $autoWriteTimestamp = true;
    //
    /***
     * 添加城市
     * @param $data
     */
    public function addCity($data)
    {
        $data['status'] = 1;
        $this->save($data);
    }

    /**
     * 返回一级(状态为正常或正常和禁用)分类
     * @param boolean $normal 分类状态 true:state=1 false status!=-1
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function getFirstCity($parent_id=0 ,$normal=false)
    {
//        $where[0] = $normal?['status','1']:['status','<>','-1'];  // status: 1 || (0&&1)
//        $where[1] =['parent_id',1];
        $map = [
            $normal?['status','=','1']:['status','neq','-1'],
            ['parent_id','=',$parent_id]
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc',
        ];
        $result=$this->where($map)
            ->order($order)
            ->paginate();
//        //输出最终语句
//        echo $this->getLastSql();

        return $result;
    }
}
