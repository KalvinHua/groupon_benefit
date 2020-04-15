<?php
/**
 * Created by PhpStorm.
 * User: zhangronghua
 * Date: 2019/11/12
 * Time: 5:09 PM
 */
namespace app\common\validate;
use think\validate;

class BusinessStore extends Validate
{
    protected $rule = [
        'id' => 'require',
        'name' => 'require',  //商铺名
        'status' => 'require|integer',
    ];
    protected $scene = [
        'status' => ['status','id']
    ];
}