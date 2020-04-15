<?php
/**
 * Created by PhpStorm.
 * User: zhangronghua
 * Date: 2019/11/27
 * Time: 2:18 PM
 */

namespace app\admin\controller;


use think\Controller;

class Order extends Controller
{
    /***
     * 返回所有状态为正常订单列表页
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index(){
        $orderData = model('Order')->where('status','neq',-1)->select();
        $this->assign(['orderData'=>$orderData]);
        return $this->fetch();
    }

    /***
     * 返回所有状态为删除订单列表页
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function deleteindex(){
        $orderData = model('Order')->where('status','eq',-1)->select();
        $this->assign(['orderData'=>$orderData]);
        return $this->fetch();
    }

    /****
     * 更改订单状态 0为关闭
     */
    public function status(){
        $id = input('get.id',0,'intval');
        if(!$id>0){
            $this->error('参数不合法');
        }
        $status = input('get.status');
        $res = model('Featured')->save(['status' => $status], ['id' => $id]);
        if ($res) {
            $this->success('状态更新成功');
        } else {
            $this->success('状态更新失败');
        }
    }
}