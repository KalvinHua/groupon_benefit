<?php
namespace app\common\model;

use think\Collection;
use think\Model;

class Category extends Model
{
    protected $autoWriteTimestamp = true;
    public function addCategory($data)
    {
        $data['status'] = 1;
        $this->save($data);
    }

    /**
     * 返回一级(状态为正常或正常和禁用)分类
     * @param boolean $normal 分类状态
     * @param int $parent_id 父id
     * @return Collection
     * @throws \think\exception\DbException
     */
    public function getFirstCategory($parent_id=0 ,$normal=false)
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

    /***
     * 获取以及分类 按listorder排序，以及输出limit条数据
     * @param int $parent_id
     * @param int $limit
     * @return array|\PDOStatement|string|Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRecommendCategoriesByParentId($parent_id=0,$limit=5){
        $map = [
            ['parent_id' , '=' , $parent_id],
            ['status' , '=' , 1],
        ];
        $order = [
          'listorder' => 'desc',
            'id' => 'desc', //如果listorder相同根据id倒叙
        ];
        $result = $this->where($map)->order($order);
        if($limit){
            $result=$result->limit($limit);
        }
        return $result->select();
    }

    /****
     * 根据一级分列id获取二级分类
     * @param array $ids
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNormalCategoriesByParentId($ids=[]){
        $map[] = ['parent_id' ,'in', implode(',',$ids)]; //按逗号分割
        $map[] = ['status','=',1];

        $order = [
            'listorder' => 'desc',
            'id' => 'desc', //如果listorder相同根据id倒叙
        ];
        $result = $this->where($map)
            ->order($order)
            ->select();
        return $result;
    }
}