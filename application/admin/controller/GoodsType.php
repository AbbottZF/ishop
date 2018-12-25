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
     * @return type
     */
    public function getPage(){
        if($this->request->isPost()){
            $data = $this->request->param();
            $data['page'] = empty($data['page'])?1:$data['page'];
            $where = ['status'=>1];
            if(isset($data['parent_id']) && $data['parent_id'] >= 0){
                $where['parent_id'] = $data['parent_id'];
            }
            if(!empty($data['name'])){
                $where['name'] = ['LIKE',"%".$data['name']."%"];
            }
            $list = $this->goods_type_model->getPage($data['page'],$where);
            return adminMsg(1,$list);
        }
    }
    /**
     * 新增数据
     * @return type
     */
    public function createInfo(){
        if($this->request->isPost()){
            $data = $this->request->param();
            $data['status'] = 1;
            $vali = \think\Loader::validate('GoodsType');
            if(!$vali->scene('create')->check($data)){
                return adminErr(0,$vali->getError());
            }
            $res = $this->goods_type_model->createInfo($data);
            return $res === FALSE ? adminErr(1000): adminMsg(1001,$res);
        }
    }
    /**
     * 更新数据
     * @return type
     */
    public function updateInfo(){
        if($this->request->isPost()){
            $data = $this->request->param();
            $vali = \think\Loader::validate('GoodsType');
            if(!$vali->scene('update')->check($data)){
                return adminErr(0,$vali->getError());
            }
            $info = $data;
            unset($info['id']);
            $res = $this->goods_type_model->updateInfo($info,['id'=>$data['id']]);
            return $res === FALSE ? adminErr(2000): adminMsg(2001,[]);
        }
    }
    /**
     * 获取单条信息
     * @return type
     */
    public function getInfo(){
        if($this->request->isPost()){
            $data = $this->request->param();
            if(empty($data['goos_type_id'])){
                return adminErr();
            }
            $info = $this->goods_type_model->getInfo(['id'=>$data['goos_type_id']]);
            return adminMsg(101,$info);
        }
    }
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
    public function getList(){
        if($this->request->isPost()){
            $data = $this->request->param();
            if(!isset($data['parent_id'])){
                return adminErr();
            }
            $info = $this->goods_type_model->getList(['parent_id'=>$data['parent_id']],'id,name');
            return adminMsg(1,$info);
        }
    }
}

