<?php
/**
 * Created by PhpStorm.
 * User: zhangronghua
 * Date: 2019/11/17
 * Time: 12:26 AM
 */

namespace app\common\validate;


use think\Validate;

class Featured extends Validate
{
//    protected $regex = [
//        'money' => '/(^[1-9][0-9]*|)/'
//    ];
    protected $rule = [
        'id' => "require|number",
        'title' => "require",
        'image'=>"require",
        'type'=>"require|in:0,1",
        'url'=>'require',
        'description'=>'require'
    ];
    protected $scene = [
        'add'=>['title','image','type','url','description'],
        'status'=>['status','id']
    ];
}