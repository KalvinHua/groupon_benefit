<?php
namespace app\index\controller;

use think\Controller;

class Order extends Base
{
    /**
     * 确认订单 提交订单号 存储订单数据跳转到支付宝支付页面
     * @return mixed
     */
    public function index(){
//        dump(input('get.'));
        //判断用户是否登陆
        if(!$this->getLoginUser()){
            $this->error('购买请登录','user/login');
        }

        //获取商品id
        $id = input('get.id',0,'intval');
        if(!$id){
            $this->error('参数不合法');
        }

        //获取购买数量
        $totalCount = input('get.total_count',0,'intval');
        if(!$totalCount){
            $this->error('参数不合法');
        }

        //获取总金额
        $totalPrice = input('get.total_price',0,'intval');
        if(!$totalPrice){
            $this->error('参数不合法');
        }

        //获取商品数据
        $commodity = model('commodity')->find($id);
        //判断是否存在此商品数据，或者商品是否在售
        if(!$commodity||$commodity->status!=1){
            $this->error('商品不存在或已被下架');
        }

        //判断订单生成方式是否合法
        if(empty($_SERVER['HTTP_REFERER'])){
            $this->error('请求不合法');
        }


        $orderSn = setrOrderSn();
        //组装入库数据
        $data= [
            'out_trade_no' => $orderSn,
            'user_id' => $this->user->id,
            'user_name' => $this->user->username,
            'commodity_id' => $id,
            'commodity_count' => $totalCount,
            'total_price' => $totalPrice,
            'referer' => $_SERVER['HTTP_REFERER'],
        ];

        //判断订单是否存储成功
        try{
            $orderId = model('Order')->add($data);
        }catch (\Exception $e){
            $this->error('订单处理失败');
        }

        //跳转页面
        $this->redirect(url('pay/index',['id'=>$orderId]));
    }

    /**
     * 确认订单页面
     * @return mixed
     */
    public function confirm()
    {
        //判断用户是否登陆
        if(!$this->getLoginUser()){
            $this->error('购买请登录','user/login');
        }
        //获取商品id
        $id = input('get.id',0,'intval');
        if(!$id){
            $this->error('参数不合法');
        }
        //获取购买数量
        $count = input('get.count',1,'intval');
        if(!$count){
            $this->error('参数不合法');
        }

        //获取商品数据
        $commodity = model('commodity')->find($id);
        //判断是否存在此商品数据，或者商品是否在售
        if(!$commodity||$commodity->status!=1){
            $this->error('商品不存在或已被下架');
        }
        //将商品数据转换为数组
//        $commodity = $commodity->toArray();

        $this->assign([
            'title'=>'提交订单',//页面标题
            'controller'=>'pay',//当前页面调用pay.css
            'commodity'=>$commodity,
            'count'=>$count
            ]);
        return $this->fetch();
    }

}
