<?php
namespace app\index\controller;

use think\Controller;

class Index extends Base
{
    public function index()
    {
        //推荐位信息获取（广告图资源）
        $featureds = model('Featured')->where(['status'=>1])->select();
        //搜索科技商品
        $commodities=model('Commodity')->getNormalCommodityByCategoryCityId(6,$this->default_city['id']);
        $technologies=model('Category')->getRecommendCategoriesByParentId(6,4);
        $this->assign([
            'featureds'=>$featureds,
            'commodities'=>$commodities,
            'technologies'=>$technologies,
            ]);
        return $this->fetch();
    }

}
