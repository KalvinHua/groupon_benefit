<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/***
 * 状态显示标签
 * @param $status
 * @return string
 */
function status($status){
    if($status == 1){
        $status_label="<span class='label label-success'>正常</span>";
    }else if($status == 0){
        $status_label="<span class='label label-warning'>待审查</span>";
    }else if($status == 2){
        $status_label="<span class='label label-danger'>不通过</span>";
    }
    else{
        $status_label="<span class='label label-default'>已删除</span>";
    }
    return $status_label;
}

/***
 * 订单状态显示标签
 * @param $status
 * @return string
 */
function orderStatus($status){
    if($status == 1){
        $status_label="<span class='label label-success'>正常</span>";
    }else if($status == 0){
        $status_label="<span class='label label-danger'>已关闭</span>";
    }
    else{
        $status_label="<span class='label label-default'>已删除</span>";
    }
    return $status_label;
}

/***
 * 支付状态显示标签
 * @param $status
 * @return string
 */
function payStatus($status){
    if($status == 1){
        $status_label="<span class='label label-success'>微信支付成功</span>";
    }else if($status == 0){
        $status_label="<span class='label label-warning'>未支付</span>";
    }else if($status == 2){
        $status_label="<span class='label label-success'>支付宝支付成功</span>";
    }else if($status == -1){
        $status_label="<span class='label label-success'>已过期</span>";
    }
    return $status_label;
}

function pagination($obj){
    if(!$obj){
        return '';
    }
    $params = request()->param();
    return $obj->appends($params)->render();
}

/**
 * 抛送请求
 * @param $url
 * @param $type //0 get 1 post
 * @param $data //数据
 * @return mixed
 */
function doCurl($url,$type=0,$data=[]){
    $ch = curl_init();  //初始化
    //设置选项
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);   //如果成功只返回结果
    curl_setopt($ch,CURLOPT_HEADER,0);  //0输出header头

    if($type == 1){
        //post
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    }

    //执行并获取内容
    $output = curl_exec($ch);

    //释放cur句柄
    curl_close($ch);
    return $output;
}

/****
 * 商户入驻申请的文案
 * @param $status
 * @return string
 */
function bisRegister($status){
    if($status==1){
        $str = '入驻申请成功';
    }else if($status == 0){
        $str = '待审核中，审核后平台方会发送邮件通知';
    }else if($status == 2){
        $str = '非常抱歉，提交的资料不符合条件,请重新提交';
    }else{
        $str = '该申请已被提交';
    }
    return $str;
}

/***
 * 商户申请详情页获取二级城市
 * @param $path city_path
 * @return mixed|string
 */
function getSeCityName($path){
    if(empty($path)){
        return '';
    }
    if(preg_match('/,/',$path)){
        $cityPath = explode(',',$path);
        $cityID = $cityPath[1];
    }else{
        $cityID = $path;
    }

    $city = model('City')->get($cityID);
    return $city->name;
}

/***
 * 商户申请详情页获取二级城市
 * @param $path city_path
 * @return mixed|string
 */
function getCityName($path){
    $str = '';
    if(empty($path)){
        return '';
    }
    if(preg_match('/,/',$path)){
        $cityPath = explode(',',$path);
        $firstCityID = $cityPath[0];
        $secondCityID = $cityPath[1];
        $city = model('City')->get($firstCityID);
        $str .= $city->name;
        $city = model('City')->get($secondCityID);
        $str .= $city->name;
    }else{
        $city = model('City')->get($path);
        $str .= $city->name;
    }

    $str = '<span>'.$str.'</span>';
    return $str;
}

/***
 * 商户申请详情页获取二级分类
 * @param $path category_path
 * @return mixed|string
 */
function getSeCategoryName($path){
    if(empty($path)){
        return '';
    }
    if(preg_match('/,/',$path)) {
        $categoryPath = explode(',', $path);
        if (strstr($categoryPath[1],"|")==true) {
            $categoryIDs = explode('|', $categoryPath[1]);
        } else {
            $categoryIDs = $categoryPath[1];
        }
        $categories = model('Category')->all($categoryIDs);
        return $categories;
    }
}

/***
 * 团购商品所属分店数据获取 解构store_ids
 * @param $path store_ids
 * @return mixed|string
 */
function getStoreIdsName($path){
    if(empty($path)){
        return '';
    }
    if(preg_match('/,/',$path)) {
        $store_ids = explode(',', $path);
        $result = model('BusinessStore')->all($store_ids);
    }else{
        $result = model('BusinessStore')->all($path);
    }
    return $result;
}

/***
 * 计算共多少店使用
 * @param $ids
 * @return int
 */
function countStores($ids){
    if(!$ids){
        return 1;
    }
    if(preg_match('/,/',$ids)){
        $arr = explode(',',$ids);
        return count($arr);
    }else{
        return 1;
    }
}

/***
 * 设置订单号
 */
function setrOrderSn(){
    //list(v1,v2)=array[0=>v1,1=>v2];
    //microtime()返回当前 Unix 时间戳的微秒数
    list($t1,$t2) = explode(' ',microtime());
    $t3 = explode('.',$t1*10000);
    return $t2.$t3[0].(rand(10000,99999));
}

