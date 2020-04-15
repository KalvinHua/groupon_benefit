<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{
    /**
     * 控制台页面
     *
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }
    public function welcome()
    {
        return '欢迎来到控制台';
//        \phpmailer\Email::send('396174609@qq.com','测试','朗哥煞笔');
    }
//    public function test(){
//        \Map::getLngLat('北京昌平沙河地铁');
//    }
//    public function test2(){
//        return \Map::staticimage('北京昌平沙河地铁');
//    }
}
