<?php
/**
 * Created by PhpStorm.
 * User: zhangronghua
 * Date: 2019/11/12
 * Time: 5:09 PM
 */
namespace app\common\validate;
use think\validate;

class Business extends Validate
{
    protected $rule = [
        'name' => 'require|max:25',
        'email' => 'require|email',
        'logo' => 'require',
        'licence_logo' => 'require',
        'description' => 'require',
        'city_id' => 'require',
        'bank_info' => 'require',
        'bank_name' => 'require',
        'bank_user' => 'require',
        'corporation' => 'require',
        'corporation_tel' => 'require|mobile',

        'tel' => 'require|mobile',
        'contact' => 'require|max:20',
        'category_id' => 'require',
        'address' => 'require|max:255',
        'open_time' => 'require',

        'username' => 'require|alphaDash',  //用户名格式只能是字母、数组、—或_
        'password' => 'require|confirm',
        'password_confirm' => 'require',

        'status' => 'require|integer'
    ];
    protected $scene = [
        'business' => ['name','city_id','logo','license_logo','description','bank_info','bank_name','bank_user','corporation','corporation_tel','email'],
        'store' => ['name','logo','tel','contact','city_id','category_id','address','open_time'],
        'account' => ['username','password'],
        'status' => ['status','id','email'],
    ];
}