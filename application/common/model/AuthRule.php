<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\common\model;
use think\Model;

class AuthRule extends Model{
    
    public function getInfo($where=[],$field=true){
        return $this->field($field)->where($where)->find()->toArray();
    }
}