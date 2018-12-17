<?php
namespace app\common\model;
use think\Model;

class GoodsType extends Model{
    
    /**
     * 分页获取数据
     * @param type $page
     * @param type $condition
     */
    public function getPage($page=1,$where=[],$order = 'create_time desc'){
        return $this->where($where)->order($order)->paginate(15,false,['page'=>$page])->each(function($item,$key){
            
        });
    }
}
