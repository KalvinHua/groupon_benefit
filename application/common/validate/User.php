<?php
/**
 * Created by PhpStorm.
 * User: zhangronghua
 * Date: 2019/11/19
 * Time: 2:56 AM
 */

namespace app\common\validate;


use think\Validate;

class User extends Validate
{
    protected $rule = [
        'id' => 'require|number',
        'username' => 'require|alphaDash',  //用户名格式只能是字母、数字、—或_
        'password|密码' => 'require|max:16|min:6',
        'repassword' => 'require|comfirm:password',
        'email' => 'require|email',
        'status' => 'require|integer',
        'captcha|验证码'=>'require|captcha'
    ];
    protected $scene = [
        'register' => ['username','password','email','captcha'],
        'login' => ['username','password','captcha'],
        'status' => ['id','status'],
    ];
    protected $message = [
        'username.require'=>'名字不能为空',
        'repassword.confirm'=>'第一次输入密码与第二次输入密码不同',
        'password.max'=>'密码长度为6-20',
        'password.min'=>'密码长度为6-20',
        'email.email'=> '邮箱格式不正确',
    ];
}