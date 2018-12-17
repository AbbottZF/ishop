<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\admin\controller;
use app\common\controller\AdminBase;
use app\common\model\GoodsType as GT;

class GoodsType extends AdminBase{
    
    protected $goods_type_model;
    
    protected function _initialize() {
        parent::_initialize();
        $this->goods_type_model = new GT();
    }
    
    /**
     * 获取列表
     */
    public function getPage(){
        $data = $this->request->param();
        $data['page'] = empty($data['page'])?1:$data['page'];
        $list = $this->goods_type_model->getPage($data['page'],['status'=>1]);
        return adminMsg($list);
    }
}

