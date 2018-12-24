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
                return adminErr();
            }
            $where = ['name'=>$data['username']];
            $where['password'] = md5($data['password'].Config::get('salf'));
            $info = $this->admin_user_model->getInfo($where,'id,status');
            if(empty($info)){
                return adminErr(0,'用户不存在，或账号、密码错误！');
            }
            if($info['status'] !== 1){
                return adminErr(0,'用户已禁用！');
            }
            $res = $this->admin_user_model->updateInfo(['last_login_time'=> time(),'last_login_ip'=> $this->request->ip()],['id'=>$info['id']]);
            if($res ===  false){
                return adminErr(0,'登录失败！');
            }
            $token = 'admin';
            return adminMsg(1,['token'=>uniqid()]);
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
