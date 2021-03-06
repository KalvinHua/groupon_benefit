<?php
/***
 * 获取二级城市数据接口
 */
namespace app\api\controller;

use think\Controller;
use \app\common\model\City as CityModel;
use \app\common\validate\City as CityValidte;


class City extends Controller
{
    /**
     * @var city 模型对象  validate 验证器对象
     */
    private $city;
    private $valid;

    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->city = new CityModel;
        $this->valid = new CityValidte;
    }

    /**
     * 获取并显示表中为一级的城市 页面
     * parent_id为0为一级城市页面
     * paren_id其他为其对应的城市页面
     *
     * @return mixed
     */
    public function getCityByParentId()
    {
        //获取parent_id = id 用于获取父节点为id的节点
        $id = $this->request->param('id');
        if(!$id){
            $this->error('参数不合法');
        }
        //传入id 获取为parent_id为id的值,true为状态为正常的节点
        $citys = $this->city->getFirstCity($id,true);
        if(!$citys){
            return show(0,'error');
        }
        return show(1,'success',$citys);
    }

    /**
     * 更改分类
     * @return mixed
     */
    public function listorder()
    {
        $data = $this->request->param();
        if (!$this->valid->scene('listorder')->check($data)) {
            $this->error($this->valid->getError());
        }
        $res = $this->city->save(['listorder' => $data['listorder']], ['id' => $data['id']]);
        if ($res) {
            $this->result($_SERVER['HTTP_REFERER'],1,'更新成功');
        } else {
            $this->result($_SERVER['HTTP_REFERER'],0,'更新失败');
        }
    }
}
