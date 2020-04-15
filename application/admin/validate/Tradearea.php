<?php
namespace app\admin\validate;

use think\Validate;

class Tradearea extends Validate
{
    /**
     * 定义正则表达式
     * @var regex
     */
    protected $regex = [
        'positive_integer' => '/^[1-9][0-9]*$/',  //正整数
        'natural_integer' => '/^[0-9]*$/',        //自然数
        'chi_word' => '/^[\x{4e00}-\x{9fa5}]+$/u',     //中文名
        'eng_word' => '/^([A-Za-z]+).([A-Za-z]+)$/',    //英文名
    ];
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require|regex:positive_integer',
        'city_id' => 'require|regex:positive_integer',
        'name' => 'require|max:20|regex:chi_word',
        'parent_id' => 'number',
        'status' => 'number|in:-1,0,1',
        'listorder' => 'require|regex:natural_integer'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'id.regex' => 'id只能为正整数',
        'city_id.regex' => 'id只能为正整数',
        'name.require' => '商圈名称不能为空',
        'name.max:20' => '商圈名称长度不能超过20',
        'name.regex' => '商圈名称为纯中文',
        'status.in' => '状态范围不合法',
        'listorder.require' => '排序序号不能为空',
        'listorder.regex' => '排序序号必须为大于或等于0的数'
    ];

    /**
     * 场景设置
     *
     */
    protected $scene = [
        'add' => ['name','city_id','parent_id','status'], //添加功能
        'listorder' => ['id','listorder'], //排序
        'status' => ['id','status'],   //状态
        'delete' => ['id','delete'],   //删除
        'update' => ['name','city_id','parent_id'],  //修改
    ];
}
