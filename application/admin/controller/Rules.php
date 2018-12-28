<?php

namespace app\admin\controller;

use app\common\controller\AdminBase;
use app\common\model\Rules as R;

class Rules extends AdminBase{
    
    protected $rules_model;
    
    protected function _initialize() {
        parent::_initialize();
        $this->rules_model = new R();
    }
    
    /**
     * 新增数据
     * @return type
     */
    public function createInfo(){
        if($this->request->isPost()){
            $data = $this->request->param();
            $data['status'] = 1;
            $vali = \think\Loader::validate('Rules');
            if(!$vali->scene('create')->check($data)){
                return adminErr(0,$vali->getError());
            }
            $res = $this->goods_type_model->createInfo($data);
            return $res === FALSE ? adminErr(1000): adminMsg(1001,$res);
        }
    }
    /**
     * 获取单列值
     * @return type
     */
    public function getColumn(){
        if($this->request->isPost()){
            $data = $this->request->param();
            if(!isset($data['parent_id'])){
                return adminErr();
            }
            $info = $this->goods_type_model->getColumn(['parent_id'=>$data['parent_id']],'id,name');
            return adminMsg(1,$info);
        }
    }
    /**
     * 获取分组
     * @return type
     */
    public function getGroup(){
        if($this->request->isPost()){
            $data = $this->request->param();
            if(!isset($data['parent_id'])){
                return adminErr();
            }
            $info = $this->goods_type_model->getColumn(['parent_id'=>$data['parent_id']],'name',TRUE);
            return adminMsg(1,$info);
        }
    }
}