<?php
/**
 * Created by PhpStorm.
 * User: zhangronghua
 * Date: 2019/11/12
 * Time: 5:09 PM
 */
namespace app\common\validate;
use think\validate;

class BusinessAccount extends Validate
{
    protected $rule = [
        'username' => 'require|alphaDash',  //用户名格式只能是字母、数组、—或_
        'password' => 'require',
        'status' => 'require|integer',
        'captcha|验证码'=>'require|captcha'
        ];
    protected $scene = [
        'account' => ['username','password'],
        'status' => ['status','id']
    ];
}