<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\admin\controller;
use app\common\controller\AdminBase;

class User extends AdminBase{
    
    protected function _initialize() {
        parent::_initialize();
    }
    
    public function login(){
        return adminMsg(['token'=>'admin']);
        return json_encode(['444444']);
    }
}
