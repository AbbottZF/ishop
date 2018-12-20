<?php
/**
 * 后台接口返回
 * @param type $code
 * @param type $data
 * @param type $msg
 * @return type
 */
function adminMsg($code=1,$data=[],$msg=''){
    $arr = [
        'code'=>$code,'data'=>$data,'msg'=>empty($msg)?app\admin\service\AdminService::getMsg($code):$msg,
    ];
    return json_encode($arr,JSON_UNESCAPED_UNICODE);
}
/**
 * 错误提示返回
 * @param type $code
 * @param type $msg
 * @return type
 */
function adminErr($code=0,$msg=''){
    $arr = [
        'code'=>$code,'msg'=>empty($msg)?app\admin\service\AdminService::getMsg($code):$msg,
    ];
    return json_encode($arr,JSON_UNESCAPED_UNICODE);
}