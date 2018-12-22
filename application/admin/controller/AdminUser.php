<?php

namespace app\admin\controller;
use app\common\controller\AdminBase;
use app\common\model\AdminUser as AU;
use think\Config;

class AdminUser extends AdminBase{
    
    protected $admin_user_model;

    protected function _initialize() {
        parent::_initialize();
        $this->admin_user_model = new AU();
    }
    /**
     * 登陆
     * @return type
     */
    public function login(){
        if($this->request->isPost()){
            $data = $this->request->param();
            if(empty($data['username']) || empty($data['password'])){
                return adminErr('数据校验不通过！');
            }
            $where = [
                'name'=>$data['username'],
                'password'=> md5($data['password'].Config::get('salf')),
                'status'=>1
            ];
            $info = $this->admin_user_model->getInfo($where);
            return adminMsg(1,['token'=>'amdmin']);
        }
    }
    public function getInfo(){
        if($this->request->isPost()){
            return adminMsg(1,['avatar'=>'http://thirdwx.qlogo.cn/mmopen/o2GQP0lHTgIxicDrXKjKxpZFnFX0tRIod8vavsufrbpPPPSO2C8K5bIYOSE2tZXibYZBhu8HXIRtxRIzfpyvI4riaCFBjhMKyao/132','name'=>'admin','roles'=>['admin']]);//测试
        }
    }
    
    public function logOut(){
        if($this->request->isPost()){
            return adminMsg(1,['token'=>'admin']);
        }
    }
}
