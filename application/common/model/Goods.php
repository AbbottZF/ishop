<?php
namespace app\common\model;
use think\Model;

class Goods extends Model{
    
    /**
     * 分页获取数据
     * @param type $page
     * @param type $condition
     */
    public function getPage($page=1,$where=[],$order = 'create_time desc'){
        return $this->where($where)->order($order)->paginate(15,false,['page'=>$page])->each(function($item,$key){});
    }
    /**
     * 新增数据
     * @param array $info
     * @return boolean
     */
    public function createInfo($info){
        $res = $this->allowField(TRUE)->save($info);
        if($res === FALSE){
            return false;
        }
        $info['id'] = $this->id;
        return $info;
    }
    /**
     * 更新数据
     * @param type $info
     * @param type $where
     * @return type
     */
    public function updateInfo($info,$where=[]){
        $res = $this->allowField(TRUE)->where($where)->update($info);
        return $res === FALSE?false:true;
    }
    /**
     * 获取单条记录
     * @param type $where
     * @param type $field
     * @return type
     */
    public function getInfo($where=[],$field=true){
        return $this->field($field)->where($where)->find();
    }
    /**
     * 获取单字段值
     * @param type $where
     * @param type $field
     * @return type
     */
    public function getValue($where=[],$field='id'){
        return $this->where($where)->value($field);
    }
    /**
     * 获取单列值
     * @param type $where
     * @param type $field
     * @return type
     */
    public function getColumn($where=[],$field='id'){
        return $this->where($where)->column($field);
    }
    /**
     * 获取集合
     * @param type $where
     * @param type $field
     * @param type $order
     * @return type
     */
    public function getList($where=[],$field=true,$order='create_time desc'){
        return $this->field($field)->where($where)->order($order)->select();
    }
}
