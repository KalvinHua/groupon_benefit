<?php
/**
 * Created by PhpStorm.
 * User: zhangronghua
 * Date: 2019/11/29
 * Time: 1:56 AM
 */

namespace app\index\controller;
use think\facade\Env;
require_once Env::get('root_path').'extend/alipay/pagepay/service/AlipayTradeService.php';
require_once Env::get('root_path').'extend/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';

use think\Controller;

class PayNotify extends Controller
{
    /***
     * 支付宝支付结果异步通知
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function notify()
    {
        $params=$_POST;
        $alipaySevice = new \AlipayTradeService(config('alipay.'));
        $alipaySevice->writeLog(var_export($_POST, true));
        $result = $alipaySevice->check($params);


        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if ($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代

            //——请根据您的业务逻辑来编写程序
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            //商户订单号
            $out_trade_no = $params['out_trade_no'];
            //支付宝交易号
            $trade_no = $params['trade_no'];
            //交易状态
            $trade_status = $params['trade_status'];
            //交易金额
            $trade_amount = $params['total_fee'];

            $orderResult = model('Order')->get(['out_trade_no' => $out_trade_no]);


            if ($params['trade_status'] == 'TRADE_FINISHED') {

                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序

//                if (empty($orderResult) || $orderResult->pay_status != 0 || $orderResult->status != 1) {
//                    echo "fail";
//                }
//
//                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
//                if ($orderResult->total_price != $trade_amount) {
//                    echo "fail";
//                }

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            } else if ($params['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
//                if (empty($orderResult) || $orderResult->pay_status != 0 || $orderResult->status != 1) {
//                    echo "fail";
//                }
//
//                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
//                if ($orderResult->total_price != $trade_amount) {
//                    echo "fail";
//                }
            }

            //执行数据库操作
            //更新订单数据
            model('Order')
                ->where(['out_trade_no'=>$out_trade_no,])
                ->update([
                    'pay_status' => 2, //2为支付宝支付
                    'transaction_id' => $trade_no,
                    'pay_amount' => $trade_amount,
                ]);

            //获取商品数据
            $commodityResult = model('Commodity')->get($orderResult['commodity_id']);
            //操作已购商品数量
            $buyCount = $commodityResult->buy_count + $orderResult->commodity_count;
            //更改商品已购数量
            model('Commodity')->save(['buy_count'=>$buyCount],['id'=>$orderResult['commodity_id']]);

            echo "success";    //请不要修改或删除
        } else {
            //验证失败
            echo "fail";

        }
    }
}