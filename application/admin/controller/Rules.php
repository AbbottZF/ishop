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
    public function create(){
        if($this->request->isPost()){
            $data = $this->request->param();
            $data['sort'] = 0;
            $data['status'] = 1;
            $vali = \think\Loader::validate('Rules');
            if(!$vali->scene('create')->check($data)){
                return adminErr(0,$vali->getError());
            }
            $data['meta'] = json_encode($data['meta'],JSON_UNESCAPED_UNICODE);
            $res = $this->rules_model->createInfo($data);
            return $res === FALSE ? adminErr(1000): adminMsg(1,$res);
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
            $info = $this->rules_model->getColumn(['parent_id'=>$data['parent_id']],'id,name');
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
            $info = $this->rules_model->getColumn(['parent_id'=>$data['parent_id']],'name',TRUE);
            return adminMsg(1,$info);
        }
    }
}