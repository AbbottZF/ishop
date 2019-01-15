<?php

namespace app\admin\controller;
use app\common\controller\AdminBase;
/**
 * Description of Index
 *
 * @author zfeng
 */
class Index extends AdminBase{
    
    public function index(){
        return $this->fetch('base/main');
    }
}
