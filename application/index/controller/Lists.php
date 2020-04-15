<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Lists extends Base
{
    public function index()
    {
        //用与排查询分类下所有商品
        $selectQuery = [];
        //用与按对应排序方式排序所有商品
        $orderQuery = [];

        //存储一级分类id
        $firstCategoryIds=[];
        //获取一级分类栏木
        $firstCategories = model('Category')->getFirstCategory(0,true);
        foreach ($firstCategories as $firstCategory){
            $firstCategoryIds[] = $firstCategory->id;
        }
        //获取分类id
        $id = input('id',0,'intval');

        //控制选中全部子类
        $secondAllFlag = 0;

        //$id =0 一级分类 二级分类
        //判断属于分类级别
        if(in_array($id,$firstCategoryIds)){//一级分类
            //todo 一级分类 id为一级分类
            $secondCategoryParentID = $id;
            //搜索category_id为$firstCategoryParentID的商品
            $selectQuery[] = ['category_id','=',$id];
            //控制选中全部子类
            $secondAllFlag = 1;
        }else if($id){//二级分类
            //todo 二级分类
            $secondCategory = model('Category')->get($id);
            if(!$secondCategory || $secondCategory->status!=1){
                $this->error('数据不合法');
            }
            //获取其二级分类对应一级分类id
            $secondCategoryParentID = $secondCategory->parent_id;
            //搜索category_path存在$id的商品
            $selectQuery[] = ['', 'EXP', Db::raw("FIND_IN_SET(" . $id . ",category_path)")];
        }else{ //0
            $secondCategoryParentID = 0;
            $secondAllFlag = 1;
        }

        //获取默认城市
        if(!empty($this->default_city)){
            $selectQuery[] = ['city_id','=',$this->default_city->id];
        }

        //存储一级分类对应的二级分类栏目
        $secondCategories=[];
        if(!empty($secondCategoryParentID)){
            $secondCategories = model('Category')->getFirstCategory($secondCategoryParentID,true);
        }

        //排序数据获取的逻辑
        $order_sales = input('get.order_sales','');
        $order_price = input('get.order_price','');
        $order_time = input('get.order_time','');
        if(!empty($order_sales)){
            $orderflag= 'order_sales';
            $orderQuery['order_sales'] =$order_sales;
        }else if(!empty($order_price)){
            $orderflag= 'order_price';
            $orderQuery['order_price'] =$order_price;
        }else if(!empty($order_time)){
            $orderflag= 'order_time';
            $orderQuery['order_time'] =$order_time;
        }else{
            $orderflag= '';
        }
        //根据条件来查询列表
        $commodities = model('Commodity')->getCommodityByConditions($selectQuery,$orderQuery);

        $this->assign([
            'firstCategories'=>$firstCategories,
            'secondCategories'=>$secondCategories,
            'id'=>$id,
            'secondCategoryParentID'=>$secondCategoryParentID,
            'orderflag'=>$orderflag,
            'commodities' => $commodities,
            'secondAll_flag'=>$secondAllFlag,
        ]);
        return $this->fetch();
    }

}
