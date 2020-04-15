<?php
namespace app\index\controller;

use think\Controller;

class Map extends Controller
{
    public function getMapImage($data)
    {
        return \Map::staticimage($data);
    }

    public function getlocation()
    {
        $data = request()->only(['location','title','content']);
        $url = \Map::toloacation($data);
        $this->redirect($url);
    }
}
