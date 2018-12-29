<?php

namespace app\admin\validate;
use think\Validate;

class Rules extends Validate{
    
    protected $rule = [
        'id'=>'require|integer',
        'name'=>'require|unique:rules',
        'code'=>'require',
        'sort'=>'require|integer',
        'status'=>'require'
    ];
    
    protected $message = [
        'id.require'=>'请提供数据信息！',
        'id.integer'=>'请提供正确数据信息！',
        'parent_id.require'=>'请正确信息！',
        'name.require'=>'请填写名称！',
        'name.unique'=>'名称已存在！',
        'code.require'=>'请填写类型编码！',
        'sort.require'=>'请填写排序值！',
        'sort.integer'=>'排序值必须为整数！',
        'status.require'=>'请提供状态！',
    ];
    
    protected $scene = [
        'create'=>['name','sort','status'],
        'update'=>['id']
    ];
}
