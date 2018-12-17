<?php

namespace app\common\model;
use think\Model;

class AdminUser extends Model{
    /**
     * 用户信息
     */
    public function getInfo($where=[]){
        return $this->where($where)->find();
    }
}