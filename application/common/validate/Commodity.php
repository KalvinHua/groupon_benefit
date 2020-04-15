<?php
/**
 * Created by PhpStorm.
 * User: zhangronghua
 * Date: 2019/11/17
 * Time: 12:26 AM
 */

namespace app\common\validate;


use think\Validate;

class Commodity extends Validate
{
//    protected $regex = [
//        'money' => '/(^[1-9][0-9]*|)/'
//    ];
    protected $rule = [
        'id' => "require|number",
        'business_id' => "require|number",
        'name' => 'require|max:100',
        'category_id'=>'require',
        'city_id'=>'require',
        'image'=>'require',
        'start_time'=>'require',
        'end_time'=>'require',
        'coupons_start_time'=>'require',
        'coupons_end_time'=>'require',
        'origin_price'=>'require|float',
        'current_price'=>'require|float',
        'total_count'=>'require|number|max:11',
        'balance_price' => 'require|float',
        'buy_count'=>'require|float'
    ];
    protected $scene=[
        'add' => ['name','category_id','city_id','image','start_time','end_time','coupons_start_time','coupons_end_time','origin_price','current_price','total_count'],
        'status' => ['status','id']
    ];
}