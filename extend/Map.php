<?php
/**
 * Created by PhpStorm.
 * 百度地图相关业务封装
 * User: zhangronghua
 * Date: 2019/11/7
 * Time: 8:10 PM
 */
class Map {
    /**
     * 根据地址来获取经纬度
     * @param $address
     * @return string
     */
    public static function getLngLat($address){

        if(!$address){
            return '';
        }
        //http://api.map.baidu.com/geocoding/v3/address=北京市海淀区上地十街10号&output=json&ak=rtSlobDQiVVkV8UOuleSgDATQ80Z9gBH&callbalck=showLoaction
        $data = [
            'address' => $address,
            'output' => 'json',
            'ak' => config('map.ak'),
            'callbalck' => 'showLoaction',
        ];
        $url = config('map.baidu_map_url'). config('map.geocoding').'?'.http_build_query($data);
        //获取内容
        //1 file_get_contents($url)
        //2 crul
        $result = doCurl($url);
        if($result){
            return json_decode($result,true);
        }else{
            return [];
        }
    }

    public static function staticimage($center){
        //http://api.map.baidu.com/staticimage/v2?ak=E4805d16520de693a3fe707cdc962045&mcode=666666&center=116.403874,39.914888&width=300&height=200&zoom=11
        if(!$center){
            return '';
        }
        $data = [
            'ak' => config('map.ak'),
            'width' => config('map.width'),
            'height' => config('map.height'),
            'ak' => config('map.ak'),
            'center' => $center,
            'markers' => $center,
            'scale' => 2,
            'zoom'=> 11,
        ];
        $url = config('map.baidu_map_url'). config('map.staticimage').'?'.http_build_query($data);
        //获取内容
        //1 file_get_contents($url)
        //2 crul
        $result = doCurl($url);
//        dump(json_decode($result,true));exit;
        if($result){
            //返回图片
            return $result;
        }else{
            return [];
        }

    }

    public static function toloacation($data){
        if(!$data){
            return '';
        }
        $queryData = [
            'location' => $data['location'],
            'title'=> $data['title'],
            'content' => $data['content'],
            'output' => 'html',
            'src' => 'webapp.benefit.benefitdemo'
        ];
        $url = config('map.baidu_map_url').config('map.marker').'?location='.$data['location'].'&title='.$data['title'].'&content='.$data['content'].'&output=html&src=webapp.benefit.benefitdemo';
        return $url;
    }
}