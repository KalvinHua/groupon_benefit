<?php
/****
 * 图像上传接口
 */
namespace app\api\controller;

use think\Controller;

class Image extends Controller
{
    /***
     *
     * @return array
     */
    public function upload(){
        //获取上传的文件
        $file = $this->request->file('file');
        //给定一个目录
        $info = $file->move('upload');
        if($info && $info->getPathname()){
//            return ['code'=>1,'msg'=>'success','data'=>[]];
            return show(1,'success','/'.$info->getPathname());
        }else{
            return show(0,'upload error');
        }
    }
}