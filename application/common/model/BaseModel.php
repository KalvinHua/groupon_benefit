<?php
/**
 * Created by PhpStorm.
 * User: zhangronghua
 * Date: 2019/11/13
 * Time: 8:58 PM
 */
/**
 * BaseModel    公公的model层
 */
namespace app\common\model;


use think\Model;

class BaseModel extends Model
{
    protected $autoWriteTimestamp = true;
    public function add($data){
        $data['status'] = 0;
        $this->save($data);
        return $this->id;
    }
}