<?php
namespace app\index\controller;

use app\common\model\Commodity;
use think\Controller;
use think\Exception;
use think\facade\Env;
require_once Env::get('root_path').'extend/alipay/pagepay/service/AlipayTradeService.php';
require_once Env::get('root_path').'extend/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';

class Pay extends Base
{
    /**
     * 提交订单
     * @return mixed
     * @throws \Exception
     */
    public function index(){
        //判断是否登陆
        if(!$this->getLoginUser()){
            $this->error('请登录','user/login');
        }
        $orderId = input('get.id',0,'intval');
        //调用微信支付的统一下单接口，微信后台返回链接参数code_url，再根据code_url生成二维码
        if(empty($orderId)){
            $this->error('输入参数不合法');
        }
        //获取订单数据
        $orderData = model('Order')->get($orderId);
        //判断订单状态
        if( empty($orderData) || $orderData->status != 1 || $orderData->pay_status != 0 ){
            $this->error('无法进行该操作');
        }
        //严格判断订单是否为用户本人
        if($orderData->user_name != $this->getLoginUser()->username){
            $this->error('不是订单本人操作');
        }
        //商品数据获取
        $commodityData = model('Commodity')->get($orderData->commodity_id);

        $postData = [
          'out_trade_no' => $orderData['out_trade_no'],
          'total_price' => $orderData['total_price'],
          'name'=> $commodityData['name'],
          'description'=> strip_tags($commodityData['description']), //strip_tags去标签
        ];

        //调用支付宝页面
        $this->aliPay($postData);
    }

    /***
     * 支付宝支付 提交数据跳转到支付宝支付页面
     * @param $postData
     * @return bool|mixed|\SimpleXMLElement|string|\提交表单HTML文本
     * @throws \Exception
     */
    public function aliPay($postData)
    {

        /**
         * 调用支付宝接口。
         */
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = trim($postData['out_trade_no']);

        //订单名称，必填
        $subject = trim($postData['name']);

        //付款金额，必填
        $total_amount = trim($postData['total_price']);

        //商品描述，可空
        $body = trim($postData['description']);

        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $aop = new \AlipayTradeService(config('alipay.'));

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $response = $aop->pagePay($payRequestBuilder,config('alipay.return_url'),config('alipay.notify_url'));
        return $response;
    }

//    /***
//     * 支付宝支付结果异步通知
//     * @throws \think\Exception
//     * @throws \think\exception\PDOException
//     */
//    public function notify()
//    {
//        $params=$_POST;
//        $alipaySevice = new \AlipayTradeService(config('alipay.'));
//        $alipaySevice->writeLog(var_export($_POST, true));
//        $result = $alipaySevice->check($params);
//
//        /* 实际验证过程建议商户添加以下校验。
//        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
//        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
//        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
//        4、验证app_id是否为该商户本身。
//        */
//        if ($result) {//验证成功
//            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//            //请在这里加上商户的业务逻辑程序代
//
//            //——请根据您的业务逻辑来编写程序
//            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
//
//            //商户订单号
//            $out_trade_no = $params['out_trade_no'];
//            //支付宝交易号
//            $trade_no = $params['trade_no'];
//            //交易状态
//            $trade_status = $params['trade_status'];
//            //交易金额
//            $trade_amount = $params['total_fee'];
//
//            $orderResult = model('Order')->get(['out_trade_no' => $out_trade_no]);
//
//
//            if ($params['trade_status'] == 'TRADE_FINISHED') {
//
//                //判断该笔订单是否在商户网站中已经做过处理
//                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
//                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
//                //如果有做过处理，不执行商户的业务程序
//
////                if (empty($orderResult) || $orderResult->pay_status != 0 || $orderResult->status != 1) {
////                    echo "fail";
////                }
////
////                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
////                if ($orderResult->total_price != $trade_amount) {
////                    echo "fail";
////                }
//
//                //注意：
//                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
//            } else if ($params['trade_status'] == 'TRADE_SUCCESS') {
//                //判断该笔订单是否在商户网站中已经做过处理
//                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
//                //如果有做过处理，不执行商户的业务程序
//                //注意：
//                //付款完成后，支付宝系统发送该交易状态通知
////                if (empty($orderResult) || $orderResult->pay_status != 0 || $orderResult->status != 1) {
////                    echo "fail";
////                }
////
////                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
////                if ($orderResult->total_price != $trade_amount) {
////                    echo "fail";
////                }
//            }
//
//            //执行数据库操作
//            //更新订单数据
//            model('Order')
//                ->where(['out_trade_no'=>$out_trade_no,])
//                ->update([
//                    'pay_status' => 2, //2为支付宝支付
//                    'transaction_id' => $trade_no,
//                    'pay_amount' => $trade_amount,
//                ]);
//
//            //获取商品数据
//            $commodityResult = model('Commodity')->get($orderResult['commodity_id']);
//            //操作已购商品数量
//            $buyCount = $commodityResult->buy_count + $orderResult->commodity_count;
//            //更改商品已购数量
//            model('Commodity')->save(['buy_count'=>$buyCount],['id'=>$orderResult['commodity_id']]);
//
//
//            echo "success";    //请不要修改或删除
//        } else {
//            //验证失败
//            echo "fail";
//
//        }
//    }

    /****
     * 支付宝支付同步通知
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function returnfy(){
        $params=$_GET;
        $alipaySevice = new \AlipayTradeService(config('alipay.'));
//        dump($params);exit;
        $result = $alipaySevice->check($params);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

            //商户订单号
            $out_trade_no = htmlspecialchars($_GET['out_trade_no']);

            //支付宝交易号
            $trade_no = htmlspecialchars($_GET['trade_no']);

            //交易时间
            $trade_time = strtotime($_GET['timestamp']);

            //实付金额
            $trade_amount = $_GET['total_amount'];

            //将订单表中的支付状态更改为已支付，并将支付宝交易号写入数据表中

            //获取订单数据
            $orderResult = model('Order')->get(['out_trade_no'=>$out_trade_no]);

            //获取商品数据
            $commodityResult = model('Commodity')->get($orderResult['commodity_id']);

            //判断订单支付状态已更改 若更改了则无需操作
            //////////////////
            if($orderResult->pay_status != 2){
                //更新订单数据
                model('Order')
                    ->where(['out_trade_no'=>$out_trade_no,])
                    ->update([
                        'pay_status' => 2, //2为支付宝支付
                        'transaction_id' => $trade_no,
                        'pay_amount' => $trade_amount,
                    ]);
                //操作已购商品数量
                $buyCount = $commodityResult->buy_count + $orderResult->commodity_count;
                //更改商品已购数量
                model('Commodity')->save(['buy_count'=>$buyCount],['id'=>$orderResult['commodity_id']]);
            }

            $this->success('支付成功，跳转中...','index/index');

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } else {
            //验证失败
            echo "验证失败";
        }
    }

}
